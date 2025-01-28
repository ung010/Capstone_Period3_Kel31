<?php

namespace App\Http\Controllers;

use App\Models\departemen;
use App\Models\prodi;
use App\Models\srt_izin_penelitian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Mpdf\Mpdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Hashids;

class Srt_Izin_Penelitian_Controller extends Controller
{

    private function deletesurat()
    {
        DB::table('srt_izin_plt')
            ->where('created_at', '<', Carbon::now('Asia/Jakarta')->subMonths(6))
            ->delete();
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        $this->deletesurat();

        $search = $request->input('search');

        $query = DB::table('srt_izin_plt')
            ->join('prodi', 'srt_izin_plt.prd_id', '=', 'prodi.id')
            ->join('users', 'srt_izin_plt.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('users_id', $user->id)
            ->select(
                'srt_izin_plt.id',
                'users.id as users_id',
                'prodi.id as prd_id',
                'departement.id as dpt_id',
                'users.nama as nama_mhw',
                'users.nim_nip',
                'users.nowa',
                'users.email',
                'users.almt_asl',
                'departement.nama_dpt',
                'prodi.nama_prd',
                'srt_izin_plt.judul_data',
                'srt_izin_plt.semester',
                'srt_izin_plt.jenis_surat',
                'srt_izin_plt.nama_lmbg',
                'srt_izin_plt.almt_lmbg',
                'srt_izin_plt.role_surat',
            );

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%")
                    ->orWhere('users.nim_nip', 'like', "%{$search}%")
                    ->orWhere('semester', 'like', "%{$search}%")
                    ->orWhere('nama_lmbg', 'like', "%{$search}%")
                    ->orWhere('judul_data', 'like', "%{$search}%")
                    ->orWhere('jenis_surat', 'like', "%{$search}%")
                    ->orWhere('almt_lmbg', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        $prodi = prodi::where('id', $user->prd_id)->first();
        $departemen = departemen::where('id', $prodi->dpt_id)->first();

        return view('srt_izin_plt.index', compact('data', 'user', 'departemen', 'prodi'));
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'lampiran' => 'required',
            'semester' => 'required',
            'jenis_surat' => 'required',
            'judul_data' => 'required',
            'nama_lmbg' => 'required',
            'jbt_lmbg' => 'required',
            'kota_lmbg' => 'required',
            'almt_lmbg' => 'required',
        ], [
            'lampiran.required' => 'Lampiran wajib diisi',
            'jenis_surat.required' => 'Permohonan data wajib diisi',
            'semester.required' => 'Semester wajib diisi',
            'judul_data.required' => 'Judul/Tema Pengambilan Data Wajib diisi',
            'nama_lmbg.required' => 'Nama perusahaan  / lembaga wajib diisi',
            'jbt_lmbg.required' => 'Jabatan orang perusahaan  / lembaga wajib diisi',
            'kota_lmbg.required' => 'Kota perusahaan / lembaga wajib diisi',
            'almt_lmbg.required' => 'Alamat perusahaan / lembaga wajib diisi',
        ]);

        $user = Auth::user();

        $existingSurat = DB::table('srt_izin_plt')
            ->where('users_id', $user->id)
            ->where('role_surat', '!=', 'mahasiswa')
            ->where('jenis_surat', $request->jenis_surat)
            ->first();

        if ($existingSurat) {
            return redirect()->back()->withErrors(['error' => 'Hanya diperbolehkan mengajukan satu surat dengan permohonan data yang sama sampai surat selesai.']);
        }

        $id_surat = mt_rand(1000000000000, 9999999999999);

        DB::table('srt_izin_plt')->insert([
            'id' => $id_surat,
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'lampiran' => $request->lampiran,
            'jenis_surat' => $request->jenis_surat,
            'semester' => $request->semester,
            'judul_data' => $request->judul_data,
            'nama_lmbg' => $request->nama_lmbg,
            'jbt_lmbg' => $request->jbt_lmbg,
            'almt_lmbg' => $request->almt_lmbg,
            'kota_lmbg' => $request->kota_lmbg,
            'tanggal_surat' => Carbon::now('Asia/Jakarta')->format('Y-m-d'),
            "created_at" => Carbon::now('Asia/Jakarta'),
        ]);

        return redirect()->route('srt_izin_plt.index')->with('success', 'Surat berhasil dibuat');
    }

    public function edit($id)
    {
        $user = Auth::user();

        $decodedId = Hashids::decode($id);

        $data = DB::table('srt_izin_plt')->where('id', $decodedId[0])->first();

        if (!$data) {
            return redirect()->route('srt_izin_plt.index')->withErrors('Data tidak ditemukan.');
        }

        $prodi = prodi::where('id', $user->prd_id)->first();
        $departemen = departemen::where('id', $prodi->dpt_id)->first();

        return view('srt_izin_plt.edit', compact('data', 'user', 'prodi', 'departemen'));
    }


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'lampiran' => 'required',
            'judul_data' => 'required',
            'nama_lmbg' => 'required',
            'jbt_lmbg' => 'required',
            'kota_lmbg' => 'required',
            'almt_lmbg' => 'required',
        ], [
            'lampiran.required' => 'Lampiran wajib diisi',
            'judul_data.required' => 'Judul/Tema Pengambilan Data Wajib diisi',
            'nama_lmbg.required' => 'Nama perusahaan  / lembaga wajib diisi',
            'jbt_lmbg.required' => 'Jabatan orang perusahaan  / lembaga wajib diisi',
            'kota_lmbg.required' => 'Kota perusahaan / lembaga wajib diisi',
            'almt_lmbg.required' => 'Alamat perusahaan / lembaga wajib diisi',
        ]);

        DB::table('srt_izin_plt')->where('id', $id)->update([
            'lampiran' => $request->lampiran,
            'judul_data' => $request->judul_data,
            'nama_lmbg' => $request->nama_lmbg,
            'jbt_lmbg' => $request->jbt_lmbg,
            'almt_lmbg' => $request->almt_lmbg,
            'kota_lmbg' => $request->kota_lmbg,
            'semester' => $request->semester,
            'role_surat' => 'admin',
            'catatan_surat' => '-',
        ]);

        return redirect()->route('srt_izin_plt.index')->with('success', 'Surat berhasil diperbarui');
    }

    function download($id)
    {
        $srt_izin_plt = DB::table('srt_izin_plt')
            ->join('prodi', 'srt_izin_plt.prd_id', '=', 'prodi.id')
            ->join('users', 'srt_izin_plt.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('srt_izin_plt.id', $id)
            ->select(
                'srt_izin_plt.id',
                'srt_izin_plt.no_surat',
                'srt_izin_plt.tanggal_surat',
                'users.id as users_id',
                'prodi.id as prodi_id',
                'departement.id as dpt_id',
                'users.nama as nama_mhw',
                'users.nim_nip',
                'users.nowa',
                'users.email',
                'users.almt_asl',
                'departement.nama_dpt',
                'prodi.nama_prd',
                'srt_izin_plt.semester',
                'srt_izin_plt.judul_data',
                'srt_izin_plt.nama_lmbg',
                'srt_izin_plt.jbt_lmbg',
                'srt_izin_plt.kota_lmbg',
                'srt_izin_plt.almt_lmbg',
                'srt_izin_plt.role_surat',
            )
            ->first();

        if (!$srt_izin_plt) {
            return redirect()->back()->with('error', 'Data not found');
        }

        if ($srt_izin_plt->tanggal_surat) {
            $srt_izin_plt->tanggal_surat = Carbon::parse($srt_izin_plt->tanggal_surat)->format('d-m-Y');
        }

        $qrUrl = url('/legal/srt_izin_plt/' . $srt_izin_plt->id);
        $qrCodePath = 'storage/qrcodes/qr-' . $srt_izin_plt->id . '.png';
        $qrCodeFullPath = public_path($qrCodePath);

        if (!File::exists(dirname($qrCodeFullPath))) {
            File::makeDirectory(dirname($qrCodeFullPath), 0755, true);
        }

        QrCode::format('png')->size(100)->generate($qrUrl, $qrCodeFullPath);

        // $mpdf = new Mpdf();
        // $html = View::make('srt_izin_plt.view', compact('srt_izin_plt', 'qrCodePath'))->render();
        // $mpdf->WriteHTML($html);
        $pdf = Pdf::loadView('srt_izin_plt.view', compact('srt_izin_plt', 'qrCodePath'));

        $namaMahasiswa = $srt_izin_plt->nama_mhw;
        $tanggalSurat = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $fileName = 'Surat_Izin_Penelitian_' . str_replace(' ', '_', $namaMahasiswa) . '_' . $tanggalSurat . '.pdf';
        // $mpdf->Output($fileName, 'D');
        return $pdf->download($fileName);
    }

    function admin(Request $request)
    {
        $search = $request->input('search');

        $query = DB::table('srt_izin_plt')
            ->join('users', 'srt_izin_plt.users_id', '=', 'users.id')
            ->select(
                'srt_izin_plt.id',
                'users.nama as nama_mhw',
                'srt_izin_plt.role_surat',
            )
            ->where('srt_izin_plt.role_surat', 'admin')
            ->orderBy('srt_izin_plt.tanggal_surat', 'asc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('srt_izin_plt.admin', compact('data'));
    }

    // function admin_unduh($id)
    // {
    //     $srt_izin_plt = DB::table('srt_izin_plt')
    //         ->join('prodi', 'srt_izin_plt.prd_id', '=', 'prodi.id')
    //         ->join('users', 'srt_izin_plt.users_id', '=', 'users.id')
    //         ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
    //         ->where('srt_izin_plt.id', $id)
    //         ->select(
    //             'srt_izin_plt.id',
    //             'srt_izin_plt.no_surat',
    //             'srt_izin_plt.tanggal_surat',
    //             'srt_izin_plt.nama_mhw',
    //             'users.id as users_id',
    //             'prodi.id as prodi_id',
    //             'departement.id as dpt_id',
    //             'users.nama',
    //             'users.nim_nip',
    //             'users.nowa',
    //             'users.email',
    //             'users.almt_asl',
    //             'departement.nama_dpt',
    //             'prodi.nama_prd',
    //             'srt_izin_plt.semester',
    //             'srt_izin_plt.judul_data',
    //             'srt_izin_plt.nama_lmbg',
    //             'srt_izin_plt.jbt_lmbg',
    //             'srt_izin_plt.kota_lmbg',
    //             'srt_izin_plt.almt_lmbg',
    //             'srt_izin_plt.role_surat',
    //         )
    //         ->first();

    //     if (!$srt_izin_plt) {
    //         return redirect()->back()->with('error', 'Data not found');
    //     }

    //     if ($srt_izin_plt->tanggal_surat) {
    //         $srt_izin_plt->tanggal_surat = Carbon::parse($srt_izin_plt->tanggal_surat)->format('d-m-Y');
    //     }

    //     $qrUrl = url('/legal/srt_izin_plt/' . $srt_izin_plt->id);
    //     $qrCodePath = 'storage/qrcodes/qr-' . $srt_izin_plt->id . '.png';
    //     $qrCodeFullPath = public_path($qrCodePath);

    //     if (!File::exists(dirname($qrCodeFullPath))) {
    //         File::makeDirectory(dirname($qrCodeFullPath), 0755, true);
    //     }

    //     QrCode::format('png')->size(100)->generate($qrUrl, $qrCodeFullPath);

    //     // $mpdf = new Mpdf();
    //     // $html = View::make('srt_izin_plt.view', compact('srt_izin_plt', 'qrCodePath'))->render();
    //     // $mpdf->WriteHTML($html);
    //     $pdf = Pdf::loadView('srt_izin_plt.view', compact('srt_izin_plt', 'qrCodePath'));

    //     $namaMahasiswa = $srt_izin_plt->nama;
    //     $tanggalSurat = Carbon::now('Asia/Jakarta')->format('Y-m-d');
    //     $fileName = 'Surat_Izin_Penelitian_' . str_replace(' ', '_', $namaMahasiswa) . '_' . $tanggalSurat . '.pdf';
    //     // $mpdf->Output($fileName, 'D');
    //     return $pdf->download($fileName);
    // }

    // public function admin_unggah(Request $request, $id)
    // {
    //     $request->validate([
    //         'srt_izin_plt' => 'required|mimes:pdf'
    //     ], [
    //         'srt_izin_plt.required' => 'Surat wajib diisi',
    //         'srt_izin_plt.mimes' => 'Surat wajib berbentuk / berekstensi PDF',
    //     ]);

    //     $srt_izin_plt = DB::table('srt_izin_plt')
    //         ->join('users', 'srt_izin_plt.users_id', '=', 'users.id')
    //         ->where('srt_izin_plt.id', $id)
    //         ->select(
    //             'srt_izin_plt.id',
    //             'users.nama',
    //             'srt_izin_plt.tanggal_surat'
    //         )
    //         ->first();

    //     if (!$srt_izin_plt) {
    //         return redirect()->back()->withErrors('Data surat tidak ditemukan.');
    //     }

    //     $tanggal_surat = Carbon::parse($srt_izin_plt->tanggal_surat)->format('d-m-Y');
    //     $nama_mahasiswa = Str::slug($srt_izin_plt->nama);

    //     $file = $request->file('srt_izin_plt');
    //     $surat_extensi = $file->extension();
    //     $nama_surat = "Surat_Izin_Penelitian_{$tanggal_surat}_{$nama_mahasiswa}." . $surat_extensi;
    //     $file->move(public_path('storage/pdf/srt_izin_plt'), $nama_surat);

    //     srt_izin_penelitian::where('id', $id)->update([
    //         'file_pdf' => $nama_surat,
    //         'role_surat' => 'mahasiswa',
    //     ]);

    //     return redirect()->back()->with('success', 'Berhasil menggunggah pdf ke mahasiswa');
    // }

    function admin_cek($id)
    {
        $srt_izin_plt = DB::table('srt_izin_plt')
            ->join('prodi', 'srt_izin_plt.prd_id', '=', 'prodi.id')
            ->join('users', 'srt_izin_plt.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('srt_izin_plt.id', $id)
            ->select(
                'srt_izin_plt.id',
                'users.id as users_id',
                'prodi.id as prd_id',
                'departement.id as dpt_id',
                'users.nama',
                'users.nim_nip',
                'users.nowa',
                'users.almt_asl',
                'users.foto',
                'users.email',
                'departement.nama_dpt',
                'prodi.nama_prd',
                'srt_izin_plt.lampiran',
                'srt_izin_plt.nama_lmbg',
                'srt_izin_plt.jbt_lmbg',
                'srt_izin_plt.kota_lmbg',
                'srt_izin_plt.almt_lmbg',
                'srt_izin_plt.role_surat',
            )
            ->first();
        return view('srt_izin_plt.cek_data', compact('srt_izin_plt'));
    }

    function admin_setuju(Request $request, $id)
    {
        $srt_izin_plt = srt_izin_penelitian::where('id', $id)->first();

        $request->validate([
            'no_surat' => 'required',
        ], [
            'no_surat.required' => 'No surat wajib diisi',
        ]);

        $srt_izin_plt->no_surat = $request->no_surat;
        $srt_izin_plt->role_surat = 'supervisor_akd';

        $srt_izin_plt->save();
        return redirect()->route('srt_izin_plt.admin')->with('success', 'No surat berhasil ditambahkan');
    }

    function admin_tolak(Request $request, $id)
    {
        $srt_izin_plt = srt_izin_penelitian::where('id', $id)->first();

        $request->validate([
            'catatan_surat' => 'required',
        ], [
            'catatan_surat.required' => 'Alasan penolakan wajib diisi',
        ]);

        $srt_izin_plt->catatan_surat = $request->catatan_surat;
        $srt_izin_plt->role_surat = 'tolak';

        $srt_izin_plt->save();
        return redirect()->route('srt_izin_plt.admin')->with('success', 'Alasan penolakan telah dikirimkan');
    }

    function supervisor(Request $request)
    {
        $search = $request->input('search');

        $query = DB::table('srt_izin_plt')
            ->join('prodi', 'srt_izin_plt.prd_id', '=', 'prodi.id')
            ->join('users', 'srt_izin_plt.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('role_surat', 'supervisor_akd')
            ->orderBy('tanggal_surat', 'asc')
            ->select(
                'srt_izin_plt.id',
                'srt_izin_plt.tanggal_surat',
                'srt_izin_plt.nama_lmbg',
                'users.nama as nama_mhw',
                'users.nim_nip',
                'prodi.nama_prd'
            );

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%")
                    ->orWhere('nama_lmbg', 'like', "%{$search}%")
                    ->orWhere('users.nim_nip', 'like', "%{$search}%")
                    ->orWhere('prodi.nama_prd', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('srt_izin_plt.supervisor', compact('data'));
    }

    function cek_sv($id)
    {
        $srt_izin_plt = DB::table('srt_izin_plt')
            ->join('prodi', 'srt_izin_plt.prd_id', '=', 'prodi.id')
            ->join('users', 'srt_izin_plt.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('srt_izin_plt.id', $id)
            ->select(
                'srt_izin_plt.id',
                'users.id as users_id',
                'prodi.id as prd_id',
                'departement.id as dpt_id',
                'users.nama',
                'users.nim_nip',
                'users.nowa',
                'users.almt_asl',
                'users.foto',
                'users.email',
                'departement.nama_dpt',
                'prodi.nama_prd',
                'srt_izin_plt.lampiran',
                'srt_izin_plt.nama_lmbg',
                'srt_izin_plt.jbt_lmbg',
                'srt_izin_plt.kota_lmbg',
                'srt_izin_plt.almt_lmbg',
                'srt_izin_plt.role_surat',
            )
            ->first();
        return view('srt_izin_plt.cek_sv', compact('srt_izin_plt'));
    }

    function setuju_sv(Request $request, $id)
    {
        $srt_izin_plt = srt_izin_penelitian::where('id', $id)->first();

        $srt_izin_plt->role_surat = 'manajer';

        $srt_izin_plt->save();
        return redirect()->route('srt_izin_plt.supervisor')->with('success', 'Surat berhasil disetujui');
    }

    function tolak_sv(Request $request, $id)
    {
        $srt_izin_plt = srt_izin_penelitian::where('id', $id)->first();

        $request->validate([
            'catatan_surat' => 'required',
        ], [
            'catatan_surat.required' => 'Alasan penolakan wajib diisi',
        ]);

        $srt_izin_plt->catatan_surat = $request->catatan_surat . '- Supervisor Akademik';
        $srt_izin_plt->catatan_surat = $request->catatan_surat;
        $srt_izin_plt->role_surat = 'tolak';

        $srt_izin_plt->save();
        return redirect()->route('srt_izin_plt.supervisor')->with('success', 'Alasan penolakan telah dikirimkan');
    }

    function manajer(Request $request)
    {
        $search = $request->input('search');

        $query = DB::table('srt_izin_plt')
            ->join('prodi', 'srt_izin_plt.prd_id', '=', 'prodi.id')
            ->join('users', 'srt_izin_plt.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('role_surat', 'manajer')
            ->orderBy('tanggal_surat', 'asc')
            ->select(
                'srt_izin_plt.id',
                'srt_izin_plt.tanggal_surat',
                'srt_izin_plt.nama_lmbg',
                'users.nama as nama_mhw',
                'users.nim_nip',
                'prodi.nama_prd',
            );

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%")
                    ->orWhere('nama_lmbg', 'like', "%{$search}%")
                    ->orWhere('users.nim_nip', 'like', "%{$search}%")
                    ->orWhere('prodi.nama_prd', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('srt_izin_plt.manajer', compact('data'));
    }

    function cek_manajer($id)
    {
        $srt_izin_plt = DB::table('srt_izin_plt')
            ->join('prodi', 'srt_izin_plt.prd_id', '=', 'prodi.id')
            ->join('users', 'srt_izin_plt.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('srt_izin_plt.id', $id)
            ->select(
                'srt_izin_plt.id',
                'users.id as users_id',
                'prodi.id as prd_id',
                'departement.id as dpt_id',
                'users.nama',
                'users.nim_nip',
                'users.nowa',
                'users.almt_asl',
                'users.foto',
                'users.email',
                'departement.nama_dpt',
                'prodi.nama_prd',
                'srt_izin_plt.lampiran',
                'srt_izin_plt.nama_lmbg',
                'srt_izin_plt.jbt_lmbg',
                'srt_izin_plt.kota_lmbg',
                'srt_izin_plt.almt_lmbg',
                'srt_izin_plt.role_surat',
            )
            ->first();
        return view('srt_izin_plt.cek_manajer', compact('srt_izin_plt'));
    }

    function setuju_manajer(Request $request, $id)
    {
        $srt_izin_plt = srt_izin_penelitian::where('id', $id)->first();

        $srt_izin_plt->role_surat = 'wd1';

        $srt_izin_plt->save();
        return redirect()->route('srt_izin_plt.manajer')->with('success', 'Surat berhasil disetujui');
    }

    function tolak_manajer(Request $request, $id)
    {
        $srt_izin_plt = srt_izin_penelitian::where('id', $id)->first();

        $request->validate([
            'catatan_surat' => 'required',
        ], [
            'catatan_surat.required' => 'Alasan penolakan wajib diisi',
        ]);

        $srt_izin_plt->catatan_surat = $request->catatan_surat . '- Manajer';
        $srt_izin_plt->catatan_surat = $request->catatan_surat;
        $srt_izin_plt->role_surat = 'tolak';

        $srt_izin_plt->save();
        return redirect()->route('srt_izin_plt.manajer')->with('success', 'Alasan penolakan telah dikirimkan');
    }

    function wd1(Request $request)
    {
        $search = $request->input('search');

        $query = DB::table('srt_izin_plt')
            ->join('prodi', 'srt_izin_plt.prd_id', '=', 'prodi.id')
            ->join('users', 'srt_izin_plt.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('role_surat', 'wd1')
            ->orderBy('tanggal_surat', 'asc')
            ->select(
                'srt_izin_plt.id',
                'srt_izin_plt.tanggal_surat',
                'srt_izin_plt.nama_lmbg',
                'users.nama as nama_mhw',
                'users.nim_nip',
                'prodi.nama_prd',
            );

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%")
                    ->orWhere('nama_lmbg', 'like', "%{$search}%")
                    ->orWhere('users.nim_nip', 'like', "%{$search}%")
                    ->orWhere('prodi.nama_prd', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('srt_izin_plt.wd1', compact('data'));
    }

    function cek_wd1($id)
    {
        $srt_izin_plt = DB::table('srt_izin_plt')
            ->join('prodi', 'srt_izin_plt.prd_id', '=', 'prodi.id')
            ->join('users', 'srt_izin_plt.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('srt_izin_plt.id', $id)
            ->select(
                'srt_izin_plt.id',
                'users.id as users_id',
                'prodi.id as prd_id',
                'departement.id as dpt_id',
                'users.nama',
                'users.nim_nip',
                'users.nowa',
                'users.almt_asl',
                'users.foto',
                'users.email',
                'departement.nama_dpt',
                'prodi.nama_prd',
                'srt_izin_plt.lampiran',
                'srt_izin_plt.nama_lmbg',
                'srt_izin_plt.jbt_lmbg',
                'srt_izin_plt.kota_lmbg',
                'srt_izin_plt.almt_lmbg',
                'srt_izin_plt.role_surat',
            )
            ->first();
        return view('srt_izin_plt.cek_wd1', compact('srt_izin_plt'));
    }

    function setuju_wd1(Request $request, $id)
    {
        $srt_izin_plt = srt_izin_penelitian::where('id', $id)->first();

        $srt_izin_plt->role_surat = 'mahasiswa';

        $srt_izin_plt->save();
        return redirect()->route('srt_izin_plt.wd1')->with('success', 'Surat berhasil disetujui');
    }

    function tolak_wd1(Request $request, $id)
    {
        $srt_izin_plt = srt_izin_penelitian::where('id', $id)->first();

        $request->validate([
            'catatan_surat' => 'required',
        ], [
            'catatan_surat.required' => 'Alasan penolakan wajib diisi',
        ]);

        $srt_izin_plt->catatan_surat = $request->catatan_surat . '- Wakil Dekan 1';
        $srt_izin_plt->catatan_surat = $request->catatan_surat;
        $srt_izin_plt->role_surat = 'tolak';

        $srt_izin_plt->save();
        return redirect()->route('srt_izin_plt.wd1')->with('success', 'Alasan penolakan telah dikirimkan');
    }
}
