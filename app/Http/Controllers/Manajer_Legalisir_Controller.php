<?php

namespace App\Http\Controllers;

use App\Models\legalisir;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// class Manajer_Legalisir_Controller extends Controller
// {
//     function kirim_ijazah(Request $request)
//     {
//         $search = $request->input('search');

//         $query = DB::table('legalisir')
//             ->join('prodi', 'legalisir.prd_id', '=', 'prodi.id')
//             ->join('users', 'legalisir.users_id', '=', 'users.id')
//             ->where('role_surat', 'manajer')
//             ->where('ambil', 'dikirim')
//             ->where('jenis_lgl', 'ijazah')
//             ->orderBy('tanggal_surat', 'asc')
//             ->select(
//                 'legalisir.id',
//                 'users.nim_nip',
//                 'legalisir.nama_mhw',
//                 'legalisir.keperluan',
//                 'prodi.nama_prd',
//             );

//         if ($search) {
//             $query->where(function ($q) use ($search) {
//                 $q->where('nama_mhw', 'like', "%{$search}%")
//                     ->orWhere('keperluan', 'LIKE', "%{$search}%")
//                     ->orWhere('users.nim_nip', 'LIKE', "%{$search}%")
//                     ->orWhere('prodi.nama_prd', 'like', "%{$search}%");
//             });
//         }

//         $data = $query->get();

//         return view('legalisir_manajer.manajer_dikirim_ijazah', compact('data'));
//     }

//     function setuju_kirim_ijazah($id)
//     {
//         $legalisir = legalisir::where('id', $id)->first();

//         $legalisir->role_surat = 'manajer_sukses';

//         $legalisir->save();
//         return redirect()->route('legalisir_manajer.manajer_dikirim_ijazah')
//         ->with('success', 'Legalisir berhasil disetujui dan dilanjutkan
//         ke admin untuk disetujui oleh Wakil dekan');
//     }

//     function kirim_transkrip(Request $request)
//     {
//         $search = $request->input('search');

//         $query = DB::table('legalisir')
//             ->join('prodi', 'legalisir.prd_id', '=', 'prodi.id')
//             ->join('users', 'legalisir.users_id', '=', 'users.id')
//             ->where('role_surat', 'manajer')
//             ->where('ambil', 'dikirim')
//             ->where('jenis_lgl', 'transkrip')
//             ->orderBy('tanggal_surat', 'asc')
//             ->select(
//                 'legalisir.id',
//                 'users.nim_nip',
//                 'legalisir.nama_mhw',
//                 'legalisir.keperluan',
//                 'prodi.nama_prd'
//             );

//         if ($search) {
//             $query->where(function ($q) use ($search) {
//                 $q->where('nama_mhw', 'like', "%{$search}%")
//                     ->orWhere('keperluan', 'LIKE', "%{$search}%")
//                     ->orWhere('users.nim_nip', 'LIKE', "%{$search}%")
//                     ->orWhere('prodi.nama_prd', 'like', "%{$search}%");
//             });
//         }

//         $data = $query->get();

//         return view('legalisir_manajer.manajer_dikirim_transkrip', compact('data'));
//     }

//     function setuju_kirim_transkrip($id)
//     {
//         $legalisir = legalisir::where('id', $id)->first();

//         $legalisir->role_surat = 'manajer_sukses';

//         $legalisir->save();
//         return redirect()->route('legalisir_manajer.manajer_dikirim_transkrip')
//         ->with('success', 'Legalisir berhasil disetujui dan dilanjutkan
//         ke admin untuk disetujui oleh Wakil dekan');
//     }

//     function kirim_ijz_trs(Request $request)
//     {
//         $search = $request->input('search');

//         $query = DB::table('legalisir')
//             ->join('prodi', 'legalisir.prd_id', '=', 'prodi.id')
//             ->join('users', 'legalisir.users_id', '=', 'users.id')
//             ->where('role_surat', 'manajer')
//             ->where('ambil', 'dikirim')
//             ->where('jenis_lgl', 'ijazah_transkrip')
//             ->orderBy('tanggal_surat', 'asc')
//             ->select(
//                 'legalisir.id',
//                 'users.nim_nip',
//                 'legalisir.nama_mhw',
//                 'legalisir.keperluan',
//                 'prodi.nama_prd',
//             );

//         if ($search) {
//             $query->where(function ($q) use ($search) {
//                 $q->where('nama_mhw', 'like', "%{$search}%")
//                     ->orWhere('keperluan', 'LIKE', "%{$search}%")
//                     ->orWhere('users.nim_nip', 'LIKE', "%{$search}%")
//                     ->orWhere('prodi.nama_prd', 'like', "%{$search}%");
//             });
//         }

//         $data = $query->get();

//         return view('legalisir_manajer.manajer_dikirim_ijz_trs', compact('data'));
//     }

//     function setuju_kirim_ijz_trs($id)
//     {
//         $legalisir = legalisir::where('id', $id)->first();

//         $legalisir->role_surat = 'manajer_sukses';

//         $legalisir->save();
//         return redirect()->route('legalisir_manajer.manajer_dikirim_ijz_trs')
//         ->with('success', 'Legalisir berhasil disetujui dan dilanjutkan
//         ke admin untuk disetujui oleh Wakil dekan');
//     }

//     function ditempat_ijazah(Request $request)
//     {
//         $search = $request->input('search');

//         $query = DB::table('legalisir')
//             ->join('prodi', 'legalisir.prd_id', '=', 'prodi.id')
//             ->join('users', 'legalisir.users_id', '=', 'users.id')
//             ->where('role_surat', 'manajer')
//             ->where('ambil', 'ditempat')
//             ->where('jenis_lgl', 'ijazah')
//             ->orderBy('tanggal_surat', 'asc')
//             ->select(
//                 'legalisir.id',
//                 'users.nim_nip',
//                 'legalisir.nama_mhw',
//                 'legalisir.keperluan',
//                 'prodi.nama_prd',
//             );

//         if ($search) {
//             $query->where(function ($q) use ($search) {
//                 $q->where('nama_mhw', 'like', "%{$search}%")
//                     ->orWhere('keperluan', 'LIKE', "%{$search}%")
//                     ->orWhere('users.nim_nip', 'LIKE', "%{$search}%")
//                     ->orWhere('prodi.nama_prd', 'like', "%{$search}%");
//             });
//         }

//         $data = $query->get();

//         return view('legalisir_manajer.manajer_ditempat_ijazah', compact('data'));
//     }

//     function setuju_ditempat_ijazah($id)
//     {
//         $legalisir = legalisir::where('id', $id)->first();

//         $legalisir->role_surat = 'manajer_sukses';

//         $legalisir->save();
//         return redirect()->route('legalisir_manajer.manajer_ditempat_ijazah')
//         ->with('success', 'Legalisir berhasil disetujui dan dilanjutkan
//         ke admin untuk disetujui oleh Wakil dekan');
//     }

//     function ditempat_transkrip(Request $request)
//     {
//         $search = $request->input('search');

//         $query = DB::table('legalisir')
//             ->join('prodi', 'legalisir.prd_id', '=', 'prodi.id')
//             ->join('users', 'legalisir.users_id', '=', 'users.id')
//             ->where('role_surat', 'manajer')
//             ->where('ambil', 'ditempat')
//             ->where('jenis_lgl', 'transkrip')
//             ->orderBy('tanggal_surat', 'asc')
//             ->select(
//                 'legalisir.id',
//                 'users.nim_nip',
//                 'legalisir.nama_mhw',
//                 'legalisir.keperluan',
//                 'prodi.nama_prd',
//             );

//         if ($search) {
//             $query->where(function ($q) use ($search) {
//                 $q->where('nama_mhw', 'like', "%{$search}%")
//                     ->orWhere('keperluan', 'LIKE', "%{$search}%")
//                     ->orWhere('users.nim_nip', 'LIKE', "%{$search}%")
//                     ->orWhere('prodi.nama_prd', 'like', "%{$search}%");
//             });
//         }

//         $data = $query->get();

//         return view('legalisir_manajer.manajer_ditempat_transkrip', compact('data'));
//     }

//     function setuju_ditempat_transkrip($id)
//     {
//         $legalisir = legalisir::where('id', $id)->first();

//         $legalisir->role_surat = 'manajer_sukses';

//         $legalisir->save();
//         return redirect()->route('legalisir_manajer.manajer_ditempat_transkrip')
//         ->with('success', 'Legalisir berhasil disetujui dan dilanjutkan
//         ke admin untuk disetujui oleh Wakil dekan');
//     }

//     function ditempat_ijz_trs(Request $request)
//     {
//         $search = $request->input('search');

//         $query = DB::table('legalisir')
//             ->join('prodi', 'legalisir.prd_id', '=', 'prodi.id')
//             ->join('users', 'legalisir.users_id', '=', 'users.id')
//             ->where('role_surat', 'manajer')
//             ->where('ambil', 'ditempat')
//             ->where('jenis_lgl', 'ijazah_transkrip')
//             ->orderBy('tanggal_surat', 'asc')
//             ->select(
//                 'legalisir.id',
//                 'users.nim_nip',
//                 'legalisir.nama_mhw',
//                 'legalisir.keperluan',
//                 'prodi.nama_prd',
//             );

//         if ($search) {
//             $query->where(function ($q) use ($search) {
//                 $q->where('nama_mhw', 'like', "%{$search}%")
//                     ->orWhere('keperluan', 'LIKE', "%{$search}%")
//                     ->orWhere('users.nim_nip', 'LIKE', "%{$search}%")
//                     ->orWhere('prodi.nama_prd', 'like', "%{$search}%");
//             });
//         }

//         $data = $query->get();

//         return view('legalisir_manajer.manajer_ditempat_ijz_trs', compact('data'));
//     }

//     function setuju_ditempat_ijz_trs($id)
//     {
//         $legalisir = legalisir::where('id', $id)->first();

//         $legalisir->role_surat = 'manajer_sukses';

//         $legalisir->save();
//         return redirect()->route('legalisir_manajer.manajer_ditempat_ijz_trs')
//         ->with('success', 'Legalisir berhasil disetujui dan dilanjutkan
//         ke admin untuk disetujui oleh Wakil dekan');
//     }
// }
