<?php

use App\Http\Controllers\Admin_Legalisir_Controller;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Legal_Controller;
use App\Http\Controllers\Legalisir_Controller;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\Manajer_Legalisir_Controller;
use App\Http\Controllers\ManajerController;
use App\Http\Controllers\NonController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Riwayat_Surat_Controller;
use App\Http\Controllers\Srt_Bbs_Pnjam_Controller;
use App\Http\Controllers\Srt_Izin_Penelitian_Controller;
use App\Http\Controllers\Srt_Magang_Controller;
use App\Http\Controllers\srt_masih_mhwController;
use App\Http\Controllers\Srt_Mhw_AsnController;
use App\Http\Controllers\srt_pmhn_kmbali_biaya_controller;
use App\Http\Controllers\Supervisor_Legalisir_Controller;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\Survey_Controller;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WDController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Legal
Route::get('/legal/srt_masih_mhw/{id}', [Legal_Controller::class, 'srt_masih_mhw'])->name('srt_masih_mhw.legal');
Route::get('/legal/srt_masih_mhw/view/wd/{id}', [Legal_Controller::class, 'lihat_srt_masih_mhw_wd'])->name('srt_masih_mhw.legal_lihat_wd');
Route::get('/legal/srt_masih_mhw/view/manajer/{id}', [Legal_Controller::class, 'lihat_srt_masih_mhw_manajer'])->name('srt_masih_mhw.legal_lihat_manajer');
Route::get('/legal/srt_mhw_asn/{id}', [Legal_Controller::class, 'srt_mhw_asn'])->name('srt_mhw_asn.legal');
Route::get('/legal/srt_mhw_asn/view/{id}', [Legal_Controller::class, 'lihat_srt_mhw_asn'])->name('srt_mhw_asn.legal_lihat');
Route::get('/legal/srt_magang/{id}', [Legal_Controller::class, 'srt_magang'])->name('srt_magang.legal');
Route::get('/legal/srt_magang/view/{id}', [Legal_Controller::class, 'lihat_srt_magang'])->name('srt_magang.legal_lihat');
Route::get('/legal/srt_izin_plt/{id}', [Legal_Controller::class, 'srt_izin_plt'])->name('srt_izin_plt.legal');
Route::get('/legal/srt_izin_plt/view/{id}', [Legal_Controller::class, 'lihat_srt_izin_plt'])->name('srt_izin_plt.legal_lihat');
Route::get('/legal/srt_pmhn_kmbali_biaya/{id}', [Legal_Controller::class, 'srt_pmhn_kmbali_biaya'])->name('srt_pmhn_kmbali_biaya.legal');
Route::get('/legal/srt_pmhn_kmbali_biaya/view/{id}', [Legal_Controller::class, 'lihat_srt_pmhn_kmbali_biaya'])->name('srt_pmhn_kmbali_biaya.legal_lihat');
Route::get('/legal/srt_bbs_pnjm/{id}', [Legal_Controller::class, 'srt_bbs_pnjm'])->name('srt_bbs_pnjm.legal');
Route::get('/legal/srt_bbs_pnjm/view/{id}', [Legal_Controller::class, 'lihat_srt_bbs_pnjm'])->name('srt_bbs_pnjm.legal_lihat');


Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::get('/login', [LoginController::class, 'index'])->name('auth.login');
    Route::post('/', [LoginController::class, 'login'])->name('auth.login');
    Route::get('/register', [RegisterController::class, 'register'])->name('register');
    Route::post('/register/create', [RegisterController::class, 'create'])->name('register.create');
    Route::get('/get-prodi/{departemenId}', [RegisterController::class, 'getProdiByDepartemen']);

});

route::get('/home', function () {
    return redirect('/error_register');
});

Route::middleware('auth')->group(function () {
    route::get('/users', [UsersController::class, 'users']);
    Route::get('/logout', [LoginController::class, 'logout']);

    route::get('/error_register', [UsersController::class, 'home']);

    route::get('/non_user', [NonController::class, 'home_non_mhw'])->name('non_mhw.home')->
    middleware('UserAkses:non_mahasiswa');
    Route::get('/non_user/my_account', [NonController::class, 'edit_non_mhw'])->name('non_mhw.edit')->middleware('UserAkses:non_mahasiswa');
    Route::post('/non_user/my_account/update', [NonController::class, 'account_non_mhw'])->name('non_mhw.account_non_mhw')->
    middleware('UserAkses:non_mahasiswa');

    route::get('/del_user', [NonController::class, 'del_mhw'])->name('del_mhw.home')->
    middleware('UserAkses:del_mahasiswa');

    // Route Mahasiswa
    route::get('/user', [MahasiswaController::class, 'index'])->name('mahasiswa.index')->
    middleware('UserAkses:mahasiswa');
    Route::get('/user/my_account', [MahasiswaController::class, 'edit'])->name('mahasiswa.edit')->
    middleware('UserAkses:mahasiswa');
    Route::post('/user/my_account/update', [MahasiswaController::class, 'update'])->name('mahasiswa.update')->
    middleware('UserAkses:mahasiswa');

    // Route Admin
    route::get('/admin', [AdminController::class, 'index'])->name('admin.index')->middleware('UserAkses:admin');

    route::get('/admin/user', [AdminController::class, 'user'])->name('admin.user')->middleware('UserAkses:admin');
    Route::get('/admin/user/search', [AdminController::class, 'search_user'])->name('admin.user.search')->middleware('UserAkses:admin');
    Route::get('/admin/user/edit/{id}', [AdminController::class, 'edit'])->name('admin.edit')->
    middleware('UserAkses:admin');
    Route::post('/admin/user/update/{id}', [AdminController::class, 'update'])->name('admin.update')->
    middleware('UserAkses:admin');

    route::get('/admin/verif_user', [AdminController::class, 'verif_user'])->name('admin.verifikasi')->middleware('UserAkses:admin');
    Route::get('/admin/verif_user/search', [AdminController::class, 'search_verif'])->name('admin.verif.search')->middleware('UserAkses:admin');
    Route::get('/admin/verif_user/cekdata/{id}', [AdminController::class, 'cekdata_mhw'])->name('admin.cekdata')->middleware('UserAkses:admin');
    Route::post('/admin/verif_user/cekdata/catatan/{id}',  [AdminController::class, 'catatan'])->name('admin.catatan')->middleware('UserAkses:admin');
    Route::post('/admin/verif_user/cekdata/verifikasi/{id}', [AdminController::class, 'verifikasi'])->name('admin.verifikasi.user')->middleware('UserAkses:admin');

    route::get('/admin/soft_delete', [AdminController::class, 'soft_delete_view'])->name('admin.soft_delete_view')
    ->middleware('UserAkses:admin');
    route::get('/admin/soft_delete/search', [AdminController::class, 'soft_delete_view'])->name('admin.soft_delete_search')
    ->middleware('UserAkses:admin');
    route::post('/admin/soft_delete/s_delete/{id}', [AdminController::class, 'soft_delete'])->name('admin.soft_delete')
    ->middleware('UserAkses:admin');
    route::post('/admin/soft_delete/restore/{id}', [AdminController::class, 'restore'])->name('admin.restore')
    ->middleware('UserAkses:admin');
    route::post('/admin/soft_delete/h_delete/{id}', [AdminController::class, 'delete_user'])->name('admin.hard_delete')
    ->middleware('UserAkses:admin');

    route::get('/supervisor_akd', [SupervisorController::class, 'index_akd'])->name('supervisor_akd.index')->
    middleware('UserAkses:supervisor_akd');
    route::get('/supervisor_akd/manage_admin', [SupervisorController::class, 'manage_admin_akd'])->name('supervisor_akd.manage_admin')->
    middleware('UserAkses:supervisor_akd');
    route::post('/supervisor_akd/manage_admin/delete/{id}', [SupervisorController::class, 'delete_admin_akd'])->name('supervisor_akd.delete')
    ->middleware('UserAkses:supervisor_akd');
    Route::post('/supervisor_akd/manage_admin/create', [SupervisorController::class, 'create_akd'])->name('supervisor_akd.create_akd')
    ->middleware('UserAkses:supervisor_akd');
    Route::post('/supervisor_akd/manage_admin/edit/{id}',  [SupervisorController::class, 'edit_akd'])->name('supervisor_akd.edit_akd')
    ->middleware('UserAkses:supervisor_akd');


    route::get('/supervisor_sd', [SupervisorController::class, 'index_sd'])->name('supervisor_sd.index')->
    middleware('UserAkses:supervisor_sd');
    route::get('/supervisor_sd/manage_admin', [SupervisorController::class, 'manage_admin_sd'])->name('supervisor_sd.manage_admin')->
    middleware('UserAkses:supervisor_sd');
    route::post('/supervisor_sd/manage_admin/delete/{id}', [SupervisorController::class, 'delete_admin_sd'])->name('supervisor_sd.delete')
    ->middleware('UserAkses:supervisor_sd');
    Route::post('/supervisor_sd/manage_admin/create', [SupervisorController::class, 'create_sd'])->name('supervisor_sd.create_sd')
    ->middleware('UserAkses:supervisor_sd');
    Route::post('/supervisor_sd/manage_admin/edit/{id}',  [SupervisorController::class, 'edit_sd'])->name('supervisor_sd.edit_sd')
    ->middleware('UserAkses:supervisor_sd');

    route::get('/manajer', [ManajerController::class, 'index'])->name('manajer.index')->
    middleware('UserAkses:manajer');
    route::get('/manajer/manage_spv', [ManajerController::class, 'manage_spv'])->name('manajer.manage_spv')->
    middleware('UserAkses:manajer');
    Route::post('/manajer/manage_spv/edit/{id}',  [ManajerController::class, 'edit_spv'])->name('manajer.edit_spv')
    ->middleware('UserAkses:manajer');
    Route::get('/manajer/account', [ManajerController::class, 'edit_account'])->name('manajer.edit_account')->
    middleware('UserAkses:manajer');
    Route::post('/manajer/account/update/{id}', [ManajerController::class, 'update_account'])->name('manajer.update_account')->
    middleware('UserAkses:manajer');

    route::get('/wd1', [WDController::class, 'index_wd1'])->name('wd1.index')->
    middleware('UserAkses:wd1');
    Route::get('/wd1/account', [WDController::class, 'edit_wd1_account'])->name('wd1.edit_account')->
    middleware('UserAkses:wd1');
    Route::post('/wd1/account/update/{id}', [WDController::class, 'update_wd1_account'])->name('wd1.update_account')->
    middleware('UserAkses:wd1');

    route::get('/wd2', [WDController::class, 'index_wd2'])->name('wd2.index')->
    middleware('UserAkses:wd2');
    Route::get('/wd2/account', [WDController::class, 'edit_wd2_account'])->name('wd2.edit_account')->
    middleware('UserAkses:wd2');
    Route::post('/wd2/account/update/{id}', [WDController::class, 'update_wd2_account'])->name('wd2.update_account')->
    middleware('UserAkses:wd2');


    // Surat Mahasiswa ASN
    route::get('/srt_mhw_asn', [Srt_Mhw_AsnController::class, 'index'])->name('srt_mhw_asn.index')
    ->middleware('UserAkses:mahasiswa');
    Route::post('/srt_mhw_asn/create', [Srt_Mhw_AsnController::class,'create'])->name('srt_mhw_asn.store')
    ->middleware('UserAkses:mahasiswa');
    Route::get('/srt_mhw_asn/search', [Srt_Mhw_AsnController::class, 'index'])->name('srt_mhw_asn.search')
    ->middleware('UserAkses:mahasiswa');
    Route::get('/srt_mhw_asn/edit/{id}', [Srt_Mhw_AsnController::class, 'edit'])->name('srt_mhw_asn.edit')->
    middleware('UserAkses:mahasiswa');
    Route::post('/srt_mhw_asn/update/{id}', [Srt_Mhw_AsnController::class, 'update'])->name('srt_mhw_asn.update')->
    middleware('UserAkses:mahasiswa');
    Route::get('/srt_mhw_asn/download/{id}', [Srt_Mhw_AsnController::class, 'download'])->name('srt_mhw_asn.download')->
    middleware('UserAkses:mahasiswa');

    route::get('/srt_mhw_asn/admin', [Srt_Mhw_AsnController::class, 'admin'])->name('srt_mhw_asn.admin')
    ->middleware('UserAkses:admin');
    Route::get('/srt_mhw_asn/admin/search', [Srt_Mhw_AsnController::class, 'admin'])->name('srt_mhw_asn.admin_search')
    ->middleware('UserAkses:admin');
    Route::get('/srt_mhw_asn/admin/cek_surat/{id}', [Srt_Mhw_AsnController::class, 'cek_surat_admin'])->name('srt_mhw_asn.cek_data')
    ->middleware('UserAkses:admin');
    Route::post('/srt_mhw_asn/admin/cek_surat/setuju/{id}',  [Srt_Mhw_AsnController::class, 'setuju'])->name('srt_mhw_asn.setuju')
    ->middleware('UserAkses:admin');
    Route::post('/srt_mhw_asn/admin/cek_surat/tolak/{id}',  [Srt_Mhw_AsnController::class, 'tolak'])->name('srt_mhw_asn.tolak')
    ->middleware('UserAkses:admin');

    route::get('/srt_mhw_asn/supervisor', [Srt_Mhw_AsnController::class, 'supervisor_akd'])->name('srt_mhw_asn.supervisor')
    ->middleware('UserAkses:supervisor_akd');
    Route::get('/srt_mhw_asn/supervisor/search', [Srt_Mhw_AsnController::class, 'supervisor_akd'])->name('srt_mhw_asn.sv_search')
    ->middleware('UserAkses:supervisor_akd');
    Route::get('/srt_mhw_asn/supervisor/cek_surat/{id}', [Srt_Mhw_AsnController::class, 'cek_sv'])->name('srt_mhw_asn.cek_sv')
    ->middleware('UserAkses:supervisor_akd');
    Route::post('/srt_mhw_asn/supervisor/cek_surat/setuju/{id}',  [Srt_Mhw_AsnController::class, 'setuju_sv'])->name('srt_mhw_asn.setuju_sv')
    ->middleware('UserAkses:supervisor_akd');
    Route::post('/srt_mhw_asn/supervisor/cek_surat/tolak/{id}',  [Srt_Mhw_AsnController::class, 'tolak_sv'])->name('srt_mhw_asn.tolak_sv')
    ->middleware('UserAkses:supervisor_akd');

    route::get('/srt_mhw_asn/manajer', [Srt_Mhw_AsnController::class, 'manajer'])->name('srt_mhw_asn.manajer')
    ->middleware('UserAkses:manajer');
    Route::get('/srt_mhw_asn/manajer/search', [Srt_Mhw_AsnController::class, 'manajer'])->name('srt_mhw_asn.manajer_search')
    ->middleware('UserAkses:manajer');
    Route::get('/srt_mhw_asn/manajer/cek_surat/{id}', [Srt_Mhw_AsnController::class, 'cek_manajer'])->name('srt_mhw_asn.cek_manajer')
    ->middleware('UserAkses:manajer');
    Route::post('/srt_mhw_asn/manajer/cek_surat/setuju/{id}',  [Srt_Mhw_AsnController::class, 'setuju_manajer'])->name('srt_mhw_asn.setuju_manajer')
    ->middleware('UserAkses:manajer');
    Route::post('/srt_mhw_asn/manajer/cek_surat/tolak/{id}',  [Srt_Mhw_AsnController::class, 'tolak_manajer'])->name('srt_mhw_asn.tolak_manajer')
    ->middleware('UserAkses:manajer');

    // Surat Masih Mahasiswa
    route::get('/srt_masih_mhw', [srt_masih_mhwController::class, 'index'])->name('srt_masih_mhw.index')
    ->middleware('UserAkses:mahasiswa');
    Route::post('/srt_masih_mhw/create', [srt_masih_mhwController::class,'create'])->name('srt_masih_mhw.store')
    ->middleware('UserAkses:mahasiswa');
    Route::get('/srt_masih_mhw/search', [srt_masih_mhwController::class, 'index'])->name('srt_masih_mhw.search')
    ->middleware('UserAkses:mahasiswa');
    Route::get('/srt_masih_mhw/edit/{id}', [srt_masih_mhwController::class, 'edit'])->name('srt_masih_mhw.edit')->
    middleware('UserAkses:mahasiswa');
    Route::post('/srt_masih_mhw/update/{id}', [srt_masih_mhwController::class, 'update'])->name('srt_masih_mhw.update')->
    middleware('UserAkses:mahasiswa');
    Route::get('/srt_masih_mhw/wd/download/{id}', [srt_masih_mhwController::class, 'download_wd'])->name('srt_masih_mhw.download_wd')->
    middleware('UserAkses:mahasiswa');
    Route::get('/srt_masih_mhw/manajer/download/{id}', [srt_masih_mhwController::class, 'download_manajer'])->name('srt_masih_mhw.download_manajer')->
    middleware('UserAkses:mahasiswa');

    route::get('/srt_masih_mhw/admin/manajer', [srt_masih_mhwController::class, 'admin'])->name('srt_masih_mhw.admin')
    ->middleware('UserAkses:admin');
    Route::get('/srt_masih_mhw/admin/manajer/search', [srt_masih_mhwController::class, 'admin'])->name('srt_masih_mhw.admin_search')
    ->middleware('UserAkses:admin');
    Route::get('/srt_masih_mhw/admin/manajer/cek_surat/{id}', [srt_masih_mhwController::class, 'cek_surat_admin'])->name('srt_masih_mhw.cek_data')
    ->middleware('UserAkses:admin');
    Route::post('/srt_masih_mhw/admin/manajer/cek_surat/setuju/{id}',  [srt_masih_mhwController::class, 'setuju'])->name('srt_masih_mhw.setuju')
    ->middleware('UserAkses:admin');
    Route::post('/srt_masih_mhw/admin/manajer/cek_surat/tolak/{id}',  [srt_masih_mhwController::class, 'tolak'])->name('srt_masih_mhw.tolak')
    ->middleware('UserAkses:admin');

    route::get('/srt_masih_mhw/admin/wd', [srt_masih_mhwController::class, 'wd'])->name('srt_masih_mhw.wd')
    ->middleware('UserAkses:admin');
    Route::get('/srt_masih_mhw/admin/wd/search', [srt_masih_mhwController::class, 'wd'])->name('srt_masih_mhw.admin_wd_search')
    ->middleware('UserAkses:admin');
    Route::get('/srt_masih_mhw/admin/wd/cek_surat/{id}', [srt_masih_mhwController::class, 'wd_cek'])->name('srt_masih_mhw.cek_wd')
    ->middleware('UserAkses:admin');
    Route::post('/srt_masih_mhw/admin/wd/cek_surat/setuju/{id}',  [srt_masih_mhwController::class, 'wd_setuju'])->name('srt_masih_mhw.wd_setuju')
    ->middleware('UserAkses:admin');
    Route::post('/srt_masih_mhw/admin/wd/cek_surat/tolak/{id}',  [srt_masih_mhwController::class, 'wd_tolak'])->name('srt_masih_mhw.wd_tolak')
    ->middleware('UserAkses:admin');
    // Route::post('/srt_masih_mhw/manajer_wd/unggah/{id}', [srt_masih_mhwController::class, 'wd_unggah'])->name('srt_masih_mhw.wd_unggah')->
    // middleware('UserAkses:admin');
    // Route::get('/srt_masih_mhw/manajer_wd/download/{id}', [srt_masih_mhwController::class, 'wd_unduh'])->name('srt_masih_mhw.admin_download_wd')->
    // middleware('UserAkses:admin');

    route::get('/srt_masih_mhw/supervisor', [srt_masih_mhwController::class, 'supervisor'])->name('srt_masih_mhw.supervisor')
    ->middleware('UserAkses:supervisor_akd');
    Route::get('/srt_masih_mhw/supervisor/search', [srt_masih_mhwController::class, 'supervisor'])->name('srt_masih_mhw.sv_search')
    ->middleware('UserAkses:supervisor_akd');
    Route::get('/srt_masih_mhw/supervisor/cek_surat/{id}', [srt_masih_mhwController::class, 'cek_sv'])->name('srt_masih_mhw.cek_sv')
    ->middleware('UserAkses:supervisor_akd');
    Route::post('/srt_masih_mhw/supervisor/cek_surat/setuju/{id}',  [srt_masih_mhwController::class, 'setuju_sv'])->name('srt_masih_mhw.setuju_sv')
    ->middleware('UserAkses:supervisor_akd');
    Route::post('/srt_masih_mhw/supervisor/cek_surat/tolak/{id}',  [srt_masih_mhwController::class, 'tolak_sv'])->name('srt_masih_mhw.tolak_sv')
    ->middleware('UserAkses:supervisor_akd');

    route::get('/srt_masih_mhw/manajer', [srt_masih_mhwController::class, 'manajer'])->name('srt_masih_mhw.manajer')
    ->middleware('UserAkses:manajer');
    Route::get('/srt_masih_mhw/manajer/search', [srt_masih_mhwController::class, 'manajer'])->name('srt_masih_mhw.manajer_search')
    ->middleware('UserAkses:manajer');
    Route::get('/srt_masih_mhw/manajer/cek_surat/{id}', [srt_masih_mhwController::class, 'cek_manajer'])->name('srt_masih_mhw.cek_manajer')
    ->middleware('UserAkses:manajer');
    Route::post('/srt_masih_mhw/manajer/cek_surat/setuju/{id}',  [srt_masih_mhwController::class, 'setuju_manajer'])->name('srt_masih_mhw.setuju_manajer')
    ->middleware('UserAkses:manajer');
    Route::post('/srt_masih_mhw/manajer/cek_surat/tolak/{id}',  [srt_masih_mhwController::class, 'tolak_manajer'])->name('srt_masih_mhw.tolak_manajer')
    ->middleware('UserAkses:manajer');
    Route::get('/srt_masih_mhw/manajer/wd/cek_surat/{id}', [srt_masih_mhwController::class, 'cek_manajer_wd'])->name('srt_masih_mhw.cek_manajer_wd')
    ->middleware('UserAkses:manajer');
    Route::post('/srt_masih_mhw/manajer/wd/cek_surat/setuju/{id}',  [srt_masih_mhwController::class, 'setuju_manajer_wd'])->name('srt_masih_mhw.setuju_manajer_wd')
    ->middleware('UserAkses:manajer');
    Route::post('/srt_masih_mhw/manajer/wd/cek_surat/tolak/{id}',  [srt_masih_mhwController::class, 'tolak_manajer_wd'])->name('srt_masih_mhw.tolak_manajer_wd')
    ->middleware('UserAkses:manajer');

    route::get('/srt_masih_mhw/wd1', [srt_masih_mhwController::class, 'wd1'])->name('srt_masih_mhw.wd1')
    ->middleware('UserAkses:wd1');
    Route::get('/srt_masih_mhw/wd1/search', [srt_masih_mhwController::class, 'wd1'])->name('srt_masih_mhw.wd1_search')
    ->middleware('UserAkses:wd1');
    Route::get('/srt_masih_mhw/wd1/cek_surat/{id}', [srt_masih_mhwController::class, 'cek_wd1'])->name('srt_masih_mhw.cek_wd1')
    ->middleware('UserAkses:wd1');
    Route::post('/srt_masih_mhw/wd1/cek_surat/setuju/{id}',  [srt_masih_mhwController::class, 'setuju_wd1'])->name('srt_masih_mhw.setuju_wd1')
    ->middleware('UserAkses:wd1');
    Route::post('/srt_masih_mhw/wd1/cek_surat/tolak/{id}',  [srt_masih_mhwController::class, 'tolak_wd1'])->name('srt_masih_mhw.tolak_wd1')
    ->middleware('UserAkses:wd1');

    // Surat Magang

    route::get('/srt_magang', [Srt_Magang_Controller::class, 'index'])->name('srt_magang.index')
    ->middleware('UserAkses:mahasiswa');
    Route::post('/srt_magang/create', [Srt_Magang_Controller::class,'create'])->name('srt_magang.store')
    ->middleware('UserAkses:mahasiswa');
    Route::get('/srt_magang/search', [Srt_Magang_Controller::class, 'index'])->name('srt_magang.search')
    ->middleware('UserAkses:mahasiswa');
    Route::get('/srt_magang/edit/{id}', [Srt_Magang_Controller::class, 'edit'])->name('srt_magang.edit')->
    middleware('UserAkses:mahasiswa');
    Route::post('/srt_magang/update/{id}', [Srt_Magang_Controller::class, 'update'])->name('srt_magang.update')->
    middleware('UserAkses:mahasiswa');
    Route::get('/srt_magang/download/{id}', [Srt_Magang_Controller::class, 'download'])->name('srt_magang.download')->
    middleware('UserAkses:mahasiswa');

    route::get('/srt_magang/admin', [Srt_Magang_Controller::class, 'admin'])->name('srt_magang.admin')
    ->middleware('UserAkses:admin');
    Route::get('/srt_magang/admin/search', [Srt_Magang_Controller::class, 'admin'])->name('srt_magang.admin_search')
    ->middleware('UserAkses:admin');
    Route::get('/srt_magang/admin/cek_surat/{id}', [Srt_Magang_Controller::class, 'admin_cek'])->name('srt_magang.cek')
    ->middleware('UserAkses:admin');
    Route::post('/srt_magang/admin/cek_surat/setuju/{id}',  [Srt_Magang_Controller::class, 'admin_setuju'])->name('srt_magang.admin_setuju')
    ->middleware('UserAkses:admin');
    Route::post('/srt_magang/admin/cek_surat/tolak/{id}',  [Srt_Magang_Controller::class, 'admin_tolak'])->name('srt_magang.admin_tolak')
    ->middleware('UserAkses:admin');
    // Route::post('/srt_magang/admin/unggah/{id}', [Srt_Magang_Controller::class, 'admin_unggah'])->name('srt_magang.admin_unggah')->
    // middleware('UserAkses:admin');
    // Route::get('/srt_magang/admin/download/{id}', [Srt_Magang_Controller::class, 'admin_unduh'])->name('srt_magang.admin_download')->
    // middleware('UserAkses:admin');

    route::get('/srt_magang/supervisor', [Srt_Magang_Controller::class, 'supervisor'])->name('srt_magang.supervisor')
    ->middleware('UserAkses:supervisor_akd');
    Route::get('/srt_magang/supervisor/search', [Srt_Magang_Controller::class, 'supervisor'])->name('srt_magang.sv_search')
    ->middleware('UserAkses:supervisor_akd');
    Route::get('/srt_magang/supervisor/cek_surat/{id}', [Srt_Magang_Controller::class, 'cek_sv'])->name('srt_magang.cek_sv')
    ->middleware('UserAkses:supervisor_akd');
    Route::post('/srt_magang/supervisor/cek_surat/setuju/{id}',  [Srt_Magang_Controller::class, 'setuju_sv'])->name('srt_magang.setuju_sv')
    ->middleware('UserAkses:supervisor_akd');
    Route::post('/srt_magang/supervisor/cek_surat/tolak/{id}',  [Srt_Magang_Controller::class, 'tolak_sv'])->name('srt_magang.tolak_sv')
    ->middleware('UserAkses:supervisor_akd');

    route::get('/srt_magang/manajer', [Srt_Magang_Controller::class, 'manajer'])->name('srt_magang.manajer')
    ->middleware('UserAkses:manajer');
    Route::get('/srt_magang/manajer/search', [Srt_Magang_Controller::class, 'manajer'])->name('srt_magang.manajer_search')
    ->middleware('UserAkses:manajer');
    Route::get('/srt_magang/manajer/cek_surat/{id}', [Srt_Magang_Controller::class, 'cek_manajer'])->name('srt_magang.cek_manajer')
    ->middleware('UserAkses:manajer');
    Route::post('/srt_magang/manajer/cek_surat/setuju/{id}',  [Srt_Magang_Controller::class, 'setuju_manajer'])->name('srt_magang.setuju_manajer')
    ->middleware('UserAkses:manajer');
    Route::post('/srt_magang/manajer/cek_surat/tolak/{id}',  [Srt_Magang_Controller::class, 'tolak_manajer'])->name('srt_magang.tolak_manajer')
    ->middleware('UserAkses:manajer');

    route::get('/srt_magang/wd1', [Srt_Magang_Controller::class, 'wd1'])->name('srt_magang.wd1')
    ->middleware('UserAkses:wd1');
    Route::get('/srt_magang/wd1/search', [Srt_Magang_Controller::class, 'wd1'])->name('srt_magang.wd1_search')
    ->middleware('UserAkses:wd1');
    Route::get('/srt_magang/wd1/cek_surat/{id}', [Srt_Magang_Controller::class, 'cek_wd1'])->name('srt_magang.cek_wd1')
    ->middleware('UserAkses:wd1');
    Route::post('/srt_magang/wd1/cek_surat/setuju/{id}',  [Srt_Magang_Controller::class, 'setuju_wd1'])->name('srt_magang.setuju_wd1')
    ->middleware('UserAkses:wd1');
    Route::post('/srt_magang/wd1/cek_surat/tolak/{id}',  [Srt_Magang_Controller::class, 'tolak_wd1'])->name('srt_magang.tolak_wd1')
    ->middleware('UserAkses:wd1');

    // Surat Izin Penelitian

    route::get('/srt_izin_plt', [Srt_Izin_Penelitian_Controller::class, 'index'])->name('srt_izin_plt.index')
    ->middleware('UserAkses:mahasiswa');
    Route::post('/srt_izin_plt/create', [Srt_Izin_Penelitian_Controller::class,'create'])->name('srt_izin_plt.store')
    ->middleware('UserAkses:mahasiswa');
    Route::get('/srt_izin_plt/search', [Srt_Izin_Penelitian_Controller::class, 'index'])->name('srt_izin_plt.search')
    ->middleware('UserAkses:mahasiswa');
    Route::get('/srt_izin_plt/edit/{id}', [Srt_Izin_Penelitian_Controller::class, 'edit'])->name('srt_izin_plt.edit')->
    middleware('UserAkses:mahasiswa');
    Route::post('/srt_izin_plt/update/{id}', [Srt_Izin_Penelitian_Controller::class, 'update'])->name('srt_izin_plt.update')->
    middleware('UserAkses:mahasiswa');
    Route::get('/srt_izin_plt/download/{id}', [Srt_Izin_Penelitian_Controller::class, 'download'])->name('srt_izin_plt.download')->
    middleware('UserAkses:mahasiswa');

    route::get('/srt_izin_plt/admin', [Srt_Izin_Penelitian_Controller::class, 'admin'])->name('srt_izin_plt.admin')
    ->middleware('UserAkses:admin');
    Route::get('/srt_izin_plt/admin/search', [Srt_Izin_Penelitian_Controller::class, 'admin'])->name('srt_izin_plt.admin_search')
    ->middleware('UserAkses:admin');
    Route::get('/srt_izin_plt/admin/cek_surat/{id}', [Srt_Izin_Penelitian_Controller::class, 'admin_cek'])->name('srt_izin_plt.cek')
    ->middleware('UserAkses:admin');
    Route::post('/srt_izin_plt/admin/cek_surat/setuju/{id}',  [Srt_Izin_Penelitian_Controller::class, 'admin_setuju'])->name('srt_izin_plt.admin_setuju')
    ->middleware('UserAkses:admin');
    Route::post('/srt_izin_plt/admin/cek_surat/tolak/{id}',  [Srt_Izin_Penelitian_Controller::class, 'admin_tolak'])->name('srt_izin_plt.admin_tolak')
    ->middleware('UserAkses:admin');
    // Route::post('/srt_izin_plt/admin/unggah/{id}', [Srt_Izin_Penelitian_Controller::class, 'admin_unggah'])->name('srt_izin_plt.admin_unggah')->
    // middleware('UserAkses:admin');
    // Route::get('/srt_izin_plt/admin/download/{id}', [Srt_Izin_Penelitian_Controller::class, 'admin_unduh'])->name('srt_izin_plt.admin_download')->
    // middleware('UserAkses:admin');

    route::get('/srt_izin_plt/supervisor', [Srt_Izin_Penelitian_Controller::class, 'supervisor'])->name('srt_izin_plt.supervisor')
    ->middleware('UserAkses:supervisor_akd');
    Route::get('/srt_izin_plt/supervisor/search', [Srt_Izin_Penelitian_Controller::class, 'supervisor'])->name('srt_izin_plt.sv_search')
    ->middleware('UserAkses:supervisor_akd');
    Route::get('/srt_izin_plt/supervisor/cek_surat/{id}', [Srt_Izin_Penelitian_Controller::class, 'cek_sv'])->name('srt_izin_plt.cek_sv')
    ->middleware('UserAkses:supervisor_akd');
    Route::post('/srt_izin_plt/supervisor/cek_surat/setuju/{id}',  [Srt_Izin_Penelitian_Controller::class, 'setuju_sv'])->name('srt_izin_plt.setuju_sv')
    ->middleware('UserAkses:supervisor_akd');
    Route::post('/srt_izin_plt/supervisor/cek_surat/tolak/{id}',  [Srt_Izin_Penelitian_Controller::class, 'tolak_sv'])->name('srt_izin_plt.tolak_sv')
    ->middleware('UserAkses:supervisor_akd');

    route::get('/srt_izin_plt/manajer', [Srt_Izin_Penelitian_Controller::class, 'manajer'])->name('srt_izin_plt.manajer')
    ->middleware('UserAkses:manajer');
    Route::get('/srt_izin_plt/manajer/search', [Srt_Izin_Penelitian_Controller::class, 'manajer'])->name('srt_izin_plt.manajer_search')
    ->middleware('UserAkses:manajer');
    Route::get('/srt_izin_plt/manajer/cek_surat/{id}', [Srt_Izin_Penelitian_Controller::class, 'cek_manajer'])->name('srt_izin_plt.cek_manajer')
    ->middleware('UserAkses:manajer');
    Route::post('/srt_izin_plt/manajer/cek_surat/setuju/{id}',  [Srt_Izin_Penelitian_Controller::class, 'setuju_manajer'])->name('srt_izin_plt.setuju_manajer')
    ->middleware('UserAkses:manajer');
    Route::post('/srt_izin_plt/manajer/cek_surat/tolak/{id}',  [Srt_Izin_Penelitian_Controller::class, 'tolak_manajer'])->name('srt_izin_plt.tolak_manajer')
    ->middleware('UserAkses:manajer');

    route::get('/srt_izin_plt/wd1', [Srt_Izin_Penelitian_Controller::class, 'wd1'])->name('srt_izin_plt.wd1')
    ->middleware('UserAkses:wd1');
    Route::get('/srt_izin_plt/wd1/search', [Srt_Izin_Penelitian_Controller::class, 'wd1'])->name('srt_izin_plt.wd1_search')
    ->middleware('UserAkses:wd1');
    Route::get('/srt_izin_plt/wd1/cek_surat/{id}', [Srt_Izin_Penelitian_Controller::class, 'cek_wd1'])->name('srt_izin_plt.cek_wd1')
    ->middleware('UserAkses:wd1');
    Route::post('/srt_izin_plt/wd1/cek_surat/setuju/{id}',  [Srt_Izin_Penelitian_Controller::class, 'setuju_wd1'])->name('srt_izin_plt.setuju_wd1')
    ->middleware('UserAkses:wd1');
    Route::post('/srt_izin_plt/wd1/cek_surat/tolak/{id}',  [Srt_Izin_Penelitian_Controller::class, 'tolak_wd1'])->name('srt_izin_plt.tolak_wd1')
    ->middleware('UserAkses:wd1');

    // Surat Pengembalian Biaya

    route::get('/srt_pmhn_kmbali_biaya', [srt_pmhn_kmbali_biaya_controller::class, 'index'])->name('srt_pmhn_kmbali_biaya.index')
    ->middleware('UserAkses:mahasiswa');
    Route::post('/srt_pmhn_kmbali_biaya/create', [srt_pmhn_kmbali_biaya_controller::class,'create'])->name('srt_pmhn_kmbali_biaya.store')
    ->middleware('UserAkses:mahasiswa');
    Route::get('/srt_pmhn_kmbali_biaya/search', [srt_pmhn_kmbali_biaya_controller::class, 'index'])->name('srt_pmhn_kmbali_biaya.search')
    ->middleware('UserAkses:mahasiswa');
    Route::get('/srt_pmhn_kmbali_biaya/edit/{id}', [srt_pmhn_kmbali_biaya_controller::class, 'edit'])->name('srt_pmhn_kmbali_biaya.edit')->
    middleware('UserAkses:mahasiswa');
    Route::post('/srt_pmhn_kmbali_biaya/update/{id}', [srt_pmhn_kmbali_biaya_controller::class, 'update'])->name('srt_pmhn_kmbali_biaya.update')->
    middleware('UserAkses:mahasiswa');
    Route::get('/srt_pmhn_kmbali_biaya/download/{id}', [srt_pmhn_kmbali_biaya_controller::class, 'download'])->name('srt_pmhn_kmbali_biaya.download')->
    middleware('UserAkses:mahasiswa');

    route::get('/srt_pmhn_kmbali_biaya/admin', [srt_pmhn_kmbali_biaya_controller::class, 'admin'])->name('srt_pmhn_kmbali_biaya.admin')
    ->middleware('UserAkses:admin');
    Route::get('/srt_pmhn_kmbali_biaya/admin/search', [srt_pmhn_kmbali_biaya_controller::class, 'admin'])->name('srt_pmhn_kmbali_biaya.admin_search')
    ->middleware('UserAkses:admin');
    Route::get('/srt_pmhn_kmbali_biaya/admin/cek_surat/{id}', [srt_pmhn_kmbali_biaya_controller::class, 'admin_cek'])->name('srt_pmhn_kmbali_biaya.cek')
    ->middleware('UserAkses:admin');
    Route::post('/srt_pmhn_kmbali_biaya/admin/cek_surat/setuju/{id}',  [srt_pmhn_kmbali_biaya_controller::class, 'admin_setuju'])->name('srt_pmhn_kmbali_biaya.admin_setuju')
    ->middleware('UserAkses:admin');
    Route::post('/srt_pmhn_kmbali_biaya/admin/cek_surat/tolak/{id}',  [srt_pmhn_kmbali_biaya_controller::class, 'admin_tolak'])->name('srt_pmhn_kmbali_biaya.admin_tolak')
    ->middleware('UserAkses:admin');
    // Route::post('/srt_pmhn_kmbali_biaya/admin/unggah/{id}', [srt_pmhn_kmbali_biaya_controller::class, 'admin_unggah'])->name('srt_pmhn_kmbali_biaya.admin_unggah')->
    // middleware('UserAkses:admin');
    // Route::get('/srt_pmhn_kmbali_biaya/admin/download/{id}', [srt_pmhn_kmbali_biaya_controller::class, 'admin_unduh'])->name('srt_pmhn_kmbali_biaya.admin_download')->
    // middleware('UserAkses:admin');

    route::get('/srt_pmhn_kmbali_biaya/supervisor', [srt_pmhn_kmbali_biaya_controller::class, 'supervisor'])->name('srt_pmhn_kmbali_biaya.supervisor')
    ->middleware('UserAkses:supervisor_sd');
    Route::get('/srt_pmhn_kmbali_biaya/supervisor/search', [srt_pmhn_kmbali_biaya_controller::class, 'supervisor'])->name('srt_pmhn_kmbali_biaya.sv_search')
    ->middleware('UserAkses:supervisor_sd');
    Route::get('/srt_pmhn_kmbali_biaya/supervisor/cek_surat/{id}', [srt_pmhn_kmbali_biaya_controller::class, 'sv_cek'])->name('srt_pmhn_kmbali_biaya.sv_cek')
    ->middleware('UserAkses:supervisor_sd');
    Route::post('/srt_pmhn_kmbali_biaya/supervisor/cek_surat/setuju/{id}',  [srt_pmhn_kmbali_biaya_controller::class, 'sv_setuju'])->name('srt_pmhn_kmbali_biaya.sv_setuju')
    ->middleware('UserAkses:supervisor_sd');
    Route::post('/srt_pmhn_kmbali_biaya/supervisor/cek_surat/tolak/{id}',  [srt_pmhn_kmbali_biaya_controller::class, 'sv_tolak'])->name('srt_pmhn_kmbali_biaya.sv_tolak')
    ->middleware('UserAkses:supervisor_sd');

    route::get('/srt_pmhn_kmbali_biaya/manajer', [srt_pmhn_kmbali_biaya_controller::class, 'manajer'])->name('srt_pmhn_kmbali_biaya.manajer')
    ->middleware('UserAkses:manajer');
    Route::get('/srt_pmhn_kmbali_biaya/manajer/search', [srt_pmhn_kmbali_biaya_controller::class, 'manajer'])->name('srt_pmhn_kmbali_biaya.manajer_search')
    ->middleware('UserAkses:manajer');
    Route::get('/srt_pmhn_kmbali_biaya/manajer/cek_surat/{id}', [srt_pmhn_kmbali_biaya_controller::class, 'manajer_cek'])->name('srt_pmhn_kmbali_biaya.manajer_cek')
    ->middleware('UserAkses:manajer');
    Route::post('/srt_pmhn_kmbali_biaya/manajer/cek_surat/setuju/{id}',  [srt_pmhn_kmbali_biaya_controller::class, 'manajer_setuju'])->name('srt_pmhn_kmbali_biaya.manajer_setuju')
    ->middleware('UserAkses:manajer');
    Route::post('/srt_pmhn_kmbali_biaya/manajer/cek_surat/tolak/{id}',  [srt_pmhn_kmbali_biaya_controller::class, 'manajer_tolak'])->name('srt_pmhn_kmbali_biaya.manajer_tolak')
    ->middleware('UserAkses:manajer');

    route::get('/srt_pmhn_kmbali_biaya/wd2', [srt_pmhn_kmbali_biaya_controller::class, 'wd2'])->name('srt_pmhn_kmbali_biaya.wd2')
    ->middleware('UserAkses:wd2');
    Route::get('/srt_pmhn_kmbali_biaya/wd2/search', [srt_pmhn_kmbali_biaya_controller::class, 'wd2'])->name('srt_pmhn_kmbali_biaya.wd2_search')
    ->middleware('UserAkses:wd2');
    Route::get('/srt_pmhn_kmbali_biaya/wd2/cek_surat/{id}', [srt_pmhn_kmbali_biaya_controller::class, 'wd2_cek'])->name('srt_pmhn_kmbali_biaya.wd2_cek')
    ->middleware('UserAkses:wd2');
    Route::post('/srt_pmhn_kmbali_biaya/wd2/cek_surat/setuju/{id}',  [srt_pmhn_kmbali_biaya_controller::class, 'wd2_setuju'])->name('srt_pmhn_kmbali_biaya.wd2_setuju')
    ->middleware('UserAkses:wd2');
    Route::post('/srt_pmhn_kmbali_biaya/wd2/cek_surat/tolak/{id}',  [srt_pmhn_kmbali_biaya_controller::class, 'wd2_tolak'])->name('srt_pmhn_kmbali_biaya.wd2_tolak')
    ->middleware('UserAkses:wd2');

    // Surat Bebas Pinjam

    route::get('/srt_bbs_pnjm', [Srt_Bbs_Pnjam_Controller::class, 'index'])->name('srt_bbs_pnjm.index')
    ->middleware('UserAkses:mahasiswa');
    Route::post('/srt_bbs_pnjm/create', [Srt_Bbs_Pnjam_Controller::class,'create'])->name('srt_bbs_pnjm.store')
    ->middleware('UserAkses:mahasiswa');
    Route::get('/srt_bbs_pnjm/search', [Srt_Bbs_Pnjam_Controller::class, 'index'])->name('srt_bbs_pnjm.search')
    ->middleware('UserAkses:mahasiswa');
    Route::get('/srt_bbs_pnjm/edit/{id}', [Srt_Bbs_Pnjam_Controller::class, 'edit'])->name('srt_bbs_pnjm.edit')->
    middleware('UserAkses:mahasiswa');
    Route::post('/srt_bbs_pnjm/update/{id}', [Srt_Bbs_Pnjam_Controller::class, 'update'])->name('srt_bbs_pnjm.update')->
    middleware('UserAkses:mahasiswa');
    Route::get('/srt_bbs_pnjm/download/{id}', [Srt_Bbs_Pnjam_Controller::class, 'download'])->name('srt_bbs_pnjm.download')->
    middleware('UserAkses:mahasiswa');

    route::get('/srt_bbs_pnjm/admin', [Srt_Bbs_Pnjam_Controller::class, 'admin'])->name('srt_bbs_pnjm.admin')
    ->middleware('UserAkses:admin');
    Route::get('/srt_bbs_pnjm/admin/search', [Srt_Bbs_Pnjam_Controller::class, 'admin'])->name('srt_bbs_pnjm.admin_search')
    ->middleware('UserAkses:admin');
    Route::get('/srt_bbs_pnjm/admin/cek_surat/{id}', [Srt_Bbs_Pnjam_Controller::class, 'admin_cek'])->name('srt_bbs_pnjm.cek')
    ->middleware('UserAkses:admin');
    Route::post('/srt_bbs_pnjm/admin/cek_surat/setuju/{id}',  [Srt_Bbs_Pnjam_Controller::class, 'admin_setuju'])->name('srt_bbs_pnjm.admin_setuju')
    ->middleware('UserAkses:admin');
    Route::post('/srt_bbs_pnjm/admin/cek_surat/tolak/{id}',  [Srt_Bbs_Pnjam_Controller::class, 'admin_tolak'])->name('srt_bbs_pnjm.admin_tolak')
    ->middleware('UserAkses:admin');

    route::get('/srt_bbs_pnjm/supervisor', [Srt_Bbs_Pnjam_Controller::class, 'supervisor'])->name('srt_bbs_pnjm.supervisor')
    ->middleware('UserAkses:supervisor_sd');
    Route::get('/srt_bbs_pnjm/supervisor/search', [Srt_Bbs_Pnjam_Controller::class, 'supervisor'])->name('srt_bbs_pnjm.sv_search')
    ->middleware('UserAkses:supervisor_sd');
    Route::get('/srt_bbs_pnjm/supervisor/cek_surat/{id}', [Srt_Bbs_Pnjam_Controller::class, 'sv_cek'])->name('srt_bbs_pnjm.sv_cek')
    ->middleware('UserAkses:supervisor_sd');
    Route::post('/srt_bbs_pnjm/supervisor/setuju/{id}',  [Srt_Bbs_Pnjam_Controller::class, 'sv_setuju'])->name('srt_bbs_pnjm.sv_setuju')
    ->middleware('UserAkses:supervisor_sd');
    Route::post('/srt_bbs_pnjm/supervisor/cek_surat/tolak/{id}',  [Srt_Bbs_Pnjam_Controller::class, 'sv_tolak'])->name('srt_bbs_pnjm.sv_tolak')
    ->middleware('UserAkses:supervisor_sd');

    // Legalisir

    route::get('/legalisir', [Legalisir_Controller::class, 'index'])->name('legalisir.index')
    ->middleware('UserAkses:mahasiswa');
    Route::post('/legalisir/create', [Legalisir_Controller::class,'create'])->name('legalisir.store')
    ->middleware('UserAkses:mahasiswa');
    Route::get('/legalisir/search', [Legalisir_Controller::class, 'index'])->name('legalisir.search')
    ->middleware('UserAkses:mahasiswa');
    Route::get('/legalisir/edit/{id}', [Legalisir_Controller::class, 'edit'])->name('legalisir.edit')->
    middleware('UserAkses:mahasiswa');
    Route::post('/legalisir/update/{id}', [Legalisir_Controller::class, 'update'])->name('legalisir.update')->
    middleware('UserAkses:mahasiswa');

    // Kirim Ijazah

    Route::get('/legalisir/admin/dikirim/ijazah', [Admin_Legalisir_Controller::class, 'kirim_ijazah'])->name('legalisir_admin.admin_dikirim_ijazah')->
    middleware('UserAkses:admin');
    Route::get('/legalisir/admin/dikirim/ijazah/search', [Admin_Legalisir_Controller::class, 'kirim_ijazah'])->name('legalisir_admin.admin_dikirim_ijazah_search')->
    middleware('UserAkses:admin');
    Route::get('/legalisir/admin/dikirim/ijazah/download/{id}', [Admin_Legalisir_Controller::class, 'unduh_kirim_ijazah'])->name('legalisir_admin.admin_dikirim_ijazah_download')->
    middleware('UserAkses:admin');
    Route::get('/legalisir/admin/dikirim/ijazah/cek_legal/{id}', [Admin_Legalisir_Controller::class, 'cek_kirim_ijazah'])->name('legalisir_admin.admin_dikirim_ijazah_cek')
    ->middleware('UserAkses:admin');
    Route::post('/legalisir/admin/dikirim/ijazah/cek_legal/setuju/{id}',  [Admin_Legalisir_Controller::class, 'setuju_kirim_ijazah'])->name('legalisir_admin.admin_dikirim_ijazah_setuju')
    ->middleware('UserAkses:admin');
    Route::post('/legalisir/admin/dikirim/ijazah/cek_legal/tolak/{id}',  [Admin_Legalisir_Controller::class, 'tolak_kirim_ijazah'])->name('legalisir_admin.admin_dikirim_ijazah_tolak')
    ->middleware('UserAkses:admin');
    Route::post('/legalisir/admin/dikirim/ijazah/no_resi/{id}',  [Admin_Legalisir_Controller::class, 'resi_kirim_ijazah'])->name('legalisir_admin.admin_dikirim_ijazah_resi')
    ->middleware('UserAkses:admin');

    route::get('/legalisir/sv/dikirim/ijazah', [Supervisor_Legalisir_Controller::class, 'kirim_ijazah'])->name('legalisir_sv.sv_dikirim_ijazah')
    ->middleware('UserAkses:supervisor_akd');
    Route::get('/legalisir/sv/dikirim/ijazah/search', [Supervisor_Legalisir_Controller::class, 'kirim_ijazah'])->name('legalisir_sv.sv_dikirim_ijazah_search')
    ->middleware('UserAkses:supervisor_akd');
    Route::get('/legalisir/sv/dikirim/ijazah/cek_legal/{id}', [Supervisor_Legalisir_Controller::class, 'cek_sv_dikirim_ijazah'])->name('legalisir_sv.cek_sv_dikirim_ijazah')
    ->middleware('UserAkses:supervisor_akd');
    Route::post('/legalisir/sv/dikirim/ijazah/cek_legal/setuju/{id}',  [Supervisor_Legalisir_Controller::class, 'setuju_sv_dikirim_ijazah'])->name('legalisir_sv.setuju_sv_dikirim_ijazah')
    ->middleware('UserAkses:supervisor_akd');
    Route::post('/legalisir/sv/dikirim/ijazah/cek_legal/tolak/{id}',  [Supervisor_Legalisir_Controller::class, 'tolak_sv_dikirim_ijazah'])->name('legalisir_sv.tolak_sv_dikirim_ijazah')
    ->middleware('UserAkses:supervisor_akd');

    // route::get('/legalisir/manajer/dikirim/ijazah', [Manajer_Legalisir_Controller::class, 'kirim_ijazah'])->name('legalisir_manajer.manajer_dikirim_ijazah')
    // ->middleware('UserAkses:manajer');
    // Route::get('/legalisir/manajer/dikirim/ijazah/search', [Manajer_Legalisir_Controller::class, 'kirim_ijazah'])->name('legalisir_manajer.manajer_dikirim_ijazah_search')
    // ->middleware('UserAkses:manajer');
    // Route::post('/legalisir/manajer/dikirim/ijazah/setuju/{id}',  [Manajer_Legalisir_Controller::class, 'setuju_kirim_ijazah'])->name('legalisir_manajer.manajer_dikirim_ijazah_setuju')
    // ->middleware('UserAkses:manajer');

    // Kirim Transkrip

    Route::get('/legalisir/admin/dikirim/transkrip', [Admin_Legalisir_Controller::class, 'kirim_transkrip'])->name('legalisir_admin.admin_dikirim_transkrip')->
    middleware('UserAkses:admin');
    Route::get('/legalisir/admin/dikirim/transkrip/search', [Admin_Legalisir_Controller::class, 'kirim_transkrip'])->name('legalisir_admin.admin_dikirim_transkrip_search')->
    middleware('UserAkses:admin');
    Route::get('/legalisir/admin/dikirim/transkrip/download/{id}', [Admin_Legalisir_Controller::class, 'unduh_kirim_transkrip'])->name('legalisir_admin.admin_dikirim_transkrip_download')->
    middleware('UserAkses:admin');
    Route::get('/legalisir/admin/dikirim/transkrip/cek_legal/{id}', [Admin_Legalisir_Controller::class, 'cek_kirim_transkrip'])->name('legalisir_admin.admin_dikirim_transkrip_cek')
    ->middleware('UserAkses:admin');
    Route::post('/legalisir/admin/dikirim/transkrip/cek_legal/setuju/{id}',  [Admin_Legalisir_Controller::class, 'setuju_kirim_transkrip'])->name('legalisir_admin.admin_dikirim_transkrip_setuju')
    ->middleware('UserAkses:admin');
    Route::post('/legalisir/admin/dikirim/transkrip/cek_legal/tolak/{id}',  [Admin_Legalisir_Controller::class, 'tolak_kirim_transkrip'])->name('legalisir_admin.admin_dikirim_transkrip_tolak')
    ->middleware('UserAkses:admin');
    Route::post('/legalisir/admin/dikirim/transkrip/no_resi/{id}',  [Admin_Legalisir_Controller::class, 'resi_kirim_transkrip'])->name('legalisir_admin.admin_dikirim_transkrip_resi')
    ->middleware('UserAkses:admin');

    route::get('/legalisir/sv/dikirim/transkrip', [Supervisor_Legalisir_Controller::class, 'kirim_transkrip'])->name('legalisir_sv.sv_dikirim_transkrip')
    ->middleware('UserAkses:supervisor_akd');
    Route::get('/legalisir/sv/dikirim/transkrip/search', [Supervisor_Legalisir_Controller::class, 'kirim_transkrip'])->name('legalisir_sv.sv_dikirim_transkrip_search')
    ->middleware('UserAkses:supervisor_akd');
    Route::get('/legalisir/sv/dikirim/transkrip/cek_legal/{id}', [Supervisor_Legalisir_Controller::class, 'cek_sv_dikirim_transkrip'])->name('legalisir_sv.cek_sv_dikirim_transkrip')
    ->middleware('UserAkses:supervisor_akd');
    Route::post('/legalisir/sv/dikirim/transkrip/cek_legal/setuju/{id}',  [Supervisor_Legalisir_Controller::class, 'setuju_sv_dikirim_transkrip'])->name('legalisir_sv.setuju_sv_dikirim_transkrip')
    ->middleware('UserAkses:supervisor_akd');
    Route::post('/legalisir/sv/dikirim/transkrip/cek_legal/tolak/{id}',  [Supervisor_Legalisir_Controller::class, 'tolak_sv_dikirim_transkrip'])->name('legalisir_sv.tolak_sv_dikirim_transkrip')
    ->middleware('UserAkses:supervisor_akd');

    // route::get('/legalisir/manajer/dikirim/transkrip', [Manajer_Legalisir_Controller::class, 'kirim_transkrip'])->name('legalisir_manajer.manajer_dikirim_transkrip')
    // ->middleware('UserAkses:manajer');
    // Route::get('/legalisir/manajer/dikirim/transkrip/search', [Manajer_Legalisir_Controller::class, 'kirim_transkrip'])->name('legalisir_manajer.manajer_dikirim_transkrip_search')
    // ->middleware('UserAkses:manajer');
    // Route::post('/legalisir/manajer/dikirim/transkrip/setuju/{id}',  [Manajer_Legalisir_Controller::class, 'setuju_kirim_transkrip'])->name('legalisir_manajer.manajer_dikirim_transkrip_setuju')
    // ->middleware('UserAkses:manajer');

    // Kirim Ijazah dan Transkrip

    Route::get('/legalisir/admin/dikirim/ijz_trs', [Admin_Legalisir_Controller::class, 'kirim_ijz_trs'])->name('legalisir_admin.admin_dikirim_ijz_trs')->
    middleware('UserAkses:admin');
    Route::get('/legalisir/admin/dikirim/ijz_trs/search', [Admin_Legalisir_Controller::class, 'kirim_ijz_trs'])->name('legalisir_admin.admin_dikirim_ijz_trs_search')->
    middleware('UserAkses:admin');
    Route::get('/legalisir/admin/dikirim/ijz_trs/download/{id}', [Admin_Legalisir_Controller::class, 'unduh_kirim_ijz_trs'])->name('legalisir_admin.admin_dikirim_ijz_trs_download')->
    middleware('UserAkses:admin');
    Route::get('/legalisir/admin/dikirim/ijz_trs/cek_legal/{id}', [Admin_Legalisir_Controller::class, 'cek_kirim_ijz_trs'])->name('legalisir_admin.admin_dikirim_ijz_trs_cek')
    ->middleware('UserAkses:admin');
    Route::post('/legalisir/admin/dikirim/ijz_trs/cek_legal/setuju/{id}',  [Admin_Legalisir_Controller::class, 'setuju_kirim_ijz_trs'])->name('legalisir_admin.admin_dikirim_ijz_trs_setuju')
    ->middleware('UserAkses:admin');
    Route::post('/legalisir/admin/dikirim/ijz_trs/cek_legal/tolak/{id}',  [Admin_Legalisir_Controller::class, 'tolak_kirim_ijz_trs'])->name('legalisir_admin.admin_dikirim_ijz_trs_tolak')
    ->middleware('UserAkses:admin');
    Route::post('/legalisir/admin/dikirim/ijz_trs/no_resi/{id}',  [Admin_Legalisir_Controller::class, 'resi_kirim_ijz_trs'])->name('legalisir_admin.admin_dikirim_ijz_trs_resi')
    ->middleware('UserAkses:admin');

    route::get('/legalisir/sv/dikirim/ijz_trs', [Supervisor_Legalisir_Controller::class, 'kirim_ijz_trs'])->name('legalisir_sv.sv_dikirim_ijz_trs')
    ->middleware('UserAkses:supervisor_akd');
    Route::get('/legalisir/sv/dikirim/ijz_trs/search', [Supervisor_Legalisir_Controller::class, 'kirim_ijz_trs'])->name('legalisir_sv.sv_dikirim_ijz_trs_search')
    ->middleware('UserAkses:supervisor_akd');
    Route::get('/legalisir/sv/dikirim/ijz_trs/cek_legal/{id}', [Supervisor_Legalisir_Controller::class, 'cek_sv_dikirim_ijz_trs'])->name('legalisir_sv.cek_sv_dikirim_ijz_trs')
    ->middleware('UserAkses:supervisor_akd');
    Route::post('/legalisir/sv/dikirim/ijz_trs/cek_legal/setuju/{id}',  [Supervisor_Legalisir_Controller::class, 'setuju_sv_dikirim_ijz_trs'])->name('legalisir_sv.setuju_sv_dikirim_ijz_trs')
    ->middleware('UserAkses:supervisor_akd');
    Route::post('/legalisir/sv/dikirim/ijz_trs/cek_legal/tolak/{id}',  [Supervisor_Legalisir_Controller::class, 'tolak_sv_dikirim_ijz_trs'])->name('legalisir_sv.tolak_sv_dikirim_ijz_trs')
    ->middleware('UserAkses:supervisor_akd');

    // route::get('/legalisir/manajer/dikirim/ijz_trs', [Manajer_Legalisir_Controller::class, 'kirim_ijz_trs'])->name('legalisir_manajer.manajer_dikirim_ijz_trs')
    // ->middleware('UserAkses:manajer');
    // Route::get('/legalisir/manajer/dikirim/ijz_trs/search', [Manajer_Legalisir_Controller::class, 'kirim_ijz_trs'])->name('legalisir_manajer.manajer_dikirim_ijz_trs_search')
    // ->middleware('UserAkses:manajer');
    // Route::post('/legalisir/manajer/dikirim/ijz_trs/setuju/{id}',  [Manajer_Legalisir_Controller::class, 'setuju_kirim_ijz_trs'])->name('legalisir_manajer.manajer_dikirim_ijz_trs_setuju')
    // ->middleware('UserAkses:manajer');

    // Ditempat Ijazah

    Route::get('/legalisir/admin/ditempat/ijazah', [Admin_Legalisir_Controller::class, 'ditempat_ijazah'])->name('legalisir_admin.admin_ditempat_ijazah')->
    middleware('UserAkses:admin');
    Route::get('/legalisir/admin/ditempat/ijazah/search', [Admin_Legalisir_Controller::class, 'ditempat_ijazah'])->name('legalisir_admin.admin_ditempat_ijazah_search')->
    middleware('UserAkses:admin');
    Route::get('/legalisir/admin/ditempat/ijazah/download/{id}', [Admin_Legalisir_Controller::class, 'unduh_ditempat_ijazah'])->name('legalisir_admin.admin_ditempat_ijazah_download')->
    middleware('UserAkses:admin');
    Route::get('/legalisir/admin/ditempat/ijazah/cek_legal/{id}', [Admin_Legalisir_Controller::class, 'cek_ditempat_ijazah'])->name('legalisir_admin.admin_ditempat_ijazah_cek')
    ->middleware('UserAkses:admin');
    Route::post('/legalisir/admin/ditempat/ijazah/cek_legal/setuju/{id}',  [Admin_Legalisir_Controller::class, 'setuju_ditempat_ijazah'])->name('legalisir_admin.admin_ditempat_ijazah_setuju')
    ->middleware('UserAkses:admin');
    Route::post('/legalisir/admin/ditempat/ijazah/cek_legal/tolak/{id}',  [Admin_Legalisir_Controller::class, 'tolak_ditempat_ijazah'])->name('legalisir_admin.admin_ditempat_ijazah_tolak')
    ->middleware('UserAkses:admin');
    Route::post('/legalisir/admin/ditempat/ijazah/no_resi/{id}',  [Admin_Legalisir_Controller::class, 'resi_ditempat_ijazah'])->name('legalisir_admin.admin_ditempat_ijazah_resi')
    ->middleware('UserAkses:admin');

    route::get('/legalisir/sv/ditempat/ijazah', [Supervisor_Legalisir_Controller::class, 'ditempat_ijazah'])->name('legalisir_sv.sv_ditempat_ijazah')
    ->middleware('UserAkses:supervisor_akd');
    Route::get('/legalisir/sv/ditempat/ijazah/search', [Supervisor_Legalisir_Controller::class, 'ditempat_ijazah'])->name('legalisir_sv.sv_ditempat_ijazah_search')
    ->middleware('UserAkses:supervisor_akd');
    Route::get('/legalisir/sv/ditempat/ijazah/cek_legal/{id}', [Supervisor_Legalisir_Controller::class, 'cek_sv_ditempat_ijazah'])->name('legalisir_sv.cek_sv_ditempat_ijazah')
    ->middleware('UserAkses:supervisor_akd');
    Route::post('/legalisir/sv/ditempat/ijazah/cek_legal/setuju/{id}',  [Supervisor_Legalisir_Controller::class, 'setuju_sv_ditempat_ijazah'])->name('legalisir_sv.setuju_sv_ditempat_ijazah')
    ->middleware('UserAkses:supervisor_akd');
    Route::post('/legalisir/sv/ditempat/ijazah/cek_legal/tolak/{id}',  [Supervisor_Legalisir_Controller::class, 'tolak_sv_ditempat_ijazah'])->name('legalisir_sv.tolak_sv_ditempat_ijazah')
    ->middleware('UserAkses:supervisor_akd');

    // route::get('/legalisir/manajer/ditempat/ijazah', [Manajer_Legalisir_Controller::class, 'ditempat_ijazah'])->name('legalisir_manajer.manajer_ditempat_ijazah')
    // ->middleware('UserAkses:manajer');
    // Route::get('/legalisir/manajer/ditempat/ijazah/search', [Manajer_Legalisir_Controller::class, 'ditempat_ijazah'])->name('legalisir_manajer.manajer_ditempat_ijazah_search')
    // ->middleware('UserAkses:manajer');
    // Route::post('/legalisir/manajer/ditempat/ijazah/setuju/{id}',  [Manajer_Legalisir_Controller::class, 'setuju_ditempat_ijazah'])->name('legalisir_manajer.manajer_ditempat_ijazah_setuju')
    // ->middleware('UserAkses:manajer');

    // Ditempat Transkrip

    Route::get('/legalisir/admin/ditempat/transkrip', [Admin_Legalisir_Controller::class, 'ditempat_transkrip'])->name('legalisir_admin.admin_ditempat_transkrip')->
    middleware('UserAkses:admin');
    Route::get('/legalisir/admin/ditempat/transkrip/search', [Admin_Legalisir_Controller::class, 'ditempat_transkrip'])->name('legalisir_admin.admin_ditempat_transkrip_search')->
    middleware('UserAkses:admin');
    Route::get('/legalisir/admin/ditempat/transkrip/download/{id}', [Admin_Legalisir_Controller::class, 'unduh_ditempat_transkrip'])->name('legalisir_admin.admin_ditempat_transkrip_download')->
    middleware('UserAkses:admin');
    Route::get('/legalisir/admin/ditempat/transkrip/cek_legal/{id}', [Admin_Legalisir_Controller::class, 'cek_ditempat_transkrip'])->name('legalisir_admin.admin_ditempat_transkrip_cek')
    ->middleware('UserAkses:admin');
    Route::post('/legalisir/admin/ditempat/transkrip/cek_legal/setuju/{id}',  [Admin_Legalisir_Controller::class, 'setuju_ditempat_transkrip'])->name('legalisir_admin.admin_ditempat_transkrip_setuju')
    ->middleware('UserAkses:admin');
    Route::post('/legalisir/admin/ditempat/transkrip/cek_legal/tolak/{id}',  [Admin_Legalisir_Controller::class, 'tolak_ditempat_transkrip'])->name('legalisir_admin.admin_ditempat_transkrip_tolak')
    ->middleware('UserAkses:admin');
    Route::post('/legalisir/admin/ditempat/transkrip/no_resi/{id}',  [Admin_Legalisir_Controller::class, 'resi_ditempat_transkrip'])->name('legalisir_admin.admin_ditempat_transkrip_resi')
    ->middleware('UserAkses:admin');

    route::get('/legalisir/sv/ditempat/transkrip', [Supervisor_Legalisir_Controller::class, 'ditempat_transkrip'])->name('legalisir_sv.sv_ditempat_transkrip')
    ->middleware('UserAkses:supervisor_akd');
    Route::get('/legalisir/sv/ditempat/transkrip/search', [Supervisor_Legalisir_Controller::class, 'ditempat_transkrip'])->name('legalisir_sv.sv_ditempat_transkrip_search')
    ->middleware('UserAkses:supervisor_akd');
    Route::get('/legalisir/sv/ditempat/transkrip/cek_legal/{id}', [Supervisor_Legalisir_Controller::class, 'cek_sv_ditempat_transkrip'])->name('legalisir_sv.cek_sv_ditempat_transkrip')
    ->middleware('UserAkses:supervisor_akd');
    Route::post('/legalisir/sv/ditempat/transkrip/cek_legal/setuju/{id}',  [Supervisor_Legalisir_Controller::class, 'setuju_sv_ditempat_transkrip'])->name('legalisir_sv.setuju_sv_ditempat_transkrip')
    ->middleware('UserAkses:supervisor_akd');
    Route::post('/legalisir/sv/ditempat/transkrip/cek_legal/tolak/{id}',  [Supervisor_Legalisir_Controller::class, 'tolak_sv_ditempat_transkrip'])->name('legalisir_sv.tolak_sv_ditempat_transkrip')
    ->middleware('UserAkses:supervisor_akd');

    // route::get('/legalisir/manajer/ditempat/transkrip', [Manajer_Legalisir_Controller::class, 'ditempat_transkrip'])->name('legalisir_manajer.manajer_ditempat_transkrip')
    // ->middleware('UserAkses:manajer');
    // Route::get('/legalisir/manajer/ditempat/transkrip/search', [Manajer_Legalisir_Controller::class, 'ditempat_transkrip'])->name('legalisir_manajer.manajer_ditempat_transkrip_search')
    // ->middleware('UserAkses:manajer');
    // Route::post('/legalisir/manajer/ditempat/transkrip/setuju/{id}',  [Manajer_Legalisir_Controller::class, 'setuju_ditempat_transkrip'])->name('legalisir_manajer.manajer_ditempat_transkrip_setuju')
    // ->middleware('UserAkses:manajer');

    // Ditempat Ijazah dan Transkrip

    Route::get('/legalisir/admin/ditempat/ijz_trs', [Admin_Legalisir_Controller::class, 'ditempat_ijz_trs'])->name('legalisir_admin.admin_ditempat_ijz_trs')->
    middleware('UserAkses:admin');
    Route::get('/legalisir/admin/ditempat/ijz_trs/search', [Admin_Legalisir_Controller::class, 'ditempat_ijz_trs'])->name('legalisir_admin.admin_ditempat_ijz_trs_search')->
    middleware('UserAkses:admin');
    Route::get('/legalisir/admin/ditempat/ijz_trs/download/{id}', [Admin_Legalisir_Controller::class, 'unduh_ditempat_ijz_trs'])->name('legalisir_admin.admin_ditempat_ijz_trs_download')->
    middleware('UserAkses:admin');
    Route::get('/legalisir/admin/ditempat/ijz_trs/cek_legal/{id}', [Admin_Legalisir_Controller::class, 'cek_ditempat_ijz_trs'])->name('legalisir_admin.admin_ditempat_ijz_trs_cek')
    ->middleware('UserAkses:admin');
    Route::post('/legalisir/admin/ditempat/ijz_trs/cek_legal/setuju/{id}',  [Admin_Legalisir_Controller::class, 'setuju_ditempat_ijz_trs'])->name('legalisir_admin.admin_ditempat_ijz_trs_setuju')
    ->middleware('UserAkses:admin');
    Route::post('/legalisir/admin/ditempat/ijz_trs/cek_legal/tolak/{id}',  [Admin_Legalisir_Controller::class, 'tolak_ditempat_ijz_trs'])->name('legalisir_admin.admin_ditempat_ijz_trs_tolak')
    ->middleware('UserAkses:admin');
    Route::post('/legalisir/admin/ditempat/ijz_trs/no_resi/{id}',  [Admin_Legalisir_Controller::class, 'resi_ditempat_ijz_trs'])->name('legalisir_admin.admin_ditempat_ijz_trs_resi')
    ->middleware('UserAkses:admin');

    route::get('/legalisir/sv/ditempat/ijz_trs', [Supervisor_Legalisir_Controller::class, 'ditempat_ijz_trs'])->name('legalisir_sv.sv_ditempat_ijz_trs')
    ->middleware('UserAkses:supervisor_akd');
    Route::get('/legalisir/sv/ditempat/ijz_trs/search', [Supervisor_Legalisir_Controller::class, 'ditempat_ijz_trs'])->name('legalisir_sv.sv_ditempat_ijz_trs_search')
    ->middleware('UserAkses:supervisor_akd');
    Route::get('/legalisir/sv/ditempat/ijz_trs/cek_legal/{id}', [Supervisor_Legalisir_Controller::class, 'cek_sv_ditempat_ijz_trs'])->name('legalisir_sv.cek_sv_ditempat_ijz_trs')
    ->middleware('UserAkses:supervisor_akd');
    Route::post('/legalisir/sv/ditempat/ijz_trs/cek_legal/setuju/{id}',  [Supervisor_Legalisir_Controller::class, 'setuju_sv_ditempat_ijz_trs'])->name('legalisir_sv.setuju_sv_ditempat_ijz_trs')
    ->middleware('UserAkses:supervisor_akd');
    Route::post('/legalisir/sv/ditempat/ijz_trs/cek_legal/tolak/{id}',  [Supervisor_Legalisir_Controller::class, 'tolak_sv_ditempat_ijz_trs'])->name('legalisir_sv.tolak_sv_ditempat_ijz_trs')
    ->middleware('UserAkses:supervisor_akd');

    // route::get('/legalisir/manajer/ditempat/ijz_trs', [Manajer_Legalisir_Controller::class, 'ditempat_ijz_trs'])->name('legalisir_manajer.manajer_ditempat_ijz_trs')
    // ->middleware('UserAkses:manajer');
    // Route::get('/legalisir/manajer/ditempat/ijz_trs/search', [Manajer_Legalisir_Controller::class, 'ditempat_ijz_trs'])->name('legalisir_manajer.manajer_ditempat_ijz_trs_search')
    // ->middleware('UserAkses:manajer');
    // Route::post('/legalisir/manajer/ditempat/ijz_trs/setuju/{id}',  [Manajer_Legalisir_Controller::class, 'setuju_ditempat_ijz_trs'])->name('legalisir_manajer.manajer_ditempat_ijz_trs_setuju')
    // ->middleware('UserAkses:manajer');

    // Tracking Surat

    Route::get('/tracking/manajer', [AdminController::class, 'track_srt_manajer'])->name('track_srt.manajer')->
    middleware('UserAkses:admin');
    Route::get('/tracking/sv_akd', [AdminController::class, 'track_srt_sv_akd'])->name('track_srt.sv_akd')->
    middleware('UserAkses:admin');
    Route::get('/tracking/sv_sd', [AdminController::class, 'track_srt_sv_sd'])->name('track_srt.sv_sd')->
    middleware('UserAkses:admin');
    Route::get('/tracking/wd1', [AdminController::class, 'track_srt_wd1'])->name('track_srt.wd1')->
    middleware('UserAkses:admin');
    Route::get('/tracking/wd2', [AdminController::class, 'track_srt_wd2'])->name('track_srt.wd2')->
    middleware('UserAkses:admin');

    // Riwayat Surat Admin

    Route::get('/riwayat_srt/admin/srt_mhw_asn', [Riwayat_Surat_Controller::class, 'admin_srt_mhw_asn'])->name('riwayat_admin.srt_mhw_asn')->
    middleware('UserAkses:admin');
    Route::get('/riwayat_srt/admin/srt_mhw_asn/search', [Riwayat_Surat_Controller::class, 'admin_srt_mhw_asn'])->name('riwayat_admin.srt_mhw_asn_search')->
    middleware('UserAkses:admin');
    Route::get('/riwayat_srt/admin/srt_masih_mhw', [Riwayat_Surat_Controller::class, 'admin_srt_masih_mhw'])->name('riwayat_admin.srt_masih_mhw')->
    middleware('UserAkses:admin');
    Route::get('/riwayat_srt/admin/srt_masih_mhw/search', [Riwayat_Surat_Controller::class, 'admin_srt_masih_mhw'])->name('riwayat_admin.srt_masih_mhw_search')->
    middleware('UserAkses:admin');
    Route::get('/riwayat_srt/admin/srt_bbs_pnjm', [Riwayat_Surat_Controller::class, 'admin_srt_bbs_pnjm'])->name('riwayat_admin.srt_bbs_pnjm')->
    middleware('UserAkses:admin');
    Route::get('/riwayat_srt/admin/srt_bbs_pnjm/search', [Riwayat_Surat_Controller::class, 'admin_srt_bbs_pnjm'])->name('riwayat_admin.srt_bbs_pnjm_search')->
    middleware('UserAkses:admin');
    Route::get('/riwayat_srt/admin/srt_izin_plt', [Riwayat_Surat_Controller::class, 'admin_srt_izin_plt'])->name('riwayat_admin.srt_izin_plt')->
    middleware('UserAkses:admin');
    Route::get('/riwayat_srt/admin/srt_izin_plt/search', [Riwayat_Surat_Controller::class, 'admin_srt_izin_plt'])->name('riwayat_admin.srt_izin_plt_search')->
    middleware('UserAkses:admin');
    Route::get('/riwayat_srt/admin/srt_magang', [Riwayat_Surat_Controller::class, 'admin_srt_magang'])->name('riwayat_admin.srt_magang')->
    middleware('UserAkses:admin');
    Route::get('/riwayat_srt/admin/srt_magang/search', [Riwayat_Surat_Controller::class, 'admin_srt_magang'])->name('riwayat_admin.srt_magang_search')->
    middleware('UserAkses:admin');
    Route::get('/riwayat_srt/admin/srt_pmhn_kmbali_biaya', [Riwayat_Surat_Controller::class, 'admin_srt_pmhn_kmbali_biaya'])->name('riwayat_admin.srt_pmhn_kmbali_biaya')->
    middleware('UserAkses:admin');
    Route::get('/riwayat_srt/admin/srt_pmhn_kmbali_biaya/search', [Riwayat_Surat_Controller::class, 'admin_srt_pmhn_kmbali_biaya'])->name('riwayat_admin.srt_pmhn_kmbali_biaya_search')->
    middleware('UserAkses:admin');
    Route::get('/riwayat_srt/admin/legalisir', [Riwayat_Surat_Controller::class, 'admin_legalisir'])->name('riwayat_admin.legalisir')->
    middleware('UserAkses:admin');
    Route::get('/riwayat_srt/admin/legalisir/search', [Riwayat_Surat_Controller::class, 'admin_legalisir'])->name('riwayat_admin.legalisir_search')->
    middleware('UserAkses:admin');


    // Riwayat Surat Supervisor Akademik

    Route::get('/riwayat_srt/sv_akd/srt_mhw_asn', [Riwayat_Surat_Controller::class, 'sv_akd_srt_mhw_asn'])->name('riwayat_sv_akd.srt_mhw_asn')->
    middleware('UserAkses:supervisor_akd');
    Route::get('/riwayat_srt/sv_akd/srt_mhw_asn/search', [Riwayat_Surat_Controller::class, 'sv_akd_srt_mhw_asn'])->name('riwayat_sv_akd.srt_mhw_asn_search')->
    middleware('UserAkses:supervisor_akd');
    Route::get('/riwayat_srt/sv_akd/srt_masih_mhw', [Riwayat_Surat_Controller::class, 'sv_akd_srt_masih_mhw'])->name('riwayat_sv_akd.srt_masih_mhw')->
    middleware('UserAkses:supervisor_akd');
    Route::get('/riwayat_srt/sv_akd/srt_masih_mhw/search', [Riwayat_Surat_Controller::class, 'sv_akd_srt_masih_mhw'])->name('riwayat_sv_akd.srt_masih_mhw_search')->
    middleware('UserAkses:supervisor_akd');
    Route::get('/riwayat_srt/sv_akd/srt_izin_plt', [Riwayat_Surat_Controller::class, 'sv_akd_srt_izin_plt'])->name('riwayat_sv_akd.srt_izin_plt')->
    middleware('UserAkses:supervisor_akd');
    Route::get('/riwayat_srt/sv_akd/srt_izin_plt/search', [Riwayat_Surat_Controller::class, 'sv_akd_srt_izin_plt'])->name('riwayat_sv_akd.srt_izin_plt_search')->
    middleware('UserAkses:supervisor_akd');
    Route::get('/riwayat_srt/sv_akd/srt_magang', [Riwayat_Surat_Controller::class, 'sv_akd_srt_magang'])->name('riwayat_sv_akd.srt_magang')->
    middleware('UserAkses:supervisor_akd');
    Route::get('/riwayat_srt/sv_akd/srt_magang/search', [Riwayat_Surat_Controller::class, 'sv_akd_srt_magang'])->name('riwayat_sv_akd.srt_magang_search')->
    middleware('UserAkses:supervisor_akd');
    Route::get('/riwayat_srt/sv_akd/legalisir', [Riwayat_Surat_Controller::class, 'sv_akd_legalisir'])->name('riwayat_sv_akd.legalisir')->
    middleware('UserAkses:supervisor_akd');
    Route::get('/riwayat_srt/sv_akd/legalisir/search', [Riwayat_Surat_Controller::class, 'sv_akd_legalisir'])->name('riwayat_sv_akd.legalisir_search')->
    middleware('UserAkses:supervisor_akd');

    // Riwayat Surat Supervisor Sumber Daya

    Route::get('/riwayat_srt/sv_sd/srt_bbs_pnjm', [Riwayat_Surat_Controller::class, 'sv_sd_srt_bbs_pnjm'])->name('riwayat_sv_sd.srt_bbs_pnjm')->
    middleware('UserAkses:supervisor_sd');
    Route::get('/riwayat_srt/sv_sd/srt_bbs_pnjm/search', [Riwayat_Surat_Controller::class, 'sv_sd_srt_bbs_pnjm'])->name('riwayat_sv_sd.srt_bbs_pnjm_search')->
    middleware('UserAkses:supervisor_sd');
    Route::get('/riwayat_srt/sv_sd/srt_pmhn_kmbali_biaya', [Riwayat_Surat_Controller::class, 'sv_sd_srt_pmhn_kmbali_biaya'])->name('riwayat_sv_sd.srt_pmhn_kmbali_biaya')->
    middleware('UserAkses:supervisor_sd');
    Route::get('/riwayat_srt/sv_sd/srt_pmhn_kmbali_biaya/search', [Riwayat_Surat_Controller::class, 'sv_sd_srt_pmhn_kmbali_biaya'])->name('riwayat_sv_sd.srt_pmhn_kmbali_biaya_search')->
    middleware('UserAkses:supervisor_sd');


    // Riwayat Surat Manajer

    Route::get('/riwayat_srt/manajer/srt_mhw_asn', [Riwayat_Surat_Controller::class, 'manajer_srt_mhw_asn'])->name('riwayat_manajer.srt_mhw_asn')->
    middleware('UserAkses:manajer');
    Route::get('/riwayat_srt/manajer/srt_mhw_asn/search', [Riwayat_Surat_Controller::class, 'manajer_srt_mhw_asn'])->name('riwayat_manajer.srt_mhw_asn_search')->
    middleware('UserAkses:manajer');
    Route::get('/riwayat_srt/manajer/srt_masih_mhw', [Riwayat_Surat_Controller::class, 'manajer_srt_masih_mhw'])->name('riwayat_manajer.srt_masih_mhw')->
    middleware('UserAkses:manajer');
    Route::get('/riwayat_srt/manajer/srt_masih_mhw/search', [Riwayat_Surat_Controller::class, 'manajer_srt_masih_mhw'])->name('riwayat_manajer.srt_masih_mhw_search')->
    middleware('UserAkses:manajer');
    Route::get('/riwayat_srt/manajer/srt_izin_plt', [Riwayat_Surat_Controller::class, 'manajer_srt_izin_plt'])->name('riwayat_manajer.srt_izin_plt')->
    middleware('UserAkses:manajer');
    Route::get('/riwayat_srt/manajer/srt_izin_plt/search', [Riwayat_Surat_Controller::class, 'manajer_srt_izin_plt'])->name('riwayat_manajer.srt_izin_plt_search')->
    middleware('UserAkses:manajer');
    Route::get('/riwayat_srt/manajer/srt_magang', [Riwayat_Surat_Controller::class, 'manajer_srt_magang'])->name('riwayat_manajer.srt_magang')->
    middleware('UserAkses:manajer');
    Route::get('/riwayat_srt/manajer/srt_magang/search', [Riwayat_Surat_Controller::class, 'manajer_srt_magang'])->name('riwayat_manajer.srt_magang_search')->
    middleware('UserAkses:manajer');
    Route::get('/riwayat_srt/manajer/srt_pmhn_kmbali_biaya', [Riwayat_Surat_Controller::class, 'manajer_srt_pmhn_kmbali_biaya'])->name('riwayat_manajer.srt_pmhn_kmbali_biaya')->
    middleware('UserAkses:manajer');
    Route::get('/riwayat_srt/manajer/srt_pmhn_kmbali_biaya/search', [Riwayat_Surat_Controller::class, 'manajer_srt_pmhn_kmbali_biaya'])->name('riwayat_manajer.srt_pmhn_kmbali_biaya_search')->
    middleware('UserAkses:manajer');
    // Route::get('/riwayat_srt/manajer/legalisir', [Riwayat_Surat_Controller::class, 'manajer_legalisir'])->name('riwayat_manajer.legalisir')->
    // middleware('UserAkses:manajer');
    // Route::get('/riwayat_srt/manajer/legalisir/search', [Riwayat_Surat_Controller::class, 'manajer_legalisir'])->name('riwayat_manajer.legalisir_search')->
    // middleware('UserAkses:manajer');

    // Riwayat Wakil Dekan 1

    Route::get('/riwayat_srt/wd1/srt_masih_mhw', [Riwayat_Surat_Controller::class, 'wd1_srt_masih_mhw'])->name('riwayat_wd1.srt_masih_mhw')->
    middleware('UserAkses:wd1');
    Route::get('/riwayat_srt/wd1/srt_masih_mhw/search', [Riwayat_Surat_Controller::class, 'wd1_srt_masih_mhw'])->name('riwayat_wd1.srt_masih_mhw_search')->
    middleware('UserAkses:wd1');
    Route::get('/riwayat_srt/wd1/srt_izin_plt', [Riwayat_Surat_Controller::class, 'wd1_srt_izin_plt'])->name('riwayat_wd1.srt_izin_plt')->
    middleware('UserAkses:wd1');
    Route::get('/riwayat_srt/wd1/srt_izin_plt/search', [Riwayat_Surat_Controller::class, 'wd1_srt_izin_plt'])->name('riwayat_wd1.srt_izin_plt_search')->
    middleware('UserAkses:wd1');
    Route::get('/riwayat_srt/wd1/srt_magang', [Riwayat_Surat_Controller::class, 'wd1_srt_magang'])->name('riwayat_wd1.srt_magang')->
    middleware('UserAkses:wd1');
    Route::get('/riwayat_srt/wd1/srt_magang/search', [Riwayat_Surat_Controller::class, 'wd1_srt_magang'])->name('riwayat_wd1.srt_magang_search')->
    middleware('UserAkses:wd1');

    // Riwayat Wakil Dekan 2

    Route::get('/riwayat_srt/wd2/srt_pmhn_kmbali_biaya', [Riwayat_Surat_Controller::class, 'wd2_srt_pmhn_kmbali_biaya'])->name('riwayat_wd2.srt_pmhn_kmbali_biaya')->
    middleware('UserAkses:wd2');
    Route::get('/riwayat_srt/wd2/srt_pmhn_kmbali_biaya/search', [Riwayat_Surat_Controller::class, 'wd2_srt_pmhn_kmbali_biaya'])->name('riwayat_wd2.srt_pmhn_kmbali_biaya_search')->
    middleware('UserAkses:wd2');

    // Survey
    Route::get('/survey', [Survey_Controller::class, 'mahasiswa'])->name('survey.mahasiswa')->
    middleware('UserAkses:mahasiswa');
    Route::post('/survey/create', [Survey_Controller::class, 'mahasiswa_create'])->name('survey.mahasiswa_create')->
    middleware('UserAkses:mahasiswa');
    Route::get('/survey/admin', [Survey_Controller::class, 'admin'])->name('survey.admin')->
    middleware('UserAkses:admin');
    Route::get('/survey/sv_akd', [Survey_Controller::class, 'sv_akd'])->name('survey.supervisor_akd')->
    middleware('UserAkses:supervisor_akd');
    Route::get('/survey/sv_sd', [Survey_Controller::class, 'sv_sd'])->name('survey.supervisor_sd')->
    middleware('UserAkses:supervisor_sd');
    Route::get('/survey/manajer', [Survey_Controller::class, 'manajer'])->name('survey.manajer')->
    middleware('UserAkses:manajer');
    Route::get('/survey/wd1', [Survey_Controller::class, 'wd1'])->name('survey.wd1')->
    middleware('UserAkses:wd1');
    Route::get('/survey/wd2', [Survey_Controller::class, 'wd2'])->name('survey.wd2')->
    middleware('UserAkses:wd2');
});


