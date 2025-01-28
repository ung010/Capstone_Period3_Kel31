<?php

namespace App\Http\Controllers;

use App\Models\legalisir;
use App\Models\srt_bbs_pnjm;
use App\Models\srt_izin_penelitian;
use App\Models\Srt_Magang;
use App\Models\srt_masih_mhw;
use App\Models\srt_mhw_asn;
use App\Models\srt_pmhn_kmbali_biaya;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class SupervisorController extends Controller
{
  public function index_akd()
  {
    $srt_mhw_asn = srt_mhw_asn::where('role_surat', 'supervisor_akd')->count();
    $srt_masih_mhw = srt_masih_mhw::where('role_surat', 'supervisor_akd')->count();
    $srt_izin_plt = srt_izin_penelitian::where('role_surat', 'supervisor_akd')->count();
    $srt_magang = Srt_Magang::where('role_surat', 'supervisor_akd')->count();
    $legalisir = legalisir::where('role_surat', 'supervisor_akd')->count();
    $total_surat =
      srt_mhw_asn::where('role_surat', 'mahasiswa')->count() +
      srt_masih_mhw::where('role_surat', 'mahasiswa')->count() +
      srt_izin_penelitian::where('role_surat', 'mahasiswa')->count() +
      Srt_Magang::where('role_surat', 'mahasiswa')->count() +
      legalisir::where('role_surat', 'mahasiswa')->count();


    return view('supervisor_akd.index', compact(
      'srt_mhw_asn',
      'srt_masih_mhw',
      'srt_izin_plt',
      'srt_magang',
      'legalisir',
      'total_surat'
    ));
  }

  public function manage_admin_akd()
  {
    $data = DB::table('users')
      ->where('role', 'admin')
      ->select(
        'id',
        'nama',
        'email',
        'nim_nip'
      )
      ->get();

    return view('supervisor_akd.manage_admin', compact('data'));
  }

  function delete_admin_akd($id)
  {
    User::where('id', $id)->delete();
    return redirect()->back()->with('success', 'Data admin berhasil dihapuskan');
  }

  public function create_akd(Request $request)
  {
    $request->validate([
      'nama' => 'required',
      'nim_nip' => 'required|unique:users',
      'email' => 'required|email|unique:users',
      'password' => 'required|min:8',
    ], [
      'nama.required' => 'Nama wajib diisi',
      'nim_nip.required' => 'NIM wajib diisi',
      'nim_nip.unique' => 'NIM sudah digunakan, silakan masukkan NIM yang lain',
      'email.required' => 'Email wajib diisi',
      'email.email' => 'Email harus valid',
      'email.unique' => 'Email sudah digunakan, silakan masukkan Email yang lain',
      'password.required' => 'Password wajib diisi',
      'password.min' => 'Password minimal 8 karakter',
    ]);

    $data = [
      'nama' => $request->nama,
      'nim_nip' => $request->nim_nip,
      'email' => $request->email,
      'password' => Hash::make($request->password),
      'role' => 'admin',
    ];
    User::create($data);

    return redirect()->back()->with('success', 'Admin berhasil ditambahkan');
  }

  public function edit_akd(Request $request, $id)
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
    return redirect()->back()->with('success', 'Data admin telah diperbarui');
  }


  public function index_sd()
  {
    $srt_bbs_pnjm = srt_bbs_pnjm::where('role_surat', 'supervisor_sd')->count();
    $srt_pmhn_kmbali_biaya = srt_pmhn_kmbali_biaya::where('role_surat', 'supervisor_sd')->count();

    $total_surat =
      srt_bbs_pnjm::where('role_surat', 'mahasiswa')->count() +
      srt_pmhn_kmbali_biaya::where('role_surat', 'mahasiswa')->count();

    return view('supervisor_sd.index', compact(
      'srt_bbs_pnjm',
      'srt_pmhn_kmbali_biaya',
      'total_surat'
    ));
  }

  public function manage_admin_sd()
  {
    $data = DB::table('users')
      ->where('role', 'admin')
      ->select(
        'id',
        'nama',
        'email',
        'nim_nip'
      )
      ->get();

    return view('supervisor_sd.manage_admin', compact('data'));
  }

  function delete_admin_sd($id)
  {
    User::where('id', $id)->delete();
    return redirect()->back()->with('success', 'Data admin berhasil dihapuskan');
  }

  public function create_sd(Request $request)
  {
    $request->validate([
      'nama' => 'required',
      'nim_nip' => 'required|unique:users',
      'email' => 'required|email|unique:users',
      'password' => 'required|min:8',
    ], [
      'nama.required' => 'Nama wajib diisi',
      'nim_nip.required' => 'NIM wajib diisi',
      'nim_nip.unique' => 'NIM sudah digunakan, silakan masukkan NIM yang lain',
      'email.required' => 'Email wajib diisi',
      'email.email' => 'Email harus valid',
      'email.unique' => 'Email sudah digunakan, silakan masukkan Email yang lain',
      'password.required' => 'Password wajib diisi',
      'password.min' => 'Password minimal 8 karakter',
    ]);

    $data = [
      'nama' => $request->nama,
      'nim_nip' => $request->nim_nip,
      'email' => $request->email,
      'password' => Hash::make($request->password),
      'role' => 'admin',
    ];
    User::create($data);

    return redirect()->back()->with('success', 'Admin berhasil ditambahkan');
  }

  public function edit_sd(Request $request, $id)
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
    return redirect()->back()->with('success', 'Data admin telah diperbarui');
  }
}
