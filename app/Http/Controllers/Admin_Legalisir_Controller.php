<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\legalisir;
use ZipStream\ZipStream;
use Illuminate\Support\Facades\Response;

class Admin_Legalisir_Controller extends Controller
{
    function kirim_ijazah(Request $request)
    {
        $search = $request->input('search');

        $query = DB::table('legalisir')
            ->join('users', 'legalisir.users_id', '=', 'users.id')
            ->select(
                'legalisir.id',
                'users.nama as nama_mhw',
                'legalisir.role_surat',
            )
            ->whereIn('legalisir.role_surat', ['admin', 'supervisor_akd', 'dekan'])
            ->orderByRaw("FIELD(legalisir.role_surat, 'dekan', 'admin', 'supervisor_akd')")
            ->orderBy('legalisir.tanggal_surat', 'asc')
            ->where('legalisir.ambil', 'dikirim')
            ->where('legalisir.jenis_lgl', 'ijazah');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%")
                    ->orWhere('legalisir.role_surat', 'LIKE', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('legalisir_admin.admin_dikirim_ijazah', compact('data'));
    }

    public function unduh_kirim_ijazah($id)
    {
        $legalisir = DB::table('legalisir')
            ->join('users', 'legalisir.users_id', '=', 'users.id')
            ->select(
                'legalisir.id',
                'users.nama as nama_mhw',
                'users.nim_nip',
                'legalisir.file_ijazah'
            )
            ->where('legalisir.id', $id)
            ->where('legalisir.jenis_lgl', 'ijazah')
            ->where('legalisir.ambil', 'dikirim')
            ->first();

        if (! $legalisir) {
            return redirect()->back()->withErrors('Data tidak ditemukan di database.');
        }

        if (! $legalisir->file_ijazah) {
            return redirect()->back()->withErrors('File belum diunggah ke sistem.');
        }

        $filePath = public_path('storage/pdf/legalisir/ijazah/'.$legalisir->file_ijazah);

        if (! file_exists($filePath)) {
            return redirect()->back()->withErrors('File tidak ditemukan di storage.');
        }

        $downloadFileName = $legalisir->nama_mhw.'_'.$legalisir->nim_nip.'_Dikirim_Ijazah.pdf';

        return response()->download($filePath, $downloadFileName);
    }

    function cek_kirim_ijazah($id)
    {
        $legalisir = legalisir::findOrFail($id);
        $legalisir = DB::table('legalisir')
            ->join('prodi', 'legalisir.prd_id', '=', 'prodi.id')
            ->join('users', 'legalisir.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('legalisir.id', $id)
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
            )
            ->first();
        return view('legalisir_admin.cek_dikirim_ijazah', compact('legalisir'));
    }

    function setuju_kirim_ijazah($id)
    {
        $legalisir = legalisir::where('id', $id)->first();

        $legalisir->role_surat = 'supervisor_akd';

        $legalisir->save();
        return redirect()->route('legalisir_admin.admin_dikirim_ijazah')->with('success', 'Legalisir berhasil disetujui dan dilanjutkan ke supervisor akademik');
    }

    function tolak_kirim_ijazah(Request $request, $id)
    {
        $legalisir = legalisir::where('id', $id)->first();

        $request->validate([
            'catatan_surat' => 'required',
        ], [
            'catatan_surat.required' => 'Alasan penolakan wajib diisi',
        ]);

        $legalisir->catatan_surat = $request->catatan_surat;
        $legalisir->role_surat = 'tolak';

        $legalisir->save();
        return redirect()->route('legalisir_admin.admin_dikirim_ijazah')->with('success', 'Alasan penolakan telah dikirimkan');
    }

    public function resi_kirim_ijazah(Request $request, $id)
    {
        $legalisir = legalisir::findOrFail($id);

        $isDiambilDitempat = $request->has('diambil_ditempat');

        if (! $isDiambilDitempat) {
            $request->validate([
                'no_resi' => 'required',
            ], [
                'no_resi.required' => 'No resi wajib diisi',
            ]);
        }

        $legalisir->no_resi = $isDiambilDitempat ? 'Diambil Ditempat' : $request->no_resi;
        $legalisir->role_surat = 'mahasiswa';

        $legalisir->save();

        return redirect()->route('legalisir_admin.admin_dikirim_ijazah')->with('success', 'No resi telah dikirimkan');
    }

    function kirim_transkrip(Request $request)
    {
        $search = $request->input('search');

        $query = DB::table('legalisir')
            ->join('users', 'legalisir.users_id', '=', 'users.id')
            ->select(
                'legalisir.id',
                'users.nama as nama_mhw',
                'legalisir.role_surat',
            )
            ->whereIn('legalisir.role_surat', ['admin', 'supervisor_akd', 'dekan'])
            ->orderByRaw("FIELD(legalisir.role_surat, 'dekan', 'admin', 'supervisor_akd')")
            ->orderBy('legalisir.tanggal_surat', 'asc')
            ->where('legalisir.ambil', 'dikirim')
            ->where('legalisir.jenis_lgl', 'transkrip');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%")
                    ->orWhere('legalisir.role_surat', 'LIKE', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('legalisir_admin.admin_dikirim_transkrip', compact('data'));
    }

    public function unduh_kirim_transkrip($id)
    {
        $legalisir = DB::table('legalisir')
            ->join('users', 'legalisir.users_id', '=', 'users.id')
            ->select(
                'legalisir.id',
                'users.nama as nama_mhw',
                'users.nim_nip',
                'legalisir.file_transkrip'
            )
            ->where('legalisir.id', $id)
            ->where('legalisir.jenis_lgl', 'transkrip')
            ->where('legalisir.ambil', 'dikirim')
            ->first();

        if (! $legalisir) {
            return redirect()->back()->withErrors('Data tidak ditemukan di database.');
        }

        if (! $legalisir->file_transkrip) {
            return redirect()->back()->withErrors('File belum diunggah ke sistem.');
        }

        $filePath = public_path('storage/pdf/legalisir/transkrip/'.$legalisir->file_transkrip);

        if (! file_exists($filePath)) {
            return redirect()->back()->withErrors('File tidak ditemukan di storage.');
        }

        $downloadFileName = $legalisir->nama_mhw.'_'.$legalisir->nim_nip.'_Dikirim_Transkrip.pdf';

        return response()->download($filePath, $downloadFileName);
    }

    function cek_kirim_transkrip($id)
    {
        $legalisir = DB::table('legalisir')
            ->join('prodi', 'legalisir.prd_id', '=', 'prodi.id')
            ->join('users', 'legalisir.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('legalisir.id', $id)
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
            )
            ->first();
        return view('legalisir_admin.cek_dikirim_transkrip', compact('legalisir'));
    }

    function setuju_kirim_transkrip($id)
    {
        $legalisir = legalisir::where('id', $id)->first();

        $legalisir->role_surat = 'supervisor_akd';

        $legalisir->save();
        return redirect()->route('legalisir_admin.admin_dikirim_transkrip')->with('success', 'Legalisir berhasil disetujui dan dilanjutkan ke supervisor akademik');
    }

    function tolak_kirim_transkrip(Request $request, $id)
    {
        $legalisir = legalisir::where('id', $id)->first();

        $request->validate([
            'catatan_surat' => 'required',
        ], [
            'catatan_surat.required' => 'Alasan penolakan wajib diisi',
        ]);

        $legalisir->catatan_surat = $request->catatan_surat;
        $legalisir->role_surat = 'tolak';

        $legalisir->save();
        return redirect()->route('legalisir_admin.admin_dikirim_transkrip')->with('success', 'Alasan penolakan telah dikirimkan');
    }

    public function resi_kirim_transkrip(Request $request, $id)
    {
        $legalisir = legalisir::findOrFail($id);

        $isDiambilDitempat = $request->has('diambil_ditempat');

        if (! $isDiambilDitempat) {
            $request->validate([
                'no_resi' => 'required',
            ], [
                'no_resi.required' => 'No resi wajib diisi',
            ]);
        }

        $legalisir->no_resi = $isDiambilDitempat ? 'Diambil Ditempat' : $request->no_resi;
        $legalisir->role_surat = 'mahasiswa';

        $legalisir->save();

        return redirect()->route('legalisir_admin.admin_dikirim_transkrip')->with('success', 'No resi telah dikirimkan');
    }

    function kirim_ijz_trs(Request $request)
    {
        $search = $request->input('search');

        $query = DB::table('legalisir')
            ->join('users', 'legalisir.users_id', '=', 'users.id')
            ->select(
                'legalisir.id',
                'users.nama as nama_mhw',
                'legalisir.role_surat',
            )
            ->whereIn('legalisir.role_surat', ['admin', 'supervisor_akd', 'dekan'])
            ->orderByRaw("FIELD(legalisir.role_surat, 'dekan', 'admin', 'supervisor_akd')")
            ->orderBy('legalisir.tanggal_surat', 'asc')
            ->where('legalisir.ambil', 'dikirim')
            ->where('legalisir.jenis_lgl', 'ijazah_transkrip');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%")
                    ->orWhere('legalisir.role_surat', 'LIKE', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('legalisir_admin.admin_dikirim_ijz_trs', compact('data'));
    }

    public function unduh_kirim_ijz_trs($id)
    {
        $legalisir = DB::table('legalisir')
            ->join('users', 'legalisir.users_id', '=', 'users.id')
            ->select(
                'legalisir.id',
                'users.nama as nama_mhw',
                'users.nim_nip',
                'legalisir.file_ijazah',
                'legalisir.file_transkrip'
            )
            ->where('legalisir.id', $id)
            ->where('legalisir.jenis_lgl', 'ijazah_transkrip')
            ->where('legalisir.ambil', 'dikirim')
            ->first();

        if (! $legalisir) {
            return redirect()->back()->withErrors('Data tidak ditemukan di database.');
        }

        if (! $legalisir->file_ijazah || ! $legalisir->file_transkrip) {
            return redirect()->back()->withErrors('File belum diunggah ke sistem.');
        }

        $fileIjazahPath = public_path('storage/pdf/legalisir/ijazah/'.$legalisir->file_ijazah);
        $fileTranskripPath = public_path('storage/pdf/legalisir/transkrip/'.$legalisir->file_transkrip);

        if (! file_exists($fileIjazahPath) || ! file_exists($fileTranskripPath)) {
            return redirect()->back()->withErrors('File tidak ditemukan di storage.');
        }

        $zipFileName = $legalisir->nama_mhw.'_'.$legalisir->nim_nip.'_Dikirim.zip';
        $zipPath = storage_path('app/'.$zipFileName);

        if (file_exists($zipPath)) {
            unlink($zipPath);
        }

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE) !== TRUE) {
            return redirect()->back()->withErrors('Gagal membuat file ZIP.');
        }

        if (! $zip->addFile($fileIjazahPath, $legalisir->nama_mhw.'_'.$legalisir->nim_nip.'_Dikirim_Ijazah.pdf')) {
            $zip->close();
            unlink($zipPath);
            return redirect()->back()->withErrors('Gagal menambahkan file ijazah ke ZIP.');
        }

        if (! $zip->addFile($fileTranskripPath, $legalisir->nama_mhw.'_'.$legalisir->nim_nip.'_Dikirim_Transkrip.pdf')) {
            $zip->close();
            unlink($zipPath);
            return redirect()->back()->withErrors('Gagal menambahkan file transkrip ke ZIP.');
        }

        if (! $zip->close()) {
            unlink($zipPath);
            return redirect()->back()->withErrors('Gagal menyimpan file ZIP.');
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    function cek_kirim_ijz_trs($id)
    {
        $legalisir = DB::table('legalisir')
            ->join('prodi', 'legalisir.prd_id', '=', 'prodi.id')
            ->join('users', 'legalisir.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('legalisir.id', $id)
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
            )
            ->first();
        return view('legalisir_admin.cek_dikirim_ijz_trs', compact('legalisir'));
    }

    function setuju_kirim_ijz_trs($id)
    {
        $legalisir = legalisir::where('id', $id)->first();

        $legalisir->role_surat = 'supervisor_akd';

        $legalisir->save();
        return redirect()->route('legalisir_admin.admin_dikirim_ijz_trs')->with('success', 'Legalisir berhasil disetujui dan dilanjutkan ke supervisor akademik');
    }

    function tolak_kirim_ijz_trs(Request $request, $id)
    {
        $legalisir = legalisir::where('id', $id)->first();

        $request->validate([
            'catatan_surat' => 'required',
        ], [
            'catatan_surat.required' => 'Alasan penolakan wajib diisi',
        ]);

        $legalisir->catatan_surat = $request->catatan_surat;
        $legalisir->role_surat = 'tolak';

        $legalisir->save();
        return redirect()->route('legalisir_admin.admin_dikirim_ijz_trs')->with('success', 'Alasan penolakan telah dikirimkan');
    }

    public function resi_kirim_ijz_trs(Request $request, $id)
    {
        $legalisir = legalisir::findOrFail($id);

        $isDiambilDitempat = $request->has('diambil_ditempat');

        if (! $isDiambilDitempat) {
            $request->validate([
                'no_resi' => 'required',
            ], [
                'no_resi.required' => 'No resi wajib diisi',
            ]);
        }

        $legalisir->no_resi = $isDiambilDitempat ? 'Diambil Ditempat' : $request->no_resi;
        $legalisir->role_surat = 'mahasiswa';

        $legalisir->save();

        return redirect()->route('legalisir_admin.admin_dikirim_ijz_trs')->with('success', 'No resi telah dikirimkan');
    }

    function ditempat_ijazah(Request $request)
    {
        $search = $request->input('search');

        $query = DB::table('legalisir')
            ->join('users', 'legalisir.users_id', '=', 'users.id')
            ->select(
                'legalisir.id',
                'users.nama as nama_mhw',
                'legalisir.role_surat',
            )
            ->whereIn('legalisir.role_surat', ['admin', 'supervisor_akd', 'dekan'])
            ->orderByRaw("FIELD(legalisir.role_surat, 'dekan', 'admin', 'supervisor_akd')")
            ->orderBy('legalisir.tanggal_surat', 'asc')
            ->where('legalisir.ambil', 'ditempat')
            ->where('legalisir.jenis_lgl', 'ijazah');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%")
                    ->orWhere('legalisir.role_surat', 'LIKE', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('legalisir_admin.admin_ditempat_ijazah', compact('data'));
    }

    public function unduh_ditempat_ijazah($id)
    {
        $legalisir = DB::table('legalisir')
            ->join('users', 'legalisir.users_id', '=', 'users.id')
            ->select(
                'legalisir.id',
                'users.nama as nama_mhw',
                'users.nim_nip',
                'legalisir.file_ijazah'
            )
            ->where('legalisir.id', $id)
            ->where('legalisir.jenis_lgl', 'ijazah')
            ->where('legalisir.ambil', 'ditempat')
            ->first();

        if (! $legalisir) {
            return redirect()->back()->withErrors('Data tidak ditemukan di database.');
        }

        if (! $legalisir->file_ijazah) {
            return redirect()->back()->withErrors('File belum diunggah ke sistem.');
        }

        $filePath = public_path('storage/pdf/legalisir/ijazah/'.$legalisir->file_ijazah);

        if (! file_exists($filePath)) {
            return redirect()->back()->withErrors('File tidak ditemukan di storage.');
        }

        $downloadFileName = $legalisir->nama_mhw.'_'.$legalisir->nim_nip.'_Ditempat_Ijazah.pdf';

        return response()->download($filePath, $downloadFileName);
    }

    function cek_ditempat_ijazah($id)
    {
        $legalisir = DB::table('legalisir')
            ->join('prodi', 'legalisir.prd_id', '=', 'prodi.id')
            ->join('users', 'legalisir.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('legalisir.id', $id)
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
            )
            ->first();
        return view('legalisir_admin.cek_ditempat_ijazah', compact('legalisir'));
    }

    function setuju_ditempat_ijazah($id)
    {
        $legalisir = legalisir::where('id', $id)->first();

        $legalisir->role_surat = 'supervisor_akd';

        $legalisir->save();
        return redirect()->route('legalisir_admin.admin_ditempat_ijazah')->with('success', 'Legalisir berhasil disetujui dan dilanjutkan ke supervisor akademik');
    }

    function tolak_ditempat_ijazah(Request $request, $id)
    {
        $legalisir = legalisir::where('id', $id)->first();

        $request->validate([
            'catatan_surat' => 'required',
        ], [
            'catatan_surat.required' => 'Alasan penolakan wajib diisi',
        ]);

        $legalisir->catatan_surat = $request->catatan_surat;
        $legalisir->role_surat = 'tolak';

        $legalisir->save();
        return redirect()->route('legalisir_admin.admin_ditempat_ijazah')->with('success', 'Alasan penolakan telah dikirimkan');
    }

    public function resi_ditempat_ijazah(Request $request, $id)
    {
        $legalisir = legalisir::findOrFail($id);

        $legalisir->no_resi = 'Legalisir dapat diambil';
        $legalisir->role_surat = 'mahasiswa';

        $legalisir->save();

        return redirect()->route('legalisir_admin.admin_ditempat_ijazah')
        ->with('success', 'Informasi mahasiswa dapat mengambil legalisir telah dikirimkan');
    }

    function ditempat_transkrip(Request $request)
    {
        $search = $request->input('search');

        $query = DB::table('legalisir')
            ->join('users', 'legalisir.users_id', '=', 'users.id')
            ->select(
                'legalisir.id',
                'users.nama as nama_mhw',
                'legalisir.role_surat',
            )
            ->whereIn('legalisir.role_surat', ['admin', 'supervisor_akd', 'dekan'])
            ->orderByRaw("FIELD(legalisir.role_surat, 'dekan', 'admin', 'supervisor_akd')")
            ->orderBy('legalisir.tanggal_surat', 'asc')
            ->where('legalisir.ambil', 'ditempat')
            ->where('legalisir.jenis_lgl', 'transkrip');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%")
                    ->orWhere('legalisir.role_surat', 'LIKE', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('legalisir_admin.admin_ditempat_transkrip', compact('data'));
    }

    public function unduh_ditempat_transkrip($id)
    {
        $legalisir = DB::table('legalisir')
            ->join('users', 'legalisir.users_id', '=', 'users.id')
            ->select(
                'legalisir.id',
                'users.nama as nama_mhw',
                'users.nim_nip',
                'legalisir.file_transkrip'
            )
            ->where('legalisir.id', $id)
            ->where('legalisir.jenis_lgl', 'transkrip')
            ->where('legalisir.ambil', 'ditempat')
            ->first();

        if (! $legalisir) {
            return redirect()->back()->withErrors('Data tidak ditemukan di database.');
        }

        if (! $legalisir->file_transkrip) {
            return redirect()->back()->withErrors('File belum diunggah ke sistem.');
        }

        $filePath = public_path('storage/pdf/legalisir/transkrip/'.$legalisir->file_transkrip);

        if (! file_exists($filePath)) {
            return redirect()->back()->withErrors('File tidak ditemukan di storage.');
        }

        $downloadFileName = $legalisir->nama_mhw.'_'.$legalisir->nim_nip.'_Ditempat_Transkrip.pdf';

        return response()->download($filePath, $downloadFileName);
    }

    function cek_ditempat_transkrip($id)
    {
        $legalisir = DB::table('legalisir')
            ->join('prodi', 'legalisir.prd_id', '=', 'prodi.id')
            ->join('users', 'legalisir.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('legalisir.id', $id)
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
            )
            ->first();
        return view('legalisir_admin.cek_ditempat_transkrip', compact('legalisir'));
    }

    function setuju_ditempat_transkrip($id)
    {
        $legalisir = legalisir::where('id', $id)->first();

        $legalisir->role_surat = 'supervisor_akd';

        $legalisir->save();
        return redirect()->route('legalisir_admin.admin_ditempat_transkrip')->with('success', 'Legalisir berhasil disetujui dan dilanjutkan ke supervisor akademik');
    }

    function tolak_ditempat_transkrip(Request $request, $id)
    {
        $legalisir = legalisir::where('id', $id)->first();

        $request->validate([
            'catatan_surat' => 'required',
        ], [
            'catatan_surat.required' => 'Alasan penolakan wajib diisi',
        ]);

        $legalisir->catatan_surat = $request->catatan_surat;
        $legalisir->role_surat = 'tolak';

        $legalisir->save();
        return redirect()->route('legalisir_admin.admin_ditempat_transkrip')->with('success', 'Alasan penolakan telah dikirimkan');
    }

    public function resi_ditempat_transkrip(Request $request, $id)
    {
        $legalisir = legalisir::findOrFail($id);

        $legalisir->no_resi = 'Legalisir dapat diambil';
        $legalisir->role_surat = 'mahasiswa';

        $legalisir->save();

        return redirect()->route('legalisir_admin.admin_ditempat_transkrip')
        ->with('success', 'Informasi mahasiswa dapat mengambil legalisir telah dikirimkan');
    }

    function ditempat_ijz_trs(Request $request)
    {
        $search = $request->input('search');

        $query = DB::table('legalisir')
            ->join('users', 'legalisir.users_id', '=', 'users.id')
            ->select(
                'legalisir.id',
                'users.nama as nama_mhw',
                'legalisir.role_surat',
            )
            ->whereIn('legalisir.role_surat', ['admin', 'supervisor_akd', 'dekan'])
            ->orderByRaw("FIELD(legalisir.role_surat, 'dekan', 'admin', 'supervisor_akd')")
            ->orderBy('legalisir.tanggal_surat', 'asc')
            ->where('legalisir.ambil', 'ditempat')
            ->where('legalisir.jenis_lgl', 'ijazah_transkrip');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%")
                    ->orWhere('legalisir.role_surat', 'LIKE', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('legalisir_admin.admin_ditempat_ijz_trs', compact('data'));
    }

    public function unduh_ditempat_ijz_trs($id)
    {
        $legalisir = DB::table('legalisir')
            ->join('users', 'legalisir.users_id', '=', 'users.id')
            ->select(
                'legalisir.id',
                'users.nama as nama_mhw',
                'users.nim_nip',
                'legalisir.file_ijazah',
                'legalisir.file_transkrip'
            )
            ->where('legalisir.id', $id)
            ->where('legalisir.jenis_lgl', 'ijazah_transkrip')
            ->where('legalisir.ambil', 'ditempat')
            ->first();

        if (! $legalisir) {
            return redirect()->back()->withErrors('Data tidak ditemukan di database.');
        }

        if (! $legalisir->file_ijazah || ! $legalisir->file_transkrip) {
            return redirect()->back()->withErrors('File belum diunggah ke sistem.');
        }

        $fileIjazahPath = public_path('storage/pdf/legalisir/ijazah/'.$legalisir->file_ijazah);
        $fileTranskripPath = public_path('storage/pdf/legalisir/transkrip/'.$legalisir->file_transkrip);

        if (! file_exists($fileIjazahPath) || ! file_exists($fileTranskripPath)) {
            return redirect()->back()->withErrors('File tidak ditemukan di storage.');
        }

        $zipFileName = $legalisir->nama_mhw.'_'.$legalisir->nim_nip.'_Ditempat.zip';
        $zipPath = storage_path('app/'.$zipFileName);

        if (file_exists($zipPath)) {
            unlink($zipPath);
        }

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE) !== TRUE) {
            return redirect()->back()->withErrors('Gagal membuat file ZIP.');
        }

        if (! $zip->addFile($fileIjazahPath, $legalisir->nama_mhw.'_'.$legalisir->nim_nip.'_Ditempat_Ijazah.pdf')) {
            $zip->close();
            unlink($zipPath);
            return redirect()->back()->withErrors('Gagal menambahkan file ijazah ke ZIP.');
        }

        if (! $zip->addFile($fileTranskripPath, $legalisir->nama_mhw.'_'.$legalisir->nim_nip.'_Ditempat_Transkrip.pdf')) {
            $zip->close();
            unlink($zipPath);
            return redirect()->back()->withErrors('Gagal menambahkan file transkrip ke ZIP.');
        }

        if (! $zip->close()) {
            unlink($zipPath);
            return redirect()->back()->withErrors('Gagal menyimpan file ZIP.');
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    function cek_ditempat_ijz_trs($id)
    {
        $legalisir = DB::table('legalisir')
            ->join('prodi', 'legalisir.prd_id', '=', 'prodi.id')
            ->join('users', 'legalisir.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('legalisir.id', $id)
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
            )
            ->first();
        return view('legalisir_admin.cek_ditempat_ijz_trs', compact('legalisir'));
    }

    function setuju_ditempat_ijz_trs($id)
    {
        $legalisir = legalisir::where('id', $id)->first();

        $legalisir->role_surat = 'supervisor_akd';

        $legalisir->save();
        return redirect()->route('legalisir_admin.admin_ditempat_ijz_trs')->with('success', 'Legalisir berhasil disetujui dan dilanjutkan ke supervisor akademik');
    }

    function tolak_ditempat_ijz_trs(Request $request, $id)
    {
        $legalisir = legalisir::where('id', $id)->first();

        $request->validate([
            'catatan_surat' => 'required',
        ], [
            'catatan_surat.required' => 'Alasan penolakan wajib diisi',
        ]);

        $legalisir->catatan_surat = $request->catatan_surat;
        $legalisir->role_surat = 'tolak';

        $legalisir->save();
        return redirect()->route('legalisir_admin.admin_ditempat_ijz_trs')->with('success', 'Alasan penolakan telah dikirimkan');
    }

    public function resi_ditempat_ijz_trs(Request $request, $id)
    {
        $legalisir = legalisir::findOrFail($id);

        $legalisir->no_resi = 'Legalisir dapat diambil';
        $legalisir->role_surat = 'mahasiswa';

        $legalisir->save();

        return redirect()->route('legalisir_admin.admin_ditempat_ijz_trs')
        ->with('success', 'Informasi mahasiswa dapat mengambil legalisir telah dikirimkan');
    }
}
