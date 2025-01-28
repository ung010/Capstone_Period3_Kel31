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
    $prodi = prodi::get();
    $departemen = departemen::get();

    $data = [
      'prodi' => $prodi,
      'departemen' => $departemen,
    ];
    return view('auth.register', $data);
  }

  function create(Request $request)
  {
    Session::flash('nama', $request->input('nama'));
    Session::flash('nim_nip', $request->input('nim_nip'));
    Session::flash('email', $request->input('email'));
    Session::flash('kota', $request->input('kota'));
    Session::flash('tanggal_lahir', $request->input('tanggal_lahir'));
    Session::flash('nowa', $request->input('nowa'));
    Session::flash('nama_ibu', $request->input('nama_ibu'));
    Session::flash('status', $request->input('status'));
    Session::flash('almt_asl', $request->input('almt_asl'));
    Session::flash('dpt_id', $request->input('dpt_id'));

    $request->validate([
      'nama' => 'required',
      'nim_nip' => 'required|unique:users',
      'email' => 'required|email|unique:users|regex:/^[a-zA-Z0-9._%+-]+@students\.undip\.ac\.id$/',
      'kota' => 'required',
      'tanggal_lahir' => 'required',
      'nama_ibu' => 'required',
      'nowa' => 'required',
      'status' => 'required',
      'almt_asl' => 'required',
      'password' => 'required|min:8|max:20',
      'prd_id' => 'required',
      'foto' => 'required|mimes:jpeg,jpg,png|image|max:2048'
    ], [
      'nama.required' => 'Nama wajib diisi',
      'nim_nip.required' => 'NIM wajib diisi',
      'nim_nip.unique' => 'NIM sudah digunakan, silakan masukkan NIM yang lain',
      'email.required' => 'Email wajib diisi',
      'email.email' => 'Email harus valid',
      'email.unique' => 'Email sudah digunakan, silakan masukkan Email yang lain',
      'email.regex' => 'Email harus menggunakan @students.undip.ac.id',
      'password.required' => 'Password wajib diisi',
      'nama_ibu.required' => 'Nama ibu wajib diisi',
      'password.min' => 'Password minimal 8 karakter',
      'password.max' => 'Password maximal 20 karakter',
      'kota.required' => 'Tempat lahir wajib diisi',
      'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
      'status.required' => 'Wajib mengisi pilihan mahasiswa / alumni',
      'nowa.required' => 'No handphone wajib diisi',
      'almt_asl.required' => 'Alamat asal rumah wajib diisi',
      'prd_id.required' => 'Prodi wajib diisi',
      'foto.required' => 'Foto wajib diisi',
      'foto.mimes' => 'Foto wajib berbentuk JPEG, JPG, dan PNG',
      'foto.image' => 'File harus berupa gambar',
      'foto.max' => 'Ukuran file gambar maksimal adalah 2 Mb'
    ]);

    $foto = $request->file('foto');
    $foto_extensi = $foto->extension();
    $nama_foto = date('ymdhis') . '.' . $foto_extensi;
    $foto->move(public_path('storage/foto/mahasiswa'), $nama_foto);

    $id = mt_rand(1000000000000, 9999999999999);

    $data = [
      'id' => $id,
      'nama' => $request->nama,
      'nim_nip' => $request->nim_nip,
      'email' => $request->email,
      'password' => Hash::make($request->password),
      'kota' => $request->kota,
      'tanggal_lahir' => $request->tanggal_lahir,
      'status' => $request->status,
      'nowa' => $request->nowa,
      'nama_ibu' => $request->nama_ibu,
      'almt_asl' => $request->almt_asl,
      'prd_id' => $request->prd_id,
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

  function getProdiByDepartemen($departemenId)
  {
    $prodi = prodi::where('dpt_id', $departemenId)->get();
    return response()->json($prodi);
  }
}
