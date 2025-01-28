<?php

namespace App\Http\Controllers;

use App\Models\departemen;
use App\Models\prodi;
use App\Models\srt_bbs_pnjm;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Mpdf\Mpdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Hashids;

class Srt_Bbs_Pnjam_Controller extends Controller
{
    private function deletesurat()
    {
        DB::table('srt_bbs_pnjm')
            ->where('created_at', '<', Carbon::now('Asia/Jakarta')->subMonths(6))
            ->delete();
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        $this->deletesurat();

        $search = $request->input('search');

        $query = DB::table('srt_bbs_pnjm')
            ->join('prodi', 'srt_bbs_pnjm.prd_id', '=', 'prodi.id')
            ->join('users', 'srt_bbs_pnjm.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('users_id', $user->id)
            ->select(
                'srt_bbs_pnjm.id',
                'users.id as users_id',
                'prodi.id as prd_id',
                'departement.id as dpt_id',
                'users.nama as nama_mhw',
                'users.nim_nip',
                'users.nowa',
                'departement.nama_dpt',
                'prodi.nama_prd',
                'srt_bbs_pnjm.dosen_wali',
                'srt_bbs_pnjm.almt_smg',
                'srt_bbs_pnjm.role_surat',
            );

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%")
                    ->orWhere('users.nim_nip', 'like', "%{$search}%")
                    ->orWhere('users.nowa', 'like', "%{$search}%")
                    ->orWhere('almt_smg', 'like', "%{$search}%")
                    ->orWhere('departement.nama_dpt', 'like', "%{$search}%")
                    ->orWhere('prodi.nama_prd', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        $prodi = prodi::where('id', $user->prd_id)->first();
        $departemen = departemen::where('id', $prodi->dpt_id)->first();

        return view('srt_bbs_pnjm.index', compact('data', 'user', 'departemen', 'prodi'));
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'almt_smg' => 'required',
            'dosen_wali' => 'required',
        ], [
            'dosen_wali.required' => 'Dosen Wali wajib diisi',
            'almt_smg.required' => 'Alamat kos / tempat tinggal semarang wajib diisi',
        ]);

        $user = Auth::user();

        $existingSurat = DB::table('srt_bbs_pnjm')
            ->where('users_id', $user->id)
            ->where('role_surat', '!=', 'mahasiswa')
            ->first();

        if ($existingSurat) {
            return redirect()->back()->withErrors(['error' => 'Hanya diperbolehkan satu surat yang diproses hingga surat itu selesai']);
        }

        $id_surat = mt_rand(1000000000000, 9999999999999);

        DB::table('srt_bbs_pnjm')->insert([
            'id' => $id_surat,
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'dosen_wali' => $request->dosen_wali,
            'almt_smg' => $request->almt_smg,
            'tanggal_surat' => Carbon::now('Asia/Jakarta')->format('Y-m-d'),
            'created_at' => Carbon::now('Asia/Jakarta'),
        ]);

        return redirect()->route('srt_bbs_pnjm.index')->with('success', 'Surat berhasil dibuat');
    }

    public function edit($id)
    {
        $user = Auth::user();

        $decodedId = Hashids::decode($id);

        $data = DB::table('srt_bbs_pnjm')->where('id', $decodedId[0])->first();

        if (!$data) {
            return redirect()->route('srt_bbs_pnjm.index')->withErrors('Data tidak ditemukan.');
        }

        $prodi = prodi::where('id', $user->prd_id)->first();
        $departemen = departemen::where('id', $prodi->dpt_id)->first();

        return view('srt_bbs_pnjm.edit', compact('data', 'user', 'prodi', 'departemen'));
    }


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'almt_smg' => 'required',
            'dosen_wali' => 'required',
        ], [
            'dosen_wali.required' => 'Dosen Wali wajib diisi',
            'almt_smg.required' => 'Alamat kos / tempat tinggal semarang wajib diisi',
        ]);

        DB::table('srt_bbs_pnjm')->where('id', $id)->update([
            'dosen_wali' => $request->dosen_wali,
            'almt_smg' => $request->almt_smg,
            'role_surat' => 'admin',
            'catatan_surat' => '-',
        ]);

        return redirect()->route('srt_bbs_pnjm.index')->with('success', 'Surat berhasil diperbarui');
    }

    function download($id)
    {
        $srt_bbs_pnjm = DB::table('srt_bbs_pnjm')
            ->join('prodi', 'srt_bbs_pnjm.prd_id', '=', 'prodi.id')
            ->join('users', 'srt_bbs_pnjm.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('srt_bbs_pnjm.id', $id)
            ->select(
                'srt_bbs_pnjm.id',
                'srt_bbs_pnjm.no_surat',
                'srt_bbs_pnjm.tanggal_surat',
                'users.id as users_id',
                'prodi.id as prodi_id',
                'departement.id as departement_id',
                'users.nama as nama_mhw',
                'users.nim_nip',
                'users.nowa',
                'departement.nama_dpt',
                'prodi.nama_prd',
                'srt_bbs_pnjm.dosen_wali',
                'srt_bbs_pnjm.almt_smg',
                'srt_bbs_pnjm.role_surat',
            )
            ->first();

        if (!$srt_bbs_pnjm) {
            return redirect()->back()->with('error', 'Data not found');
        }

        if ($srt_bbs_pnjm->tanggal_surat) {
            $srt_bbs_pnjm->tanggal_surat = Carbon::parse($srt_bbs_pnjm->tanggal_surat)->format('d-m-Y');
        }

        $qrUrl = url('/legal/srt_bbs_pnjm/' . $srt_bbs_pnjm->id);
        $qrCodePath = 'storage/qrcodes/qr-' . $srt_bbs_pnjm->id . '.png';
        $qrCodeFullPath = public_path($qrCodePath);

        if (!File::exists(dirname($qrCodeFullPath))) {
            File::makeDirectory(dirname($qrCodeFullPath), 0755, true);
        }

        QrCode::format('png')->size(100)->generate($qrUrl, $qrCodeFullPath);
        $pdf = Pdf::loadView('srt_bbs_pnjm.view', compact('srt_bbs_pnjm', 'qrCodePath'));

        // $mpdf = new Mpdf();
        // $html = View::make('srt_bbs_pnjm.view', compact('srt_bbs_pnjm', 'qrCodePath'))->render();
        // $mpdf->WriteHTML($html);

        $namaMahasiswa = $srt_bbs_pnjm->nama_mhw;
        $tanggalSurat = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $fileName = 'Surat_Bebas_Pinjam_' . str_replace(' ', '_', $namaMahasiswa) . '_' . $tanggalSurat . '.pdf';
        // $mpdf->Output($fileName, 'D');
        return $pdf->download($fileName);
    }

    function admin(Request $request)
    {
        $search = $request->input('search');

        $query = DB::table('srt_bbs_pnjm')
            ->join('users', 'srt_bbs_pnjm.users_id', '=', 'users.id')
            ->select(
                'srt_bbs_pnjm.id',
                'users.nama as nama_mhw',
            )
            ->where('srt_bbs_pnjm.role_surat', 'admin')
            ->orderBy('srt_bbs_pnjm.tanggal_surat', 'asc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('srt_bbs_pnjm.admin', compact('data'));
    }

    function admin_cek($id)
    {
        $srt_bbs_pnjm = DB::table('srt_bbs_pnjm')
            ->join('prodi', 'srt_bbs_pnjm.prd_id', '=', 'prodi.id')
            ->join('users', 'srt_bbs_pnjm.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('srt_bbs_pnjm.id', $id)
            ->select(
                'srt_bbs_pnjm.id',
                'srt_bbs_pnjm.no_surat',
                'srt_bbs_pnjm.tanggal_surat',
                'users.id as users_id',
                'prodi.id as prd_id',
                'departement.id as dpt_id',
                'users.nama as nama_mhw',
                'users.nim_nip',
                'users.nowa',
                'users.foto',
                'departement.nama_dpt',
                'prodi.nama_prd',
                'srt_bbs_pnjm.dosen_wali',
                'srt_bbs_pnjm.almt_smg',
                'srt_bbs_pnjm.role_surat',
            )
            ->first();
        return view('srt_bbs_pnjm.cek_data', compact('srt_bbs_pnjm'));
    }

    function admin_setuju(Request $request, $id)
    {
        $srt_bbs_pnjm = srt_bbs_pnjm::where('id', $id)->first();

        $request->validate([
            'no_surat' => 'required',
        ], [
            'no_surat.required' => 'No surat wajib diisi',
        ]);

        $srt_bbs_pnjm->no_surat = $request->no_surat;
        $srt_bbs_pnjm->role_surat = 'supervisor_sd';

        $srt_bbs_pnjm->save();
        return redirect()->route('srt_bbs_pnjm.admin')->with('success', 'No surat berhasil ditambahkan');
    }

    function admin_tolak(Request $request, $id)
    {
        $srt_bbs_pnjm = srt_bbs_pnjm::where('id', $id)->first();

        $request->validate([
            'catatan_surat' => 'required',
        ], [
            'catatan_surat.required' => 'Alasan penolakan wajib diisi',
        ]);

        $srt_bbs_pnjm->catatan_surat = $request->catatan_surat;
        $srt_bbs_pnjm->role_surat = 'tolak';

        $srt_bbs_pnjm->save();
        return redirect()->route('srt_bbs_pnjm.admin')->with('success', 'Alasan penolakan telah dikirimkan');
    }

    function supervisor(Request $request)
    {
        $search = $request->input('search');

        $query = DB::table('srt_bbs_pnjm')
            ->join('users', 'srt_bbs_pnjm.users_id', '=', 'users.id')
            ->where('role_surat', 'supervisor_sd')
            ->orderBy('tanggal_surat', 'asc')
            ->select(
                'srt_bbs_pnjm.id',
                'users.nama as nama_mhw',
                'users.nim_nip',
            );

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%")
                    ->orWhere('users.nim_nip', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('srt_bbs_pnjm.supervisor', compact('data'));
    }

    function sv_cek($id)
    {
        $srt_bbs_pnjm = DB::table('srt_bbs_pnjm')
            ->join('prodi', 'srt_bbs_pnjm.prd_id', '=', 'prodi.id')
            ->join('users', 'srt_bbs_pnjm.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('srt_bbs_pnjm.id', $id)
            ->select(
                'srt_bbs_pnjm.id',
                'srt_bbs_pnjm.no_surat',
                'srt_bbs_pnjm.tanggal_surat',
                'users.id as users_id',
                'prodi.id as prd_id',
                'departement.id as dpt_id',
                'users.nama as nama_mhw',
                'users.nim_nip',
                'users.nowa',
                'users.foto',
                'departement.nama_dpt',
                'prodi.nama_prd',
                'srt_bbs_pnjm.dosen_wali',
                'srt_bbs_pnjm.almt_smg',
                'srt_bbs_pnjm.role_surat',
            )
            ->first();
        return view('srt_bbs_pnjm.cek_sv', compact('srt_bbs_pnjm'));
    }

    function sv_setuju(Request $request, $id)
    {
        $srt_bbs_pnjm = srt_bbs_pnjm::where('id', $id)->first();

        $srt_bbs_pnjm->role_surat = 'mahasiswa';

        $srt_bbs_pnjm->save();
        return redirect()->route('srt_bbs_pnjm.supervisor')->with('success', 'Surat telah disetujui');
    }

    function sv_tolak(Request $request, $id)
    {
        $srt_bbs_pnjm = srt_bbs_pnjm::where('id', $id)->first();

        $request->validate([
            'catatan_surat' => 'required',
        ], [
            'catatan_surat.required' => 'Alasan penolakan wajib diisi',
        ]);

        $srt_bbs_pnjm->catatan_surat = $request->catatan_surat . ' - Supervisor Sumber Daya';
        $srt_bbs_pnjm->catatan_surat = $request->catatan_surat;
        $srt_bbs_pnjm->role_surat = 'tolak';

        $srt_bbs_pnjm->save();
        return redirect()->route('srt_bbs_pnjm.supervisor')->with('success', 'Alasan penolakan telah dikirimkan');
    }

}
