<?php

namespace App\Http\Controllers;

use App\Models\legalisir;
use App\Models\srt_bbs_pnjm;
use App\Models\srt_izin_penelitian;
use App\Models\Srt_Magang;
use App\Models\srt_masih_mhw;
use App\Models\srt_mhw_asn;
use App\Models\srt_pmhn_kmbali_biaya;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ManajerController extends Controller
{
  public function index()
  {
    $srt_mhw_asn = srt_mhw_asn::where('role_surat', 'manajer')->count();
    $srt_masih_mhw = srt_masih_mhw::where('role_surat', 'manajer')->count();
    $srt_izin_plt = srt_izin_penelitian::where('role_surat', 'manajer')->count();
    $srt_magang = Srt_Magang::where('role_surat', 'manajer')->count();
    $srt_pmhn_kmbali_biaya = srt_pmhn_kmbali_biaya::where('role_surat', 'manajer')->count();
    $legalisir = legalisir::where('role_surat', 'manajer')->count();
    $total_surat =
      srt_mhw_asn::where('role_surat', 'mahasiswa')->count() +
      srt_masih_mhw::where('role_surat', 'mahasiswa')->count() +
      srt_izin_penelitian::where('role_surat', 'mahasiswa')->count() +
      Srt_Magang::where('role_surat', 'mahasiswa')->count() +
      srt_pmhn_kmbali_biaya::where('role_surat', 'mahasiswa')->count() +
      legalisir::where('role_surat', 'mahasiswa')->count();

    return view('manajer.index', compact(
      'srt_mhw_asn',
      'srt_masih_mhw',
      'srt_izin_plt',
      'srt_magang',
      'srt_pmhn_kmbali_biaya',
      'legalisir',
      'total_surat'
    ));
  }

  function edit_account()
  {
    $user = Auth::user();
    return view('manajer.account', compact('user'));
  }

  function update_account(Request $request, $id)
  {
    $user = User::findOrFail($id);

    $request->validate([
      'nama' => 'required',
      'nim_nip' => 'required|unique:users,nim_nip,' . $user->id,
      'email' => 'required|email|unique:users,email,' . $user->id,
    ], [
      'nama.required' => 'Nama wajib diisi',
      'nim_nip.required' => 'NIM wajib diisi',
      'nim_nip.unique' => 'NIM sudah digunakan, silakan masukkan NIM yang lain',
      'email.required' => 'Email wajib diisi',
      'email.email' => 'Email harus valid',
      'email.unique' => 'Email sudah digunakan, silakan masukkan Email yang lain',
    ]);

    $user->nama = $request->nama;
    $user->nim_nip = $request->nim_nip;
    $user->email = $request->email;

    if ($request->filled('password')) {
      $user->password = Hash::make($request->password);
    }

    $user->save();
    return redirect()->back()->with('success', 'Data diri telah diperbarui');
  }

  public function manage_spv()
  {
    $data = DB::table('users')
      ->whereIn('users.role', ['supervisor_akd', 'supervisor_sd'])
      ->select(
        'id',
        'nama',
        'email',
        'nim_nip'
      )
      ->get();

    return view('manajer.manage_supervisor', compact('data'));
  }

  public function edit_spv(Request $request, $id)
  {
    // dd($id);
    $user = User::findOrFail($id);

    $request->validate([
      'nama' => 'required',
      'nim_nip' => 'required|unique:users,nim_nip,' . $user->id,
      'email' => 'required|email|unique:users,email,' . $user->id,
    ], [
      'nama.required' => 'Nama wajib diisi',
      'nim_nip.required' => 'NIM wajib diisi',
      'nim_nip.unique' => 'NIM sudah digunakan, silakan masukkan NIM yang lain',
      'email.required' => 'Email wajib diisi',
      'email.email' => 'Email harus valid',
      'email.unique' => 'Email sudah digunakan, silakan masukkan Email yang lain',
    ]);

    $user->nama = $request->nama;
    $user->nim_nip = $request->nim_nip;
    $user->email = $request->email;

    if ($request->filled('password')) {
      $user->password = Hash::make($request->password);
    }

    $user->save();
    return redirect()->back()->with('success', 'Data supervisor telah diperbarui');
  }
}
