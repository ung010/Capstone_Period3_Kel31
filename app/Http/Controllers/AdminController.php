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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
  public function index()
  {
    $srt_mhw_asn = srt_mhw_asn::where('role_surat', 'admin')->count();
    $srt_masih_mhw = srt_masih_mhw::where('role_surat', 'admin')->count();
    $srt_bbs_pnjm = srt_bbs_pnjm::where('role_surat', 'admin')->count();
    $srt_izin_plt = srt_izin_penelitian::where('role_surat', 'admin')->count();
    $srt_magang = Srt_Magang::where('role_surat', 'admin')->count();
    $srt_pmhn_kmbali_biaya = srt_pmhn_kmbali_biaya::where('role_surat', 'admin')->count();
    $legalisir = legalisir::where('role_surat', 'admin')->count();
    $verif_akun = User::where('role', 'non_mahasiswa')->count();
    $total_surat =
      srt_mhw_asn::where('role_surat', 'mahasiswa')->count() +
      srt_masih_mhw::where('role_surat', 'mahasiswa')->count() +
      srt_bbs_pnjm::where('role_surat', 'mahasiswa')->count() +
      srt_izin_penelitian::where('role_surat', 'mahasiswa')->count() +
      Srt_Magang::where('role_surat', 'mahasiswa')->count() +
      srt_pmhn_kmbali_biaya::where('role_surat', 'mahasiswa')->count() +
      legalisir::where('role_surat', 'mahasiswa')->count();

    return view('admin.index', compact(
      'srt_mhw_asn',
      'srt_masih_mhw',
      'srt_bbs_pnjm',
      'srt_izin_plt',
      'srt_magang',
      'srt_pmhn_kmbali_biaya',
      'legalisir',
      'verif_akun',
      'total_surat'
    ));
  }

  function track_srt_sv_akd()
  {
    $srt_mhw_asn = srt_mhw_asn::where('role_surat', 'supervisor_akd')->count();
    $srt_masih_mhw = srt_masih_mhw::where('role_surat', 'supervisor_akd')->count();
    $srt_izin_plt = srt_izin_penelitian::where('role_surat', 'supervisor_akd')->count();
    $srt_magang = Srt_Magang::where('role_surat', 'supervisor_akd')->count();
    $legalisir = legalisir::where('role_surat', 'supervisor_akd')->count();

    return view('track_srt.akd', compact(
      'srt_mhw_asn',
      'srt_masih_mhw',
      'srt_izin_plt',
      'srt_magang',
      'legalisir',
    ));
  }

  function track_srt_sv_sd()
  {
    $srt_bbs_pnjm = srt_bbs_pnjm::where('role_surat', 'supervisor_sd')->count();
    $srt_pmhn_kmbali_biaya = srt_pmhn_kmbali_biaya::where('role_surat', 'supervisor_sd')->count();

    return view('track_srt.sd', compact(
      'srt_bbs_pnjm',
      'srt_pmhn_kmbali_biaya',
    ));
  }

  function track_srt_manajer()
  {
    $srt_mhw_asn = srt_mhw_asn::where('role_surat', 'manajer')->count();
    $srt_masih_mhw = srt_masih_mhw::where('role_surat', 'manajer')->count();
    $srt_izin_plt = srt_izin_penelitian::where('role_surat', 'manajer')->count();
    $srt_magang = Srt_Magang::where('role_surat', 'manajer')->count();
    $srt_pmhn_kmbali_biaya = srt_pmhn_kmbali_biaya::where('role_surat', 'manajer')->count();

    return view('track_srt.manajer', compact(
      'srt_mhw_asn',
      'srt_masih_mhw',
      'srt_izin_plt',
      'srt_magang',
      'srt_pmhn_kmbali_biaya',
    ));
  }

  function track_srt_wd1()
  {
    $srt_masih_mhw = srt_masih_mhw::where('role_surat', 'wd1')->count();
    $srt_izin_plt = srt_izin_penelitian::where('role_surat', 'wd1')->count();
    $srt_magang = Srt_Magang::where('role_surat', 'wd1')->count();

    return view('track_srt.wd1', compact(
      'srt_masih_mhw',
      'srt_izin_plt',
      'srt_magang',
    ));
  }

  function track_srt_wd2()
  {
    $srt_pmhn_kmbali_biaya = srt_pmhn_kmbali_biaya::where('role_surat', 'wd2')->count();

    return view('track_srt.wd2', compact(
      'srt_pmhn_kmbali_biaya',
    ));
  }

  public function user()
  {
    $data = DB::table('users')
      ->join('prodi', 'users.prd_id', '=', 'prodi.id')
      ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
      ->where('users.role', 'mahasiswa')
      ->select(
        'users.id',
        'users.nama',
        'users.nmr_unik',
        'users.status',
        'users.nowa',
        'users.email',
        'prodi.dpt_id',
        'departement.nama_dpt',
        'prodi.nama_prd',
      )
      ->get();

    $dataDeleted = DB::table('users')
      ->join('prodi', 'users.prd_id', '=', 'prodi.id')
      ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
      ->where('users.role', 'del_mahasiswa')
      ->select(
        'users.id',
        'users.nama',
        'users.nmr_unik',
        'users.email',
        'users.nowa',
        'departement.nama_dpt',
        'prodi.nama_prd',
      )
      ->get();

    return view('admin.user')->with('data', $data)->with('dataDeleted', $dataDeleted);
  }

  public function search_user(Request $request)
  {
    $query = $request->input('query');

    $data = DB::table('users')
      ->join('prodi', 'users.prd_id', '=', 'prodi.id')
      ->join('departement', 'users.dpt_id', '=', 'departement.id')
      ->where('users.role', 'mahasiswa')
      ->whereIn('users.status', ['mahasiswa', 'alumni'])
      ->where(function ($q) use ($query) {
        $q->where('users.nama', 'LIKE', "%{$query}%")
          ->orWhere('users.nmr_unik', 'LIKE', "%{$query}%")
          ->orWhere('users.email', 'LIKE', "%{$query}%")
          ->orWhere('prodi.nama_prd', 'LIKE', "%{$query}%")
          ->orWhere('departement.nama_dpt', 'LIKE', "%{$query}%");
      })
      ->select(
        'users.id',
        'users.nama',
        'users.nmr_unik',
        'prodi.nama_prd',
        'departement.nama_dpt',
      )
      ->get();

    return view('admin.user', compact('data'));
  }

  public function verif_user()
  {
    $data = DB::table('users')
      ->join('prodi', 'users.prd_id', '=', 'prodi.id')
      ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
      ->where('users.role', 'non_mahasiswa')
      ->whereIn('users.status', ['mahasiswa', 'alumni'])
      ->select(
        'users.id',
        'users.nama',
        'users.nmr_unik',
        'users.email',
        'prodi.dpt_id',
        'departement.nama_dpt',
        'prodi.nama_prd',
      )
      ->get();

    return view('admin.verifikasi')->with('data', $data);
  }

  public function search_verif(Request $request)
  {
    $query = $request->input('query');

    $data = DB::table('users')
      ->join('prodi', 'users.prd_id', '=', 'prodi.id')
      ->join('departement', 'users.dpt_id', '=', 'departement.id')
      ->where('users.role', 'non_mahasiswa')
      ->whereIn('users.status', ['mahasiswa', 'alumni'])
      ->where(function ($q) use ($query) {
        $q->where('users.nama', 'LIKE', "%{$query}%")
          ->orWhere('users.nmr_unik', 'LIKE', "%{$query}%")
          ->orWhere('users.email', 'LIKE', "%{$query}%")
          ->orWhere('departement.nama_dpt', 'LIKE', "%{$query}%");
      })
      ->select(
        'users.id',
        'users.nama',
        'users.nmr_unik',
        'departement.nama_dpt',
      )
      ->get();

    return view('admin.verifikasi', compact('data'));
  }

  // function soft_delete_view(Request $request)
  // {
  //   $query = $request->input('query');

  //   $data = DB::table('users')
  //     ->join('prodi', 'users.prd_id', '=', 'prodi.id')
  //     ->join('departement', 'users.dpt_id', '=', 'departement.id')
  //     ->join('jenjang_pendidikan', 'users.jnjg_id', '=', 'jenjang_pendidikan.id')
  //     ->where('users.role', 'del_mahasiswa')
  //     ->whereIn('users.status', ['mahasiswa', 'alumni'])
  //     ->where(function ($q) use ($query) {
  //       $q->where('users.nama', 'LIKE', "%{$query}%")
  //         ->orWhere('users.nmr_unik', 'LIKE', "%{$query}%")
  //         ->orWhere('users.email', 'LIKE', "%{$query}%")
  //         ->orWhere('departement.nama_dpt', 'LIKE', "%{$query}%")
  //         ->orWhere(DB::raw('CONCAT(jenjang_pendidikan.nama_jnjg, " - ", prodi.nama_prd)'), 'LIKE', "%{$query}%");
  //     })
  //     ->select(
  //       'users.id',
  //       'users.nama',
  //       'users.nmr_unik',
  //       'departement.nama_dpt',
  //       DB::raw('CONCAT(jenjang_pendidikan.nama_jnjg, " - ", prodi.nama_prd) as jenjang_prodi'),
  //     )
  //     ->get();

  //   return view('admin.soft_del', compact('data'));
  // }

  public function soft_delete($id)
  {
    $user = User::find($id);

    if ($user) {
      if ($user->role === 'mahasiswa') {
        $user->role = 'del_mahasiswa';
        $user->save();

        return redirect('/admin/user')->with('success', 'Akun berhasil dihapus sementara');
      }

      return redirect('/admin/user')->withErrors('Role pengguna tidak dapat diubah.');
    }
    return redirect('/admin/user')->withErrors('Akun tidak ditemukan.');
  }

  function restore($id)
  {
    $user = DB::table('users')->where('id', $id)->first();

    if ($user) {
      if ($user->role === 'del_mahasiswa') {
        DB::table('users')->where('id', $id)->update(['role' => 'mahasiswa']);

        return redirect('/admin/user')->with('success', 'Akun berhasil dipulihkan');
      } else {
        return redirect('/admin/user')->with('error', 'Role tidak sesuai');
      }
    } else {
      return redirect('/admin/user')->with('error', 'User tidak ditemukan');
    }
  }

  function delete_user($id)
  {
    $data =  User::where('id', $id)->first();
    File::delete(public_path('storage/foto/mahasiswa') . '/' . $data->foto);
    User::where('id', $id)->delete();
    return redirect('/admin/user')->with('success', 'Berhasil menghapus permanen akun');
  }

  function edit($id)
  {
    $user = User::findOrFail($id);
    return view('admin.edit', compact('user'));
  }

  function update(Request $request, $id)
  {
    $user = User::findOrFail($id);

    $request->validate([
      'email' => 'required|email|unique:users,email,' . $user->id,
      'password' => 'nullable|min:8',
    ], [
      'email.required' => 'Email wajib diisi',
      'email.email' => 'Email harus valid',
      'email.unique' => 'Email sudah digunakan, silakan masukkan Email yang lain',
      'password.min' => 'Password minimal 8 karakter',
    ]);

    $user->email = $request->email;
    $user->status = $request->status;

    if ($request->filled('password')) {
      $user->password = Hash::make($request->password);
    }

    $user->save();
    return redirect()->route('admin.user')->with('success', 'Berhasil mengupdate data user');
  }

  function cekdata_mhw($id)
  {
    $user = DB::table('users')
      ->join('prodi', 'users.prd_id', '=', 'prodi.id')
      ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
      ->where('users.id', $id)
      ->select(
        'users.id',
        'prodi.id as prodi_id',
        'departement.id as dpt_id',
        'users.nama',
        'users.nmr_unik',
        DB::raw('CONCAT(users.kota, ", ", DATE_FORMAT(users.tanggal_lahir, "%d-%m-%Y")) as ttl'),
        'users.nowa',
        'users.nama_ibu',
        'users.foto',
        'users.role',
        'users.email',
        'users.status',
        'users.almt_asl',
        'departement.nama_dpt',
        'prodi.nama_prd',
      )
      ->first();
    return view('admin.cekdata', compact('user'));
  }

  public function catatan(Request $request, $id)
  {
    $users = User::findOrFail($id);

    $request->validate([
      'catatan_user' => 'required',
    ], [
      'catatan_user.required' => 'Catatan kepada user wajib diisi',
    ]);

    $users->catatan_user = $request->catatan_user;

    $users->save();
    return redirect()->route('admin.verifikasi')->with('success', 'Penolakan verifikasi berhasil dilakukan');
  }

  function verifikasi($id)
  {
    $user = DB::table('users')->where('id', $id)->first();

    if ($user->role == 'non_mahasiswa') {
      DB::table('users')->where('id', $id)->update(['role' => 'mahasiswa']);
    }

    return redirect()->route('admin.verifikasi')->with('success', 'Akun telah diverifikasi');
  }
}
