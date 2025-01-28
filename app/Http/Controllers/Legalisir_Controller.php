<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\departemen;
use App\Models\prodi;
use Hashids;

class Legalisir_Controller extends Controller
{

    private function deleteExpiredSurat()
    {
        $expiredSurat = DB::table('legalisir')
            ->where('created_at', '<', Carbon::now('Asia/Jakarta')->subMonths(6))
            ->get();

        foreach ($expiredSurat as $surat) {
            if ($surat->file_ijazah) {
                $filePathIjazah = public_path('storage/pdf/legalisir/ijazah/' . $surat->file_ijazah);
                if (file_exists($filePathIjazah)) {
                    unlink($filePathIjazah);
                }
            }

            if ($surat->file_transkrip) {
                $filePathTranskrip = public_path('storage/pdf/legalisir/transkrip/' . $surat->file_transkrip);
                if (file_exists($filePathTranskrip)) {
                    unlink($filePathTranskrip);
                }
            }

            DB::table('legalisir')->where('id', $surat->id)->delete();
        }
    }


    public function index(Request $request)
    {
        $user = Auth::user();

        $this->deleteExpiredSurat();

        $search = $request->input('search');

        $query = DB::table('legalisir')
            ->join('prodi', 'legalisir.prd_id', '=', 'prodi.id')
            ->join('users', 'legalisir.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('users_id', $user->id)
            ->select(
                'legalisir.id',
                'users.id as users_id',
                'prodi.id as prd_id',
                'departement.id as dpt_id',
                'users.nama as nama_mhw',
                'users.nim_nip',
                'users.nowa',
                'users.almt_asl',
                'departement.nama_dpt',
                'prodi.nama_prd',
                'legalisir.no_resi',
                'legalisir.ambil',
                'legalisir.jenis_lgl',
                'legalisir.keperluan',
                'legalisir.tgl_lulus',
                'legalisir.almt_kirim',
                'legalisir.kcmt_kirim',
                'legalisir.kdps_kirim',
                'legalisir.klh_kirim',
                'legalisir.kota_kirim',
                'legalisir.file_ijazah',
                'legalisir.file_transkrip',
                'legalisir.role_surat',
            );

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%")
                    ->orWhere('users.nim_nip', 'like', "%{$search}%")
                    ->orWhere('departement.nama_dpt', 'like', "%{$search}%")
                    ->orWhere('users.almt_asl', 'like', "%{$search}%")
                    ->orWhere('almt_kirim', 'like', "%{$search}%")
                    ->orWhere('prodi.nama_prd', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        $prodi = prodi::where('id', $user->prd_id)->first();
        $departemen = departemen::where('id', $prodi->dpt_id)->first();

        return view('legalisir.index', compact('data', 'user', 'departemen', 'prodi'));
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'file_ijazah' => 'nullable|mimes:pdf|max:500',
            'file_transkrip' => 'nullable|mimes:pdf|max:500',
            'keperluan' => 'required',
            'tgl_lulus' => 'required',
            'jenis_lgl' => 'required',
            'ambil' => 'required',
        ], [
            'file_ijazah.mimes' => 'File ijazah wajib bertipe PDF',
            'file_transkrip.mimes' => 'File transkrip wajib bertipe PDF',
            'file_ijazah.max' => 'Ukuran file transkrip melebihi 500 Kilobytes',
            'file_transkrip.max' => 'Ukuran file ijazah melebihi 500 Kilobytes',
            'keperluan.required' => 'Kolom keperluan wajib diisi',
            'tgl_lulus.required' => 'Kolom tanggal lulus wajib diisi',
            'jenis_lgl.required' => 'Jenis Legalisir wajib diisi',
            'ambil.required' => 'Kolom pengambilan wajib diisi',
        ]);

        $user = Auth::user();

        $nama_ijazah = null;
        if ($request->hasFile('file_ijazah')) {
            $file_ijazah = $request->file('file_ijazah');
            $tanggal_file = Carbon::now('Asia/Jakarta')->format('Y-m-d_His');
            $nama_ijazah = 'Ijazah_' . str_replace(' ', '_', $user->nama) . '_' . $user->nim_nip . '_' . $tanggal_file . '.' . $file_ijazah->getClientOriginalExtension();
            $file_ijazah->move(public_path('storage/pdf/legalisir/ijazah'), $nama_ijazah);
        }

        $nama_transkrip = null;
        if ($request->hasFile('file_transkrip')) {
            $file_transkrip = $request->file('file_transkrip');
            $tanggal_file = Carbon::now('Asia/Jakarta')->format('Y-m-d_His');
            $nama_transkrip = 'Transkrip_' . str_replace(' ', '_', $user->nama) . '_' . $user->nim_nip . '_' . $tanggal_file . '.' . $file_transkrip->getClientOriginalExtension();
            $file_transkrip->move(public_path('storage/pdf/legalisir/transkrip'), $nama_transkrip);
        }

        $existingSurat = DB::table('legalisir')
        ->where('users_id', $user->id)
        ->first();

        if ($existingSurat) {
            if ($request->jenis_lgl === 'ijazah' && $existingSurat->jenis_lgl === 'ijazah_transkrip') {
                return redirect()->back()->withErrors(['error' => 'Anda sudah mengajukan legalisir ijazah dan transkrip, tidak bisa mengajukan lagi.']);
            }

            if ($request->jenis_lgl === 'transkrip' && $existingSurat->jenis_lgl === 'ijazah_transkrip') {
                return redirect()->back()->withErrors(['error' => 'Sudah mengajukan legalisir ijazah dan transkrip, tidak bisa mengajukan lagi.']);
            }

            if ($request->jenis_lgl === 'ijazah_transkrip' && in_array($existingSurat->jenis_lgl, ['ijazah', 'transkrip'])) {
                return redirect()->back()->withErrors(['error' => 'Tidak bisa mengajukan legalisir karena sudah mengajukan sebelumnya']);
            }
        }

        $id_lgl = mt_rand(1000000000000, 9999999999999);

        $data = [
            'id' => $id_lgl,
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'ambil' => $request->ambil,
            'jenis_lgl' => $request->jenis_lgl,
            'keperluan' => $request->keperluan,
            'tgl_lulus' => $request->tgl_lulus,
            'almt_kirim' => $request->almt_kirim ?: '-',
            'kcmt_kirim' => $request->kcmt_kirim,
            'kdps_kirim' => $request->kdps_kirim,
            'klh_kirim' => $request->klh_kirim,
            'kota_kirim' => $request->kota_kirim,
            'file_ijazah' => $nama_ijazah,
            'file_transkrip' => $nama_transkrip,
            'tanggal_surat' => Carbon::now('Asia/Jakarta')->format('Y-m-d'),
            'created_at' => Carbon::now('Asia/Jakarta'),
        ];

        DB::table('legalisir')->insert($data);

        return redirect()->route('legalisir.index')->with('success', 'Legalisir berhasil dibuat');
    }

    public function edit($id)
    {
        $user = Auth::user();

        $decodedId = Hashids::decode($id);

        $data = DB::table('legalisir')->where('id', $decodedId[0])->first();

        if (!$data) {
            return redirect()->route('legalisir.index')->withErrors('Data tidak ditemukan.');
        }

        $prodi = prodi::where('id', $user->prd_id)->first();
        $departemen = departemen::where('id', $prodi->dpt_id)->first();

        return view('legalisir.edit', compact('data', 'user', 'prodi', 'departemen'));
    }


    public function update(Request $request, $id)
    {
        $data = DB::table('legalisir')->where('id', $id)->first();

        $validated = $request->validate([
            'file_ijazah' => 'nullable|mimes:pdf|max:500',
            'file_transkrip' => 'nullable|mimes:pdf|max:500',
            'keperluan' => 'required',
            'tgl_lulus' => 'required',
            'jenis_lgl' => 'required',
            'ambil' => 'required',
        ], [
            'file_ijazah.mimes' => 'File ijazah wajib bertipe PDF',
            'file_transkrip.mimes' => 'File transkrip wajib bertipe PDF',
            'file_ijazah.max' => 'Ukuran file transkrip melebihi 500 Kilobytes',
            'file_transkrip.max' => 'Ukuran file ijazah melebihi 500 Kilobytes',
            'keperluan.required' => 'Kolom keperluan wajib diisi',
            'tgl_lulus.required' => 'Kolom tanggal lulus wajib diisi',
            'jenis_lgl.required' => 'Jenis Legalisir wajib diisi',
            'ambil.required' => 'Kolom pengambilan wajib diisi',
        ]);

        $updateData = [];

        if ($request->hasFile('file_ijazah')) {
            $file_ijazah = $request->file('file_ijazah');
            $ijazah_extensi = $file_ijazah->extension();
            $tanggal_file = Carbon::now('Asia/Jakarta')->setTimezone('Asia/Jakarta')->format('Y-m-d_His');
            $nama_ijazah = 'Ijazah_' . str_replace(' ', '_', Auth::user()->nama) . '_' . Auth::user()->nim_nip . '_' . $tanggal_file . '.' . $ijazah_extensi;
            $file_ijazah->move(public_path('storage/pdf/legalisir/ijazah'), $nama_ijazah);
            $updateData['file_ijazah'] = $nama_ijazah;
        } else {
            $updateData['file_ijazah'] = $data->file_ijazah;
        }

        if ($request->hasFile('file_transkrip')) {
            $file_transkrip = $request->file('file_transkrip');
            $transkrip_extensi = $file_transkrip->extension();
            $tanggal_file = Carbon::now('Asia/Jakarta')->setTimezone('Asia/Jakarta')->format('Y-m-d_His');
            $nama_transkrip = 'Transkrip_' . str_replace(' ', '_', Auth::user()->nama) . '_' . Auth::user()->nim_nip . '_' . $tanggal_file . '.' . $transkrip_extensi;
            $file_transkrip->move(public_path('storage/pdf/legalisir/transkrip'), $nama_transkrip);
            $updateData['file_transkrip'] = $nama_transkrip;
        } else {
            $updateData['file_transkrip'] = $data->file_transkrip;
        }

        DB::table('legalisir')->where('id', $id)->update(array_merge($updateData, [
            'ambil' => $request->ambil,
            'jenis_lgl' => $request->jenis_lgl,
            'keperluan' => $request->keperluan,
            'tgl_lulus' => $request->tgl_lulus,
            'almt_kirim' => $request->almt_kirim ?: '-',
            'kcmt_kirim' => $request->kcmt_kirim,
            'kdps_kirim' => $request->kdps_kirim,
            'klh_kirim' => $request->klh_kirim,
            'kota_kirim' => $request->kota_kirim,
            'role_surat' => 'admin',
            'catatan_surat' => '-',
        ]));

        return redirect()->route('legalisir.index')->with('success', 'Legalisir berhasil diperbarui');
    }
}
