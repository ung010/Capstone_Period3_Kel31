<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\prodi;
use App\Models\departemen;
use App\Models\jenjang_pendidikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class NonController extends Controller
{
  function home_non_mhw()
  {
    $user = Auth::user();
    return view('non_mhw.home', compact('user'));
  }

  function edit_non_mhw()
  {
    $user = Auth::user();

    $data = DB::table('users')
      ->join('prodi', 'users.prd_id', '=', 'prodi.id')
      ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
      ->where('users.id', $user->id)
      ->select(
        'users.id',
        'prodi.id as prd_id',
        'departement.id as dpt_id',
        'users.nama',
        'users.nim_nip',
        'users.nowa',
        'users.email',
        'users.kota',
        'users.tanggal_lahir',
        'users.almt_asl',
        'users.foto',
        'users.nama_ibu',
        'users.password',
        'users.role',
        'users.status',
        'users.catatan_user'
      )
      ->first();

    $data = (array) $data;

    $departemen = Departemen::get();
    $prodi = Prodi::get();

    $data['departemen'] = $departemen;
    $data['prodi'] = $prodi;
    $data['user'] = $user;

    return view('non_mhw.account', compact('data' ,'user', 'departemen', 'prodi'));
  }


  public function getProdi($departemen_id)
{
    $prodi = Prodi::where('dpt_id', $departemen_id)->get();
    return response()->json($prodi);
}

  public function account_non_mhw(Request $request)
  {
    $userId = Auth::id();
    $request->validate([
      'nama' => 'required',
      'nim_nip' => 'required|unique:users,nim_nip,' . $userId,
      'email' => 'required|email|unique:users,email,' . $userId,
      'kota' => 'required',
      'tanggal_lahir' => 'required',
      'nowa' => 'required',
      'nama_ibu' => 'required',
      'almt_asl' => 'required',
      'prd_id' => 'required',
      'foto' => 'image|mimes:jpeg,png,jpg|max:2048',
      'status' => 'required'
    ], [
      'nama.required' => 'Nama wajib diisi',
      'nim_nip.required' => 'NIM wajib diisi',
      'nim_nip.unique' => 'NIM sudah digunakan, silakan masukkan NIM yang lain',
      'email.required' => 'Email wajib diisi',
      'email.email' => 'Email harus valid',
      'email.unique' => 'Email sudah digunakan, silakan masukkan Email yang lain',
      'kota.required' => 'Tempat lahir wajib diisi',
      'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
      'nowa.required' => 'No handphone wajib diisi',
      'almt_asl.required' => 'Alamat asal rumah wajib diisi',
      'prd_id.required' => 'Prodi wajib diisi',
      'foto.image' => 'File harus berupa gambar',
      'foto.mimes' => 'File harus berupa gambar dengan format jpeg, png, atau jpg',
      'foto.max' => 'Ukuran file gambar maksimal adalah 2048 kilobyte',
      'status.required' => 'Status mahasiswa / alumni wajib diisi'
    ]);

    $user = DB::table('users')->where('id', $userId)->first();

    if ($request->hasFile('foto')) {
      if ($user->foto && file_exists(public_path('storage/foto/mahasiswa/' . $user->foto))) {
          unlink(public_path('storage/foto/mahasiswa/' . $user->foto));
      }

      $foto = $this->handleFileUpload($request->file('foto'));
    } else {
      $foto = $user->foto;
    }

    DB::table('users')->where('id', $userId)->update([
      'nama' => $request->nama,
      'nim_nip' => $request->nim_nip,
      'email' => $request->email,
      'kota' => $request->kota,
      'tanggal_lahir' => $request->tanggal_lahir,
      'nowa' => $request->nowa,
      'almt_asl' => $request->almt_asl,
      'nama_ibu' => $request->nama_ibu,
      'prd_id' => $request->prd_id,
      'password' => $request->filled('password') ? Hash::make($request->password) : DB::raw('password'),
      'foto' => $foto,
      'status' => $request->status,
      'catatan_user' => '-',
    ]);

    return redirect()->back()->with('success', 'Berhasil mengupdate data diri');
  }

  private function handleFileUpload($file)
  {
      $fileExtension = $file->extension();
      $fileName = date('ymdhis') . '.' . $fileExtension;
      $file->move(public_path('storage/foto/mahasiswa'), $fileName);
      return $fileName;
  }

  function del_mhw()
  {
    $user = Auth::user();
    return view('del_mhw.home', compact('user'));
  }
}
