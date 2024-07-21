<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class NonController extends Controller
{
    function home_non_mhw() {
        return view('non_mhw.home');
    }

    function edit_non_mhw($id)
    {
        $user = User::findOrFail($id);
        return view('non_mhw.account', compact('user'));
    }

    public function account_non_mhw(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama' => 'required',
            'nmr_unik' => 'required|unique:users,nmr_unik,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'kota' => 'required',
            'tanggal_lahir' => 'required',
            'nowa' => 'required',
            'nama_ibu' => 'required',
            'almt_asl' => 'required',
            'dpt_id' => 'required',
            'prd_id' => 'required',
            'jnjg_id' => 'required',
            // 'password' => 'required|min:8',
        ], [
            'nama.required' => 'Nama wajib diisi',
            'nmr_unik.required' => 'NIM wajib diisi',
            'nmr_unik.unique' => 'NIM sudah digunakan, silakan masukkan NIM yang lain',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Email harus valid',
            'email.unique' => 'Email sudah digunakan, silakan masukkan Email yang lain',
            // 'password.required' => 'Password wajib diisi',
            // 'password.min' => 'Password minimal 8 karakter',
            'kota.required' => 'Tempat lahir wajib diisi',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
            'nowa.required' => 'No handphone wajib diisi',
            'almt_asl.required' => 'Alamat asal rumah wajib diisi',
            'dpt_id.required' => 'Departemen wajib diisi',
            'prd_id.required' => 'Prodi wajib diisi',
            'jnjg_id.required' => 'Jenjang pendidikan wajib diisi',
        ]);

        $user->nama = $request->nama;
        $user->nmr_unik = $request->nmr_unik;
        $user->email = $request->email;
        $user->kota = $request->kota;
        $user->tanggal_lahir = $request->tanggal_lahir;
        $user->nowa = $request->nowa;
        $user->almt_asl = $request->almt_asl;
        $user->jnjg_id = $request->jnjg_id;
        $user->dpt_id = $request->dpt_id;
        $user->prd_id = $request->prd_id;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
    
        $user->save();
        return redirect()->route('non_mhw.home')->with('success', 'Berhasil mengupdate data diri');
    }

    function home_non_alum() {
        return view('non_alum.home');
    }

    function edit_non_alum($id)
    {
        $user = User::findOrFail($id);
        return view('non_alum.account', compact('user'));
    }

    public function account_non_alum(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama' => 'required',
            'nmr_unik' => 'required|unique:users,nmr_unik,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'kota' => 'required',
            'tanggal_lahir' => 'required',
            'nowa' => 'required',
            'nama_ibu' => 'required',
            'almt_asl' => 'required',
            'dpt_id' => 'required',
            'prd_id' => 'required',
            'jnjg_id' => 'required',
            // 'password' => 'required|min:8',
        ], [
            'nama.required' => 'Nama wajib diisi',
            'nmr_unik.required' => 'NIM wajib diisi',
            'nmr_unik.unique' => 'NIM sudah digunakan, silakan masukkan NIM yang lain',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Email harus valid',
            'email.unique' => 'Email sudah digunakan, silakan masukkan Email yang lain',
            // 'password.required' => 'Password wajib diisi',
            // 'password.min' => 'Password minimal 8 karakter',
            'kota.required' => 'Tempat lahir wajib diisi',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
            'nowa.required' => 'No handphone wajib diisi',
            'almt_asl.required' => 'Alamat asal rumah wajib diisi',
            'dpt_id.required' => 'Departemen wajib diisi',
            'prd_id.required' => 'Prodi wajib diisi',
            'jnjg_id.required' => 'Jenjang pendidikan wajib diisi',
        ]);

        $user->nama = $request->nama;
        $user->nmr_unik = $request->nmr_unik;
        $user->email = $request->email;
        $user->kota = $request->kota;
        $user->tanggal_lahir = $request->tanggal_lahir;
        $user->nowa = $request->nowa;
        $user->almt_asl = $request->almt_asl;
        $user->jnjg_id = $request->jnjg_id;
        $user->dpt_id = $request->dpt_id;
        $user->prd_id = $request->prd_id;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
    
        $user->save();
        return redirect()->route('non_alum.home')->with('success', 'Berhasil mengupdate data diri');
    }
}
