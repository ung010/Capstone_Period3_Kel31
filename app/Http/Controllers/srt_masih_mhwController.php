<?php

namespace App\Http\Controllers;

use App\Models\departemen;
use App\Models\prodi;
use App\Models\srt_masih_mhw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Mpdf\Mpdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Hashids;

class srt_masih_mhwController extends Controller
{
    private function deletesurat()
    {
        DB::table('srt_magang')
            ->where('created_at', '<', Carbon::now('Asia/Jakarta')->subMonths(6))
            ->delete();
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        $this->deletesurat();

        $search = $request->input('search');

        $query = DB::table('srt_masih_mhw')
            ->join('prodi', 'srt_masih_mhw.prd_id', '=', 'prodi.id')
            ->join('users', 'srt_masih_mhw.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('users_id', $user->id)
            ->select(
                'srt_masih_mhw.id',
                'users.id as users_id',
                'prodi.id as prd_id',
                'departement.id as dpt_id',
                'users.nama as nama_mhw',
                'users.nim_nip',
                'departement.nama_dpt',
                'prodi.nama_prd',
                'srt_masih_mhw.thn_awl',
                'srt_masih_mhw.semester',
                'srt_masih_mhw.thn_akh',
                'srt_masih_mhw.almt_smg',
                DB::raw('CONCAT(users.kota, ", ", DATE_FORMAT(users.tanggal_lahir, "%d-%m-%Y")) as ttl'),
                'srt_masih_mhw.tujuan_buat_srt',
                'srt_masih_mhw.role_surat',
                'srt_masih_mhw.tujuan_akhir'
            );

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%")
                    ->orWhere('almt_smg', 'like', "%{$search}%")
                    ->orWhere('semester', 'like', "%{$search}%")
                    ->orWhere('thn_awl', 'like', "%{$search}%")
                    ->orWhere('thn_akh', 'like', "%{$search}%")
                    ->orWhere('tujuan_buat_srt', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        $prodi = prodi::where('id', $user->prd_id)->first();
        $departemen = departemen::where('id', $prodi->dpt_id)->first();

        $kota = $user->kota;
        $tanggal_lahir = $user->tanggal_lahir;
        $kota_tanggal_lahir = ($kota && $tanggal_lahir) ? $kota . ', ' . \Carbon\Carbon::parse($tanggal_lahir)->format('d F Y') : 'N/A';

        return view('srt_masih_mhw.index', compact('data', 'user', 'prodi', 'departemen', 'kota_tanggal_lahir'));
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'thn_awl' => 'required',
            'thn_akh' => 'required',
            'almt_smg' => 'required',
            'semester' => 'required',
            'tujuan_buat_srt' => 'required',
            'tujuan_akhir' => 'required',
        ], [
            'thn_awl.required' => 'Tahun pertama wajib diisi',
            'thn_akh.required' => 'Tahun kedua wajib diisi',
            'semester.required' => 'Semester wajib diisi',
            'almt_smg.required' => 'Alamat kos / tempat tinggal semarang wajib diisi',
            'tujuan_buat_srt.required' => 'Tujuan pembuatan surat wajib diisi',
            'tujuan_akhir.required' => 'Wajib memilih yang mendatangani surat',
        ]);

        $user = Auth::user();

        $existingSurat = DB::table('srt_masih_mhw')
            ->where('users_id', $user->id)
            ->get();

        foreach ($existingSurat as $surat) {
            if ($surat->tujuan_akhir === 'wd' && $request->tujuan_akhir === 'wd' && $surat->role_surat !== 'mahasiswa') {
                return redirect()->back()->withErrors(['error' => 'Surat dengan tujuan akhir Wakil Dekan sudah ada dan belum selesai.']);
            }

            if ($surat->tujuan_akhir === 'manajer' && $request->tujuan_akhir === 'manajer' && $surat->role_surat !== 'mahasiswa') {
                return redirect()->back()->withErrors(['error' => 'Surat dengan tujuan akhir Manajer sudah ada dan belum selesai.']);
            }
        }

        $id_surat = mt_rand(1000000000000, 9999999999999);

        DB::table('srt_masih_mhw')->insert([
            'id' => $id_surat,
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'thn_awl' => $request->thn_awl,
            'semester' => $request->semester,
            'thn_akh' => $request->thn_akh,
            'almt_smg' => $request->almt_smg,
            'tujuan_buat_srt' => $request->tujuan_buat_srt,
            'tujuan_akhir' => $request->tujuan_akhir,
            'tanggal_surat' => Carbon::now('Asia/Jakarta')->format('Y-m-d'),
            'created_at' => Carbon::now('Asia/Jakarta'),
        ]);

        return redirect()->route('srt_masih_mhw.index')->with('success', 'Surat berhasil dibuat');
    }

    public function edit($id)
    {
        $user = Auth::user();

        $decodedId = Hashids::decode($id);

        $data = DB::table('srt_masih_mhw')->where('id', $decodedId[0])->first();

        if (!$data) {
            return redirect()->route('srt_masih_mhw.index')->withErrors('Data tidak ditemukan.');
        }

        $prodi = prodi::where('id', $user->prd_id)->first();
        $departemen = departemen::where('id', $prodi->dpt_id)->first();

        $kota = $user->kota;
        $tanggal_lahir = $user->tanggal_lahir;
        $kota_tanggal_lahir = ($kota && $tanggal_lahir) ? $kota . ', ' . \Carbon\Carbon::parse($tanggal_lahir)->format('d F Y') : 'N/A';

        return view('srt_masih_mhw.edit', compact('data', 'user', 'prodi', 'departemen', 'kota_tanggal_lahir'));
    }


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'thn_awl' => 'required',
            'thn_akh' => 'required',
            'almt_smg' => 'required',
            'semester' => 'required',
            'tujuan_buat_srt' => 'required',
        ], [
            'thn_awl.required' => 'Tahun pertama wajib diisi',
            'thn_akh.required' => 'Tahun kedua wajib diisi',
            'semester.required' => 'Semester wajib diisi',
            'almt_smg.required' => 'Alamat kos / tempat tinggal semarang wajib diisi',
            'tujuan_buat_srt.required' => 'Tujuan pembuatan surat wajib diisi',
        ]);

        DB::table('srt_masih_mhw')->where('id', $id)->update([
            'thn_awl' => $request->thn_awl,
            'thn_akh' => $request->thn_akh,
            'semester' => $request->semester,
            'almt_smg' => $request->almt_smg,
            'tujuan_buat_srt' => $request->tujuan_buat_srt,
            'role_surat' => 'admin',
            'catatan_surat' => '-',
        ]);

        return redirect()->route('srt_masih_mhw.index')->with('success', 'Surat berhasil diperbarui');
    }

    function download_wd($id)
    {
        $srt_masih_mhw = DB::table('srt_masih_mhw')
            ->join('prodi', 'srt_masih_mhw.prd_id', '=', 'prodi.id')
            ->join('users', 'srt_masih_mhw.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('srt_masih_mhw.id', $id)
            ->select(
                'srt_masih_mhw.id',
                'users.id as users_id',
                'prodi.id as prd_id',
                'departement.id as dpt_id',
                'users.nama as nama_mhw',
                'srt_masih_mhw.no_surat',
                'users.nim_nip',
                'departement.nama_dpt',
                'prodi.nama_prd',
                'srt_masih_mhw.thn_awl',
                'srt_masih_mhw.thn_akh',
                'srt_masih_mhw.almt_smg',
                'users.nowa',
                DB::raw('CONCAT(users.kota, ", ", DATE_FORMAT(users.tanggal_lahir, "%d-%m-%Y")) as ttl'),
                'srt_masih_mhw.tujuan_buat_srt',
                'srt_masih_mhw.role_surat',
                'srt_masih_mhw.tujuan_akhir',
                'srt_masih_mhw.tanggal_surat'
            )
            ->first();

        if (!$srt_masih_mhw) {
            return redirect()->back()->with('error', 'Data not found');
        }

        if ($srt_masih_mhw->tanggal_surat) {
            $srt_masih_mhw->tanggal_surat = Carbon::parse($srt_masih_mhw->tanggal_surat)->format('d-m-Y');
        }

        $qrUrl = url('/legal/srt_masih_mhw/' . $srt_masih_mhw->id);
        $qrCodePath = 'storage/qrcodes/qr-' . $srt_masih_mhw->id . '.png';
        $qrCodeFullPath = public_path($qrCodePath);

        if (!File::exists(dirname($qrCodeFullPath))) {
            File::makeDirectory(dirname($qrCodeFullPath), 0755, true);
        }

        QrCode::format('png')->size(100)->generate($qrUrl, $qrCodeFullPath);

        // $mpdf = new Mpdf();
        // $html = View::make('srt_masih_mhw.view_wd', compact('srt_masih_mhw', 'qrCodePath'))->render();
        // $mpdf->WriteHTML($html);
        $pdf = Pdf::loadView('srt_masih_mhw.view_wd', compact('srt_masih_mhw', 'qrCodePath'));

        $namaMahasiswa = $srt_masih_mhw->nama_mhw;
        $tanggalSurat = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $fileName = 'Surat_Masih_Mahasiswa_WD_' . str_replace(' ', '_', $namaMahasiswa) . '_' . $tanggalSurat . '.pdf';
        // $mpdf->Output($fileName, 'D');
        return $pdf->download($fileName);
    }

    function download_manajer($id)
    {
        $mpdf = new \Mpdf\Mpdf();

        $srt_masih_mhw = DB::table('srt_masih_mhw')
            ->join('prodi', 'srt_masih_mhw.prd_id', '=', 'prodi.id')
            ->join('users', 'srt_masih_mhw.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('srt_masih_mhw.id', $id)
            ->select(
                'srt_masih_mhw.id',
                'users.id as users_id',
                'prodi.id as prodi_id',
                'departement.id as departement_id',
                'users.nama as nama_mhw',
                'srt_masih_mhw.no_surat',
                'users.nim_nip',
                'departement.nama_dpt',
                'prodi.nama_prd',
                'srt_masih_mhw.thn_awl',
                'srt_masih_mhw.thn_akh',
                'srt_masih_mhw.almt_smg',
                DB::raw('CONCAT(users.kota, ", ", DATE_FORMAT(users.tanggal_lahir, "%d-%m-%Y")) as ttl'),
                'srt_masih_mhw.tujuan_buat_srt',
                'srt_masih_mhw.role_surat',
                'srt_masih_mhw.tujuan_akhir',
                'srt_masih_mhw.tanggal_surat',
                'users.nowa'
            )
            ->first();

        if ($srt_masih_mhw && $srt_masih_mhw->tanggal_surat) {
            $srt_masih_mhw->tanggal_surat = Carbon::parse($srt_masih_mhw->tanggal_surat)->format('d-m-Y');
        }

        $qrUrl = url('/legal/srt_masih_mhw/' . $srt_masih_mhw->id);
        $qrCodePath = 'storage/qrcodes/qr-' . $srt_masih_mhw->id . '.png';
        $qrCodeFullPath = public_path($qrCodePath);

        if (!File::exists(dirname($qrCodeFullPath))) {
            File::makeDirectory(dirname($qrCodeFullPath), 0755, true);
        }

        QrCode::format('png')->size(100)->generate($qrUrl, $qrCodeFullPath);

        $pdf = Pdf::loadView('srt_masih_mhw.view_manajer', compact('srt_masih_mhw', 'qrCodePath'));

        $namaMahasiswa = $srt_masih_mhw->nama_mhw;
        $tanggalSurat = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        $fileName = 'Surat_Masih_Mahasiswa_Manajer_' . str_replace(' ', '_', $namaMahasiswa) . '_' . $tanggalSurat . '.pdf';
        return $pdf->download($fileName);
    }

    function admin(Request $request)
    {
        $search = $request->input('search');

        $query = DB::table('srt_masih_mhw')
            ->join('users', 'srt_masih_mhw.users_id', '=', 'users.id')
            ->select(
                'srt_masih_mhw.id',
                'srt_masih_mhw.tujuan_akhir',
                'users.nama as nama_mhw',
            )
            ->where('srt_masih_mhw.role_surat', 'admin')
            // ->where('srt_masih_mhw.tujuan_akhir', 'manajer')
            ->orderBy('srt_masih_mhw.tanggal_surat', 'asc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('srt_masih_mhw.admin', compact('data'));
    }

    // function wd(Request $request)
    // {
    //     $search = $request->input('search');

    //     $query = DB::table('srt_masih_mhw')
    //         ->join('users', 'srt_masih_mhw.users_id', '=', 'users.id')
    //         ->select(
    //             'srt_masih_mhw.id',
    //             'users.nama as nama_mhw',
    //             'srt_masih_mhw.role_surat',
    //             'srt_masih_mhw.tujuan_akhir'
    //         )
    //         ->where('srt_masih_mhw.role_surat', 'admin')
    //         ->orderBy('srt_masih_mhw.tanggal_surat', 'asc')
    //         ->where('srt_masih_mhw.tujuan_akhir', 'wd');

    //     if ($search) {
    //         $query->where(function ($q) use ($search) {
    //             $q->where('users.nama', 'like', "%{$search}%");
    //         });
    //     }

    //     $data = $query->get();

    //     return view('srt_masih_mhw.wd', compact('data'));
    // }

    //   function wd_unduh($id)
//   {
//     $srt_masih_mhw = DB::table('srt_masih_mhw')
//       ->join('prodi', 'srt_masih_mhw.prd_id', '=', 'prodi.id')
//       ->join('users', 'srt_masih_mhw.users_id', '=', 'users.id')
//       ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
//       ->where('srt_masih_mhw.id', $id)
//       ->select(
//         'srt_masih_mhw.id',
//         'users.id as users_id',
//         'prodi.id as prd_id',
//         'departement.id as dpt_id',
//         'users.nama',
//         'srt_masih_mhw.nama_mhw',
//         'srt_masih_mhw.no_surat',
//         'users.nim_nip',
//         'departement.nama_dpt',
//         'prodi.nama_prd',
//         'srt_masih_mhw.thn_awl',
//         'srt_masih_mhw.thn_akh',
//         'srt_masih_mhw.almt_smg',
//         'users.nowa',
//         DB::raw('CONCAT(users.kota, ", ", DATE_FORMAT(users.tanggal_lahir, "%d-%m-%Y")) as ttl'),
//         'srt_masih_mhw.tujuan_buat_srt',
//         'srt_masih_mhw.role_surat',
//         'srt_masih_mhw.tujuan_akhir',
//         'srt_masih_mhw.tanggal_surat'
//       )
//       ->first();

    //     if (!$srt_masih_mhw) {
//       return redirect()->back()->with('error', 'Data not found');
//     }

    //     if ($srt_masih_mhw->tanggal_surat) {
//       $srt_masih_mhw->tanggal_surat = Carbon::parse($srt_masih_mhw->tanggal_surat)->format('d-m-Y');
//     }

    //     $qrUrl = url('/legal/srt_masih_mhw/' . $srt_masih_mhw->id);
//     $qrCodePath = 'storage/qrcodes/qr-' . $srt_masih_mhw->id . '.png';
//     $qrCodeFullPath = public_path($qrCodePath);

    //     if (!File::exists(dirname($qrCodeFullPath))) {
//       File::makeDirectory(dirname($qrCodeFullPath), 0755, true);
//     }

    //     QrCode::format('png')->size(100)->generate($qrUrl, $qrCodeFullPath);

    //     // $mpdf = new Mpdf();
//     // $html = View::make('srt_masih_mhw.view_wd', compact('srt_masih_mhw', 'qrCodePath'))->render();
//     // $mpdf->WriteHTML($html);
//     $pdf = Pdf::loadView('srt_masih_mhw.view_wd', compact('srt_masih_mhw', 'qrCodePath'));

    //     $namaMahasiswa = $srt_masih_mhw->nama;
//     $tanggalSurat = Carbon::now('Asia/Jakarta')->format('Y-m-d');
//     $fileName = 'Surat_Masih_Mahasiswa_' . str_replace(' ', '_', $namaMahasiswa) . '_' . $tanggalSurat . '.pdf';
//     // $mpdf->Output($fileName, 'D');
//     return $pdf->download($fileName);
//   }

    //   public function wd_unggah(Request $request, $id)
//   {
//     $request->validate([
//       'srt_masih_mhw' => 'required|mimes:pdf'
//     ], [
//       'srt_masih_mhw.required' => 'Surat wajib diisi',
//       'srt_masih_mhw.mimes' => 'Surat wajib berbentuk / berekstensi PDF',
//     ]);

    //     $srt_masih_mhw = DB::table('srt_masih_mhw')
//       ->join('users', 'srt_masih_mhw.users_id', '=', 'users.id')
//       ->where('srt_masih_mhw.id', $id)
//       ->select(
//         'srt_masih_mhw.id',
//         'users.nama',
//         'srt_masih_mhw.tanggal_surat'
//       )
//       ->first();

    //     if (!$srt_masih_mhw) {
//       return redirect()->back()->withErrors('Data surat tidak ditemukan.');
//     }

    //     $tanggal_surat = Carbon::parse($srt_masih_mhw->tanggal_surat)->format('d-m-Y');
//     $nama_mahasiswa = Str::slug($srt_masih_mhw->nama);

    //     $file = $request->file('srt_masih_mhw');
//     $surat_extensi = $file->extension();
//     $nama_surat = "Surat_Masih_Mahasiswa_{$tanggal_surat}_{$nama_mahasiswa}." . $surat_extensi;
//     $file->move(public_path('storage/pdf/srt_masih_mahasiswa/wd'), $nama_surat);

    //     srt_masih_mhw::where('id', $id)->update([
//       'file_pdf' => $nama_surat,
//       'role_surat' => 'mahasiswa',
//     ]);

    //     return redirect()->back()->with('success', 'Berhasil menggunggah pdf ke mahasiswa');
//   }

    // function wd_cek($id)
    // {
    //     $srt_masih_mhw = DB::table('srt_masih_mhw')
    //         ->join('prodi', 'srt_masih_mhw.prd_id', '=', 'prodi.id')
    //         ->join('users', 'srt_masih_mhw.users_id', '=', 'users.id')
    //         ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
    //         ->where('srt_masih_mhw.id', $id)
    //         ->select(
    //             'srt_masih_mhw.id',
    //             'users.id as users_id',
    //             'prodi.id as prodi_id',
    //             'departement.id as departement_id',
    //             'users.nama',
    //             'users.nim_nip',
    //             'users.almt_asl',
    //             DB::raw('CONCAT(users.kota, ", ", DATE_FORMAT(users.tanggal_lahir, "%d-%m-%Y")) as ttl'),
    //             'departement.nama_dpt',
    //             'prodi.nama_prd',
    //             'users.nowa',
    //             'users.foto',
    //             'srt_masih_mhw.tujuan_buat_srt',
    //             'srt_masih_mhw.tujuan_akhir'
    //         )
    //         ->first();
    //     return view('srt_masih_mhw.wd_cek', compact('srt_masih_mhw'));
    // }

    // function wd_setuju(Request $request, $id)
    // {
    //     $srt_masih_mhw = srt_masih_mhw::where('id', $id)->first();

    //     $request->validate([
    //         'no_surat' => 'required',
    //     ], [
    //         'no_surat.required' => 'No surat wajib diisi',
    //     ]);

    //     $srt_masih_mhw->no_surat = $request->no_surat;
    //     $srt_masih_mhw->role_surat = 'supervisor_akd';

    //     $srt_masih_mhw->save();
    //     return redirect()->route('srt_masih_mhw.wd')->with('success', 'No surat berhasil ditambahkan');
    // }

    // function wd_tolak(Request $request, $id)
    // {
    //     $srt_masih_mhw = srt_masih_mhw::where('id', $id)->first();

    //     $request->validate([
    //         'catatan_surat' => 'required',
    //     ], [
    //         'catatan_surat.required' => 'Alasan penolakan wajib diisi',
    //     ]);

    //     $srt_masih_mhw->catatan_surat = $request->catatan_surat;
    //     $srt_masih_mhw->role_surat = 'tolak';

    //     $srt_masih_mhw->save();
    //     return redirect()->route('srt_masih_mhw.wd')->with('success', 'Alasan penolakan telah dikirimkan');
    // }

    function cek_surat_admin($id)
    {
        $srt_masih_mhw = DB::table('srt_masih_mhw')
            ->join('prodi', 'srt_masih_mhw.prd_id', '=', 'prodi.id')
            ->join('users', 'srt_masih_mhw.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('srt_masih_mhw.id', $id)
            ->select(
                'srt_masih_mhw.id',
                'users.id as users_id',
                'prodi.id as prd_id',
                'departement.id as dpt_id',
                'users.nama',
                'users.nim_nip',
                'users.almt_asl',
                DB::raw('CONCAT(users.kota, ", ", DATE_FORMAT(users.tanggal_lahir, "%d-%m-%Y")) as ttl'),
                'departement.nama_dpt',
                'prodi.nama_prd',
                'users.nowa',
                'users.foto',
                'srt_masih_mhw.tujuan_buat_srt',
                'srt_masih_mhw.tujuan_akhir'
            )
            ->first();
        return view('srt_masih_mhw.cek_data', compact('srt_masih_mhw'));
    }

    function setuju(Request $request, $id)
    {
        $srt_masih_mhw = srt_masih_mhw::where('id', $id)->first();

        $request->validate([
            'no_surat' => 'required',
        ], [
            'no_surat.required' => 'No surat wajib diisi',
        ]);

        $srt_masih_mhw->no_surat = $request->no_surat;
        $srt_masih_mhw->role_surat = 'supervisor_akd';

        $srt_masih_mhw->save();
        return redirect()->route('srt_masih_mhw.admin')->with('success', 'No surat berhasil ditambahkan');
    }

    function tolak(Request $request, $id)
    {
        $srt_masih_mhw = srt_masih_mhw::where('id', $id)->first();

        $request->validate([
            'catatan_surat' => 'required',
        ], [
            'catatan_surat.required' => 'Alasan penolakan wajib diisi',
        ]);

        $srt_masih_mhw->catatan_surat = $request->catatan_surat;
        $srt_masih_mhw->role_surat = 'tolak';

        $srt_masih_mhw->save();
        return redirect()->route('srt_masih_mhw.admin')->with('success', 'Alasan penolakan telah dikirimkan');
    }

    function supervisor(Request $request)
    {
        $search = $request->input('search');

        $query = DB::table('srt_masih_mhw')
            ->join('users', 'srt_masih_mhw.users_id', '=', 'users.id')
            ->select(
                'srt_masih_mhw.id',
                'users.nama as nama_mhw',
                'srt_masih_mhw.tujuan_buat_srt'
            )
            ->where('srt_masih_mhw.role_surat', 'supervisor_akd')
            ->orderBy('srt_masih_mhw.tanggal_surat', 'asc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%")
                    ->orWhere('tujuan_buat_srt', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('srt_masih_mhw.supervisor', compact('data'));
    }

    function cek_sv($id)
    {
        $srt_masih_mhw = DB::table('srt_masih_mhw')
            ->join('prodi', 'srt_masih_mhw.prd_id', '=', 'prodi.id')
            ->join('users', 'srt_masih_mhw.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('srt_masih_mhw.id', $id)
            ->select(
                'srt_masih_mhw.id',
                'users.id as users_id',
                'prodi.id as prd_id',
                'departement.id as dpt_id',
                'users.nama',
                'users.nim_nip',
                'users.almt_asl',
                DB::raw('CONCAT(users.kota, ", ", DATE_FORMAT(users.tanggal_lahir, "%d-%m-%Y")) as ttl'),
                'departement.nama_dpt',
                'prodi.nama_prd',
                'users.nowa',
                'users.foto',
                'srt_masih_mhw.tujuan_buat_srt',
                'srt_masih_mhw.tujuan_akhir'
            )
            ->first();
        return view('srt_masih_mhw.cek_sv', compact('srt_masih_mhw'));
    }

    function setuju_sv(Request $request, $id)
    {
        $srt_masih_mhw = srt_masih_mhw::where('id', $id)->first();

        $srt_masih_mhw->role_surat = 'manajer';

        $srt_masih_mhw->save();
        return redirect()->route('srt_masih_mhw.supervisor')->with('success', 'Surat berhasil disetujui');
    }

    function tolak_sv(Request $request, $id)
    {
        $srt_masih_mhw = srt_masih_mhw::where('id', $id)->first();

        $request->validate([
            'catatan_surat' => 'required',
        ], [
            'catatan_surat.required' => 'Alasan penolakan wajib diisi',
        ]);

        $srt_masih_mhw->catatan_surat = $request->catatan_surat . '- Supervisor Akademik';
        $srt_masih_mhw->catatan_surat = $request->catatan_surat;
        $srt_masih_mhw->role_surat = 'tolak';

        $srt_masih_mhw->save();
        return redirect()->route('srt_masih_mhw.supervisor')->with('success', 'Alasan penolakan telah dikirimkan');
    }

    function manajer(Request $request)
    {
        $search = $request->input('search');

        $query = DB::table('srt_masih_mhw')
            ->join('users', 'srt_masih_mhw.users_id', '=', 'users.id')
            ->select(
                'srt_masih_mhw.id',
                'users.nama as nama_mhw',
                'srt_masih_mhw.tujuan_buat_srt',
                'srt_masih_mhw.tujuan_akhir'
            )
            ->where('role_surat', 'manajer')
            ->orderBy('tanggal_surat', 'asc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%")
                    ->orWhere('tujuan_buat_srt', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('srt_masih_mhw.manajer', compact('data'));
    }

    function cek_manajer($id)
    {
        $srt_masih_mhw = DB::table('srt_masih_mhw')
            ->join('prodi', 'srt_masih_mhw.prd_id', '=', 'prodi.id')
            ->join('users', 'srt_masih_mhw.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('srt_masih_mhw.id', $id)
            ->select(
                'srt_masih_mhw.id',
                'users.id as users_id',
                'prodi.id as prd_id',
                'departement.id as dpt_id',
                'users.nama',
                'users.nim_nip',
                'users.almt_asl',
                DB::raw('CONCAT(users.kota, ", ", DATE_FORMAT(users.tanggal_lahir, "%d-%m-%Y")) as ttl'),
                'departement.nama_dpt',
                'prodi.nama_prd',
                'users.nowa',
                'users.foto',
                'srt_masih_mhw.tujuan_buat_srt',
                'srt_masih_mhw.tujuan_akhir'
            )
            ->first();
        return view('srt_masih_mhw.cek_manajer', compact('srt_masih_mhw'));
    }

    function setuju_manajer(Request $request, $id)
    {
        $srt_masih_mhw = srt_masih_mhw::where('id', $id)->first();

        $srt_masih_mhw->role_surat = 'mahasiswa';

        $srt_masih_mhw->save();
        return redirect()->route('srt_masih_mhw.manajer')->with('success', 'Surat dikirim ke mahasiswa yang bersangkutan');
    }

    function tolak_manajer(Request $request, $id)
    {
        $srt_masih_mhw = srt_masih_mhw::where('id', $id)->first();

        $request->validate([
            'catatan_surat' => 'required',
        ], [
            'catatan_surat.required' => 'Alasan penolakan wajib diisi',
        ]);

        $srt_masih_mhw->catatan_surat = $request->catatan_surat . '- Manajer';
        $srt_masih_mhw->catatan_surat = $request->catatan_surat;
        $srt_masih_mhw->role_surat = 'tolak';

        $srt_masih_mhw->save();
        return redirect()->route('srt_masih_mhw.manajer')->with('success', 'Alasan penolakan telah dikirimkan');
    }

    // function cek_manajer_wd($id)
    // {
    //     $srt_masih_mhw = DB::table('srt_masih_mhw')
    //         ->join('prodi', 'srt_masih_mhw.prd_id', '=', 'prodi.id')
    //         ->join('users', 'srt_masih_mhw.users_id', '=', 'users.id')
    //         ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
    //         ->where('srt_masih_mhw.id', $id)
    //         ->select(
    //             'srt_masih_mhw.id',
    //             'users.id as users_id',
    //             'prodi.id as prd_id',
    //             'departement.id as dpt_id',
    //             'users.nama',
    //             'users.nim_nip',
    //             'users.almt_asl',
    //             DB::raw('CONCAT(users.kota, ", ", DATE_FORMAT(users.tanggal_lahir, "%d-%m-%Y")) as ttl'),
    //             'departement.nama_dpt',
    //             'prodi.nama_prd',
    //             'users.nowa',
    //             'users.foto',
    //             'srt_masih_mhw.tujuan_buat_srt',
    //             'srt_masih_mhw.tujuan_akhir'
    //         )
    //         ->first();
    //     return view('srt_masih_mhw.cek_manajer', compact('srt_masih_mhw'));
    // }

    function setuju_manajer_wd(Request $request, $id)
    {
        $srt_masih_mhw = srt_masih_mhw::where('id', $id)->first();

        $srt_masih_mhw->role_surat = 'wd1';

        $srt_masih_mhw->save();
        return redirect()->route('srt_masih_mhw.manajer')->with('success', 'Surat dilanjutkan ke Wakil Dekan');
    }

    function tolak_manajer_wd(Request $request, $id)
    {
        $srt_masih_mhw = srt_masih_mhw::where('id', $id)->first();

        $request->validate([
            'catatan_surat' => 'required',
        ], [
            'catatan_surat.required' => 'Alasan penolakan wajib diisi',
        ]);

        $srt_masih_mhw->catatan_surat = $request->catatan_surat . '- Manajer';
        $srt_masih_mhw->catatan_surat = $request->catatan_surat;
        $srt_masih_mhw->role_surat = 'tolak';

        $srt_masih_mhw->save();
        return redirect()->route('srt_masih_mhw.manajer')->with('success', 'Alasan penolakan telah dikirimkan');
    }

    function wd1(Request $request)
    {
        $search = $request->input('search');

        $query = DB::table('srt_masih_mhw')
            ->join('users', 'srt_masih_mhw.users_id', '=', 'users.id')
            ->select(
                'srt_masih_mhw.id',
                'users.nama as nama_mhw',
                'srt_masih_mhw.tujuan_buat_srt',
                'srt_masih_mhw.tujuan_akhir'
            )
            ->where('srt_masih_mhw.role_surat', 'wd1')
            ->orderBy('srt_masih_mhw.tanggal_surat', 'asc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%")
                    ->orWhere('tujuan_buat_srt', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('srt_masih_mhw.wd1', compact('data'));
    }

    function cek_wd1($id)
    {
        $srt_masih_mhw = DB::table('srt_masih_mhw')
            ->join('prodi', 'srt_masih_mhw.prd_id', '=', 'prodi.id')
            ->join('users', 'srt_masih_mhw.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('srt_masih_mhw.id', $id)
            ->select(
                'srt_masih_mhw.id',
                'users.id as users_id',
                'prodi.id as prd_id',
                'departement.id as dpt_id',
                'users.nama',
                'users.nim_nip',
                'users.almt_asl',
                DB::raw('CONCAT(users.kota, ", ", DATE_FORMAT(users.tanggal_lahir, "%d-%m-%Y")) as ttl'),
                'departement.nama_dpt',
                'prodi.nama_prd',
                'users.nowa',
                'users.foto',
                'srt_masih_mhw.tujuan_buat_srt',
                'srt_masih_mhw.tujuan_akhir'
            )
            ->first();
        return view('srt_masih_mhw.cek_wd1', compact('srt_masih_mhw'));
    }

    function setuju_wd1(Request $request, $id)
    {
        $srt_masih_mhw = srt_masih_mhw::where('id', $id)->first();

        $srt_masih_mhw->role_surat = 'mahasiswa';

        $srt_masih_mhw->save();
        return redirect()->route('srt_masih_mhw.wd1')->with('success', 'Surat dikirim ke mahasiswa yang bersangkutan');
    }

    function tolak_wd1(Request $request, $id)
    {
        $srt_masih_mhw = srt_masih_mhw::where('id', $id)->first();

        $request->validate([
            'catatan_surat' => 'required',
        ], [
            'catatan_surat.required' => 'Alasan penolakan wajib diisi',
        ]);

        $srt_masih_mhw->catatan_surat = $request->catatan_surat . '- Wakil Dekan 1';
        $srt_masih_mhw->catatan_surat = $request->catatan_surat;
        $srt_masih_mhw->role_surat = 'tolak';

        $srt_masih_mhw->save();
        return redirect()->route('srt_masih_mhw.wd1')->with('success', 'Alasan penolakan telah dikirimkan');
    }
}
