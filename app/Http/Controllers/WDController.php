<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\srt_izin_penelitian;
use App\Models\Srt_Magang;
use App\Models\srt_masih_mhw;
use App\Models\srt_pmhn_kmbali_biaya;

class WDController extends Controller
{
    public function index_wd1()
  {
    $srt_masih_mhw = srt_masih_mhw::where('role_surat', 'wd1')->count();
    $srt_izin_plt = srt_izin_penelitian::where('role_surat', 'wd1')->count();
    $srt_magang = Srt_Magang::where('role_surat', 'wd1')->count();
    $total_surat =
      srt_masih_mhw::where('role_surat', 'mahasiswa')->count() +
      srt_izin_penelitian::where('role_surat', 'mahasiswa')->count() +
      Srt_Magang::where('role_surat', 'mahasiswa')->count();

    return view('wd1.index', compact(
      'srt_masih_mhw',
      'srt_izin_plt',
      'srt_magang',
      'total_surat'
    ));
  }

  function edit_wd1_account()
  {
    $user = Auth::user();
    return view('wd1.account', compact('user'));
  }

  function update_wd1_account(Request $request, $id)
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

  public function index_wd2()
  {
    $srt_pmhn_kmbali_biaya = srt_pmhn_kmbali_biaya::where('role_surat', 'wd2')->count();
    $total_surat =
      srt_pmhn_kmbali_biaya::where('role_surat', 'mahasiswa')->count();

    return view('wd2.index', compact(
      'srt_pmhn_kmbali_biaya',
      'total_surat'
    ));
  }

  function edit_wd2_account()
  {
    $user = Auth::user();
    return view('wd2.account', compact('user'));
  }

  function update_wd2_account(Request $request, $id)
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
}
