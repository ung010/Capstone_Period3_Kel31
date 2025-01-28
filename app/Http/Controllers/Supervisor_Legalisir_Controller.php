<?php

namespace App\Http\Controllers;

use App\Models\legalisir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Supervisor_Legalisir_Controller extends Controller
{
    function kirim_ijazah(Request $request)
    {
        $search = $request->input('search');

        $query = DB::table('legalisir')
            ->join('prodi', 'legalisir.prd_id', '=', 'prodi.id')
            ->join('users', 'legalisir.users_id', '=', 'users.id')
            ->where('role_surat', 'supervisor_akd')
            ->where('ambil', 'dikirim')
            ->where('jenis_lgl', 'ijazah')
            ->orderBy('tanggal_surat', 'asc')
            ->select(

                'legalisir.id',
                'users.nama as nama_mhw',
                'legalisir.keperluan',
                'prodi.nama_prd',
                'users.nim_nip'
            );

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%")
                    ->orWhere('keperluan', 'LIKE', "%{$search}%")
                    ->orWhere('users.nim_nip', 'LIKE', "%{$search}%")
                    ->orWhere('prodi.nama_prd', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('legalisir_sv.sv_dikirim_ijazah', compact('data'));
    }

    function cek_sv_dikirim_ijazah($id)
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
        return view('legalisir_sv.cek_sv_dikirim_ijazah', compact('legalisir'));
    }

    function setuju_sv_dikirim_ijazah(Request $request, $id)
    {
        $legalisir = legalisir::where('id', $id)->first();

        $legalisir->role_surat = 'dekan';

        $legalisir->save();
        return redirect()->route('legalisir_sv.sv_dikirim_ijazah')
        ->with('success', 'Legalisir berhasil disetujui');

    }

    function tolak_sv_dikirim_ijazah(Request $request, $id)
    {
        $legalisir = legalisir::where('id', $id)->first();

        $request->validate([
            'catatan_surat' => 'required',
        ], [
            'catatan_surat.required' => 'Alasan penolakan wajib diisi',
        ]);

        $legalisir->catatan_surat = $request->catatan_surat . ' - Supervisor Akademik';
        $legalisir->catatan_surat = $request->catatan_surat;
        $legalisir->role_surat = 'tolak';

        $legalisir->save();
        return redirect()->route('legalisir_sv.sv_dikirim_ijazah')->with('success', 'Alasan penolakan telah dikirimkan');
    }

    function kirim_transkrip(Request $request)
    {
        $search = $request->input('search');

        $query = DB::table('legalisir')
            ->join('prodi', 'legalisir.prd_id', '=', 'prodi.id')
            ->join('users', 'legalisir.users_id', '=', 'users.id')
            ->where('role_surat', 'supervisor_akd')
            ->where('ambil', 'dikirim')
            ->where('jenis_lgl', 'transkrip')
            ->orderBy('tanggal_surat', 'asc')
            ->select(
                'users.nim_nip',
                'legalisir.id',
                'users.nama as nama_mhw',
                'legalisir.keperluan',
                'prodi.nama_prd'
            );

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%")
                    ->orWhere('keperluan', 'LIKE', "%{$search}%")
                    ->orWhere('users.nim_nip', 'LIKE', "%{$search}%")
                    ->orWhere('prodi.nama_prd', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('legalisir_sv.sv_dikirim_transkrip', compact('data'));
    }

    function cek_sv_dikirim_transkrip($id)
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
        return view('legalisir_sv.cek_sv_dikirim_transkrip', compact('legalisir'));
    }

    function setuju_sv_dikirim_transkrip($id)
    {
        $legalisir = legalisir::where('id', $id)->first();

        $legalisir->role_surat = 'dekan';

        $legalisir->save();
        return redirect()->route('legalisir_sv.sv_dikirim_transkrip')->with('success', 'Legalisir berhasil disetujui');
    }

    function tolak_sv_dikirim_transkrip(Request $request, $id)
    {
        $legalisir = legalisir::where('id', $id)->first();

        $request->validate([
            'catatan_surat' => 'required',
        ], [
            'catatan_surat.required' => 'Alasan penolakan wajib diisi',
        ]);

        $legalisir->catatan_surat = $request->catatan_surat . ' - Supervisor Akademik';
        $legalisir->catatan_surat = $request->catatan_surat;
        $legalisir->role_surat = 'tolak';

        $legalisir->save();
        return redirect()->route('legalisir_sv.sv_dikirim_transkrip')->with('success', 'Alasan penolakan telah dikirimkan');
    }

    function kirim_ijz_trs(Request $request)
    {
        $search = $request->input('search');

        $query = DB::table('legalisir')
            ->join('prodi', 'legalisir.prd_id', '=', 'prodi.id')
            ->join('users', 'legalisir.users_id', '=', 'users.id')
            ->where('role_surat', 'supervisor_akd')
            ->where('ambil', 'dikirim')
            ->where('jenis_lgl', 'ijazah_transkrip')
            ->orderBy('tanggal_surat', 'asc')
            ->select(
                'users.nim_nip',
                'legalisir.id',
                'users.nama as nama_mhw',
                'legalisir.keperluan',
                'prodi.nama_prd'
            );

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%")
                    ->orWhere('keperluan', 'LIKE', "%{$search}%")
                    ->orWhere('users.nim_nip', 'LIKE', "%{$search}%")
                    ->orWhere('prodi.nama_prd', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('legalisir_sv.sv_dikirim_ijz_trs', compact('data'));
    }

    function cek_sv_dikirim_ijz_trs($id)
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
        return view('legalisir_sv.cek_sv_dikirim_ijz_trs', compact('legalisir'));
    }

    function setuju_sv_dikirim_ijz_trs($id)
    {
        $legalisir = legalisir::where('id', $id)->first();

        $legalisir->role_surat = 'dekan';

        $legalisir->save();
        return redirect()->route('legalisir_sv.sv_dikirim_ijz_trs')->with('success', 'Legalisir berhasil disetujui');
    }

    function tolak_sv_dikirim_ijz_trs(Request $request, $id)
    {
        $legalisir = legalisir::where('id', $id)->first();

        $request->validate([
            'catatan_surat' => 'required',
        ], [
            'catatan_surat.required' => 'Alasan penolakan wajib diisi',
        ]);

        $legalisir->catatan_surat = $request->catatan_surat . ' - Supervisor Akademik';
        $legalisir->catatan_surat = $request->catatan_surat;
        $legalisir->role_surat = 'tolak';

        $legalisir->save();
        return redirect()->route('legalisir_sv.sv_dikirim_ijz_trs')->with('success', 'Alasan penolakan telah dikirimkan');
    }

    function ditempat_ijazah(Request $request)
    {
        $search = $request->input('search');

        $query = DB::table('legalisir')
            ->join('prodi', 'legalisir.prd_id', '=', 'prodi.id')
            ->join('users', 'legalisir.users_id', '=', 'users.id')
            ->where('role_surat', 'supervisor_akd')
            ->where('ambil', 'ditempat')
            ->where('jenis_lgl', 'ijazah')
            ->orderBy('tanggal_surat', 'asc')
            ->select(
                'users.nim_nip',
                'legalisir.id',
                'users.nama as nama_mhw',
                'legalisir.keperluan',
                'prodi.nama_prd'
            );

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%")
                    ->orWhere('keperluan', 'LIKE', "%{$search}%")
                    ->orWhere('users.nim_nip', 'LIKE', "%{$search}%")
                    ->orWhere('prodi.nama_prd', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('legalisir_sv.sv_ditempat_ijazah', compact('data'));
    }

    function cek_sv_ditempat_ijazah($id)
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
        return view('legalisir_sv.cek_sv_ditempat_ijazah', compact('legalisir'));
    }

    function setuju_sv_ditempat_ijazah($id)
    {
        $legalisir = legalisir::where('id', $id)->first();

        $legalisir->role_surat = 'dekan';

        $legalisir->save();
        return redirect()->route('legalisir_sv.sv_ditempat_ijazah')->with('success', 'Legalisir berhasil disetujui');
    }

    function tolak_sv_ditempat_ijazah(Request $request, $id)
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
        return redirect()->route('legalisir_sv.sv_ditempat_ijazah')->with('success', 'Alasan penolakan telah dikirimkan');
    }

    function ditempat_transkrip(Request $request)
    {
        $search = $request->input('search');

        $query = DB::table('legalisir')
            ->join('prodi', 'legalisir.prd_id', '=', 'prodi.id')
            ->join('users', 'legalisir.users_id', '=', 'users.id')
            ->where('role_surat', 'supervisor_akd')
            ->where('ambil', 'ditempat')
            ->where('jenis_lgl', 'transkrip')
            ->orderBy('tanggal_surat', 'asc')
            ->select(
                'users.nim_nip',
                'legalisir.id',
                'users.nama as nama_mhw',
                'legalisir.keperluan',
                'prodi.nama_prd'
            );

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%")
                    ->orWhere('keperluan', 'LIKE', "%{$search}%")
                    ->orWhere('users.nim_nip', 'LIKE', "%{$search}%")
                    ->orWhere('prodi.nama_prd', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('legalisir_sv.sv_ditempat_transkrip', compact('data'));
    }

    function cek_sv_ditempat_transkrip($id)
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
        return view('legalisir_sv.cek_sv_ditempat_transkrip', compact('legalisir'));
    }

    function setuju_sv_ditempat_transkrip($id)
    {
        $legalisir = legalisir::where('id', $id)->first();

        $legalisir->role_surat = 'dekan';

        $legalisir->save();
        return redirect()->route('legalisir_sv.sv_ditempat_transkrip')->with('success', 'Legalisir berhasil disetujui');
    }

    function tolak_sv_ditempat_transkrip(Request $request, $id)
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
        return redirect()->route('legalisir_sv.sv_ditempat_transkrip')->with('success', 'Alasan penolakan telah dikirimkan');
    }

    function ditempat_ijz_trs(Request $request)
    {
        $search = $request->input('search');

        $query = DB::table('legalisir')
            ->join('prodi', 'legalisir.prd_id', '=', 'prodi.id')
            ->join('users', 'legalisir.users_id', '=', 'users.id')
            ->where('role_surat', 'supervisor_akd')
            ->where('ambil', 'ditempat')
            ->where('jenis_lgl', 'ijazah_transkrip')
            ->orderBy('tanggal_surat', 'asc')
            ->select(
                'users.nim_nip',
                'legalisir.id',
                'users.nama as nama_mhw',
                'legalisir.keperluan',
                'prodi.nama_prd',
            );

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%")
                    ->orWhere('keperluan', 'LIKE', "%{$search}%")
                    ->orWhere('users.nim_nip', 'LIKE', "%{$search}%")
                    ->orWhere('prodi.nama_prd', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('legalisir_sv.sv_ditempat_ijz_trs', compact('data'));
    }

    function cek_sv_ditempat_ijz_trs($id)
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
        return view('legalisir_sv.cek_sv_ditempat_ijz_trs', compact('legalisir'));
    }

    function setuju_sv_ditempat_ijz_trs($id)
    {
        $legalisir = legalisir::where('id', $id)->first();

        $legalisir->role_surat = 'dekan';

        $legalisir->save();
        return redirect()->route('legalisir_sv.sv_ditempat_ijz_trs')->with('success', 'Legalisir berhasil disetujui');
    }

    function tolak_sv_ditempat_ijz_trs(Request $request, $id)
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
        return redirect()->route('legalisir_sv.sv_ditempat_ijz_trs')->with('success', 'Alasan penolakan telah dikirimkan');
    }
}
