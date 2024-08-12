<?php

namespace App\Http\Controllers;

use App\Models\departemen;
use App\Models\jenjang_pendidikan;
use App\Models\prodi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{
  function register()
  {
    $jenjang_pendidikan = jenjang_pendidikan::get();
    $departemen = departemen::get();
    $prodi = prodi::get();

    $data = [
      'jenjang_pendidikan' => $jenjang_pendidikan,
      'departemen' => $departemen,
      'prodi' => $prodi,
    ];
    return view('auth.register', $data);
  }

  function create(Request $request)
  {
    Session::flash('nama', $request->input('nama'));
    Session::flash('nmr_unik', $request->input('nmr_unik'));
    Session::flash('email', $request->input('email'));
    Session::flash('kota', $request->input('kota'));
    Session::flash('tanggal_lahir', $request->input('tanggal_lahir'));
    Session::flash('nowa', $request->input('nowa'));
    Session::flash('nama_ibu', $request->input('nama_ibu'));
    Session::flash('status', $request->input('status'));
    Session::flash('almt_asl', $request->input('almt_asl'));
    Session::flash('prd_id', $request->input('prd_id'));
    Session::flash('dpt_id', $request->input('dpt_id'));
    Session::flash('jnjg_id', $request->input('jnjg_id'));

    $request->validate([
      'nama' => 'required',
      'nmr_unik' => 'required|unique:users',
      'email' => 'required|email|unique:users',
      'kota' => 'required',
      'tanggal_lahir' => 'required',
      'nama_ibu' => 'required',
      'nowa' => 'required',
      'status' => 'required',
      'almt_asl' => 'required',
      'password' => 'required|min:8',
      'dpt_id' => 'required',
      'prd_id' => 'required',
      'jnjg_id' => 'required',
      'foto' => 'required|mimes:jpeg,jpg,png|image|max:2048'
    ], [
      'nama.required' => 'Nama wajib diisi',
      'nmr_unik.required' => 'NIM wajib diisi',
      'nmr_unik.unique' => 'NIM sudah digunakan, silakan masukkan NIM yang lain',
      'email.required' => 'Email wajib diisi',
      'email.email' => 'Email harus valid',
      'email.unique' => 'Email sudah digunakan, silakan masukkan Email yang lain',
      'password.required' => 'Password wajib diisi',
      'nama_ibu.required' => 'Nama ibu wajib diisi',
      'password.min' => 'Password minimal 8 karakter',
      'kota.required' => 'Tempat lahir wajib diisi',
      'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
      'status.required' => 'Wajib mengisi pilihan mahasiswa / alumni',
      'nowa.required' => 'No handphone wajib diisi',
      'almt_asl.required' => 'Alamat asal rumah wajib diisi',
      'dpt_id.required' => 'Departemen wajib diisi',
      'prd_id.required' => 'Prodi wajib diisi',
      'jnjg_id.required' => 'Jenjang Pendidikan wajib diisi',
      'foto.required' => 'Foto wajib diisi',
      'foto.mimes' => 'Foto wajib berbentuk JPEG, JPG, dan PNG',
      'foto.image' => 'File harus berupa gambar',
      'foto.max' => 'Ukuran file gambar maksimal adalah 2048 kilobyte'
    ]);

    $foto = $request->file('foto');
    $foto_extensi = $foto->extension();
    $nama_foto = date('ymdhis') . '.' . $foto_extensi;
    $foto->move(public_path('storage/foto/mahasiswa'), $nama_foto);

    $data = [
      'nama' => $request->nama,
      'nmr_unik' => $request->nmr_unik,
      'email' => $request->email,
      'password' => Hash::make($request->password),
      'kota' => $request->kota,
      'tanggal_lahir' => $request->tanggal_lahir,
      'status' => $request->status,
      'nowa' => $request->nowa,
      'nama_ibu' => $request->nama_ibu,
      'almt_asl' => $request->almt_asl,
      'dpt_id' => $request->dpt_id,
      'prd_id' => $request->prd_id,
      'jnjg_id' => $request->jnjg_id,
      'foto' => $nama_foto
    ];
    User::create($data);

    $inforegister = [
      'email' => $request->email,
      'password' => $request->password
    ];

    if (Auth::attempt($inforegister)) {
      $user = Auth::user();

      if ($user->role == 'non_mahasiswa') {
        return redirect('/non_user');
      } else {
        return redirect('/error_register');
      }
    } else {
      return redirect('/register')->withErrors('Email, password, atau data lain yang dimasukkan tidak sesuai');
    }
  }
}
