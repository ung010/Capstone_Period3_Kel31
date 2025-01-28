<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Mpdf\Mpdf;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Hashids;

class Legal_Controller extends Controller
{
    function srt_mhw_asn($id)
    {
        $srt_mhw_asn = DB::table('srt_mhw_asn')
            ->join('prodi', 'srt_mhw_asn.prd_id', '=', 'prodi.id')
            ->join('users', 'srt_mhw_asn.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('srt_mhw_asn.id', $id)
            ->select(
                'srt_mhw_asn.id',
                'users.id as users_id',
                'prodi.id as prodi_id',
                'departement.id as departement_id',
                'users.nama as nama_mhw',
                'users.nim_nip',
                'departement.nama_dpt',
                'prodi.nama_prd',
                'srt_mhw_asn.thn_awl',
                'srt_mhw_asn.thn_akh',
                'srt_mhw_asn.nama_ortu',
                'srt_mhw_asn.nip_ortu',
                'srt_mhw_asn.ins_ortu',
                'srt_mhw_asn.role_surat',
                'srt_mhw_asn.tanggal_surat',
                'srt_mhw_asn.no_surat',
                'srt_mhw_asn.semester'
            )
            ->first();

        if ($srt_mhw_asn) {
            $srt_mhw_asn->tanggal_surat = Carbon::parse($srt_mhw_asn->tanggal_surat)->translatedFormat('d F Y');
        }

        return view('legal.srt_mhw_asn', compact('srt_mhw_asn'));
    }

    function lihat_srt_mhw_asn($id)
    {
        $mpdf = new \Mpdf\Mpdf();

        $srt_mhw_asn = DB::table('srt_mhw_asn')
            ->join('prodi', 'srt_mhw_asn.prd_id', '=', 'prodi.id')
            ->join('users', 'srt_mhw_asn.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('srt_mhw_asn.id', $id)
            ->select(
                'srt_mhw_asn.id',
                'users.id as users_id',
                'prodi.id as prodi_id',
                'departement.id as departement_id',
                'users.nama as nama_mhw',
                'users.nim_nip',
                'departement.nama_dpt',
                'prodi.nama_prd',
                'srt_mhw_asn.thn_awl',
                'srt_mhw_asn.thn_akh',
                'srt_mhw_asn.nama_ortu',
                'srt_mhw_asn.nip_ortu',
                'srt_mhw_asn.role_surat',
                'srt_mhw_asn.ins_ortu',
                'srt_mhw_asn.tanggal_surat',
                'srt_mhw_asn.no_surat',
                'srt_mhw_asn.semester'
            )
            ->first();

        if ($srt_mhw_asn && $srt_mhw_asn->tanggal_surat) {
            $srt_mhw_asn->tanggal_surat = Carbon::parse($srt_mhw_asn->tanggal_surat)->format('d-m-Y');
        }

        $qrUrl = url('/legal/srt_mhw_asn/' . $srt_mhw_asn->id);
        $qrCodePath = 'storage/qrcodes/qr-' . $srt_mhw_asn->id . '.png';
        $qrCodeFullPath = public_path($qrCodePath);

        if (!File::exists(dirname($qrCodeFullPath))) {
            File::makeDirectory(dirname($qrCodeFullPath), 0755, true);
        }

        QrCode::format('png')->size(100)->generate($qrUrl, $qrCodeFullPath);

        $pdf = Pdf::loadView('srt_mhw_asn.view', compact('srt_mhw_asn', 'qrCodePath'));

        return $pdf->stream('Surat_Mahasiswa_Bagi_ASN_' . $srt_mhw_asn->nama . '.pdf');
    }

    function srt_masih_mhw($id)
    {
        $srt_masih_mhw = DB::table('srt_masih_mhw')
            ->join('prodi', 'srt_masih_mhw.prd_id', '=', 'prodi.id')
            ->join('users', 'srt_masih_mhw.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('srt_masih_mhw.id', $id)
            ->select(
                'srt_masih_mhw.id',
                'users.id as users_id',
                'prodi.id as prd_id',
                'departement.id as dpt_id',
                'users.nama as nama_mhw',
                'srt_masih_mhw.no_surat',
                'users.nim_nip',
                'users.nowa',
                'departement.nama_dpt',
                'prodi.nama_prd',
                DB::raw('CONCAT(users.kota, ", ", DATE_FORMAT(users.tanggal_lahir, "%d-%m-%Y")) as ttl'),
                'srt_masih_mhw.no_surat',
                'srt_masih_mhw.tujuan_buat_srt',
                'srt_masih_mhw.role_surat',
                'srt_masih_mhw.tujuan_akhir',
                'srt_masih_mhw.tanggal_surat'
            )
            ->first();

        if ($srt_masih_mhw) {
            $srt_masih_mhw->tanggal_surat = Carbon::parse($srt_masih_mhw->tanggal_surat)->translatedFormat('d F Y');
        }

        return view('legal.srt_masih_mhw', compact('srt_masih_mhw'));
    }

    function lihat_srt_masih_mhw_wd($id)
    {
        $srt_masih_mhw = DB::table('srt_masih_mhw')
            ->join('prodi', 'srt_masih_mhw.prd_id', '=', 'prodi.id')
            ->join('users', 'srt_masih_mhw.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('srt_masih_mhw.id', $id)
            ->select(
                'srt_masih_mhw.id',
                'users.id as users_id',
                'prodi.id as prd_id',
                'departement.id as dpt_id',
                'users.nama',
                'users.nama as nama_mhw',
                'users.nim_nip',
                'users.nowa',
                'departement.nama_dpt',
                'prodi.nama_prd',
                'srt_masih_mhw.thn_awl',
                'srt_masih_mhw.thn_akh',
                'srt_masih_mhw.almt_smg',
                DB::raw('CONCAT(users.kota, ", ", DATE_FORMAT(users.tanggal_lahir, "%d-%m-%Y")) as ttl'),
                'srt_masih_mhw.no_surat',
                'srt_masih_mhw.tujuan_buat_srt',
                'srt_masih_mhw.role_surat',
                'srt_masih_mhw.tujuan_akhir',
                'srt_masih_mhw.tanggal_surat'
            )
            ->first();

        if (!$srt_masih_mhw) {
            return redirect()->back()->with('error', 'Data not found');
        }

        if ($srt_masih_mhw->tanggal_surat) {
            $srt_masih_mhw->tanggal_surat = Carbon::parse($srt_masih_mhw->tanggal_surat)->format('d-m-Y');
        }

        $qrUrl = url('/legal/srt_masih_mhw/' . $srt_masih_mhw->id);
        $qrCodePath = 'storage/qrcodes/qr-' . $srt_masih_mhw->id . '.png';
        $qrCodeFullPath = public_path($qrCodePath);

        if (!File::exists(dirname($qrCodeFullPath))) {
            File::makeDirectory(dirname($qrCodeFullPath), 0755, true);
        }

        QrCode::format('png')->size(100)->generate($qrUrl, $qrCodeFullPath);

        $pdf = Pdf::loadView('srt_masih_mhw.view_wd', compact('srt_masih_mhw', 'qrCodePath'));

        return $pdf->stream('Surat_Keterangan_Mahasiswa_' . $srt_masih_mhw->nama . '.pdf');
    }

    function lihat_srt_masih_mhw_manajer($id)
    {
        $srt_masih_mhw = DB::table('srt_masih_mhw')
            ->join('prodi', 'srt_masih_mhw.prd_id', '=', 'prodi.id')
            ->join('users', 'srt_masih_mhw.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('srt_masih_mhw.id', $id)
            ->select(
                'srt_masih_mhw.id',
                'users.id as users_id',
                'prodi.id as prd_id',
                'departement.id as dpt_id',
                'users.nama',
                'users.nama as nama_mhw',
                'users.nim_nip',
                'users.nowa',
                'departement.nama_dpt',
                'prodi.nama_prd',
                'srt_masih_mhw.thn_awl',
                'srt_masih_mhw.thn_akh',
                'srt_masih_mhw.almt_smg',
                DB::raw('CONCAT(users.kota, ", ", DATE_FORMAT(users.tanggal_lahir, "%d-%m-%Y")) as ttl'),
                'srt_masih_mhw.no_surat',
                'srt_masih_mhw.tujuan_buat_srt',
                'srt_masih_mhw.role_surat',
                'srt_masih_mhw.tujuan_akhir',
                'srt_masih_mhw.tanggal_surat'
            )
            ->first();

        if (!$srt_masih_mhw) {
            return redirect()->back()->with('error', 'Data not found');
        }

        if ($srt_masih_mhw->tanggal_surat) {
            $srt_masih_mhw->tanggal_surat = Carbon::parse($srt_masih_mhw->tanggal_surat)->format('d-m-Y');
        }

        $qrUrl = url('/legal/srt_masih_mhw/' . $srt_masih_mhw->id);
        $qrCodePath = 'storage/qrcodes/qr-' . $srt_masih_mhw->id . '.png';
        $qrCodeFullPath = public_path($qrCodePath);

        if (!File::exists(dirname($qrCodeFullPath))) {
            File::makeDirectory(dirname($qrCodeFullPath), 0755, true);
        }

        QrCode::format('png')->size(100)->generate($qrUrl, $qrCodeFullPath);

        $pdf = Pdf::loadView('srt_masih_mhw.view_manajer', compact('srt_masih_mhw', 'qrCodePath'));

        return $pdf->stream('Surat_Keterangan_Mahasiswa_' . $srt_masih_mhw->nama . '.pdf');
    }

    function srt_magang($id)
    {
        $srt_magang = DB::table('srt_magang')
            ->join('prodi', 'srt_magang.prd_id', '=', 'prodi.id')
            ->join('users', 'srt_magang.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('srt_magang.id', $id)
            ->select(
                'srt_magang.id',
                'srt_magang.no_surat',
                'srt_magang.tanggal_surat',
                'users.nama as nama_mhw',
                'users.id as users_id',
                'prodi.id as prd_id',
                'departement.id as dpt_id',
                'users.nama',
                'users.nim_nip',
                'users.nowa',
                'departement.nama_dpt',
                'prodi.nama_prd',
                'srt_magang.role_surat',
            )
            ->first();

        if ($srt_magang) {
            $srt_magang->tanggal_surat = Carbon::parse($srt_magang->tanggal_surat)->translatedFormat('d F Y');
        }

        return view('legal.srt_magang', compact('srt_magang'));
    }

    function lihat_srt_magang($id)
    {
        $srt_magang = DB::table('srt_magang')
            ->join('prodi', 'srt_magang.prd_id', '=', 'prodi.id')
            ->join('users', 'srt_magang.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('srt_magang.id', $id)
            ->select(
                'srt_magang.id',
                'srt_magang.no_surat',
                'srt_magang.tanggal_surat',
                'users.nama as nama_mhw',
                'users.id as users_id',
                'prodi.id as prd_id',
                'departement.id as dpt_id',
                'users.nama',
                'users.nim_nip',
                'users.nowa',
                'users.email',
                'departement.nama_dpt',
                'prodi.nama_prd',
                'srt_magang.semester',
                'srt_magang.ipk',
                'srt_magang.sksk',
                'srt_magang.nama_lmbg',
                'srt_magang.jbt_lmbg',
                'srt_magang.kota_lmbg',
                'srt_magang.almt_lmbg',
                'srt_magang.almt_smg',
                'srt_magang.role_surat',
            )
            ->first();

        if (!$srt_magang) {
            return redirect()->back()->with('error', 'Data not found');
        }

        if ($srt_magang->tanggal_surat) {
            $srt_magang->tanggal_surat = Carbon::parse($srt_magang->tanggal_surat)->format('d-m-Y');
        }

        $qrUrl = url('/legal/srt_magang/' . $srt_magang->id);
        $qrCodePath = 'storage/qrcodes/qr-' . $srt_magang->id . '.png';
        $qrCodeFullPath = public_path($qrCodePath);

        if (!File::exists(dirname($qrCodeFullPath))) {
            File::makeDirectory(dirname($qrCodeFullPath), 0755, true);
        }

        QrCode::format('png')->size(100)->generate($qrUrl, $qrCodeFullPath);

        $pdf = Pdf::loadView('srt_magang.view', compact('srt_magang', 'qrCodePath'));

        return $pdf->stream('Surat_Magang_' . $srt_magang->nama . '.pdf');
    }

    function srt_izin_plt($id)
    {
        $srt_izin_plt = DB::table('srt_izin_plt')
            ->join('prodi', 'srt_izin_plt.prd_id', '=', 'prodi.id')
            ->join('users', 'srt_izin_plt.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('srt_izin_plt.id', $id)
            ->select(
                'srt_izin_plt.id',
                'srt_izin_plt.no_surat',
                'srt_izin_plt.tanggal_surat',
                'users.nama as nama_mhw',
                'users.id as users_id',
                'prodi.id as prd_id',
                'departement.id as dpt_id',
                'users.nim_nip',
                'srt_izin_plt.judul_data',
                'srt_izin_plt.nama_lmbg',
                'srt_izin_plt.role_surat',
            )
            ->first();

        if ($srt_izin_plt) {
            $srt_izin_plt->tanggal_surat = Carbon::parse($srt_izin_plt->tanggal_surat)->translatedFormat('d F Y');
        }

        return view('legal.srt_izin_plt', compact('srt_izin_plt'));
    }

    function lihat_srt_izin_plt($id)
    {
        $srt_izin_plt = DB::table('srt_izin_plt')
            ->join('prodi', 'srt_izin_plt.prd_id', '=', 'prodi.id')
            ->join('users', 'srt_izin_plt.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('srt_izin_plt.id', $id)
            ->select(
                'srt_izin_plt.id',
                'srt_izin_plt.no_surat',
                'srt_izin_plt.tanggal_surat',
                'users.nama as nama_mhw',
                'users.id as users_id',
                'prodi.id as prd_id',
                'departement.id as dpt_id',
                'users.nama',
                'users.nim_nip',
                'users.nowa',
                'users.email',
                'users.almt_asl',
                'departement.nama_dpt',
                'prodi.nama_prd',
                'srt_izin_plt.semester',
                'srt_izin_plt.judul_data',
                'srt_izin_plt.nama_lmbg',
                'srt_izin_plt.jbt_lmbg',
                'srt_izin_plt.kota_lmbg',
                'srt_izin_plt.almt_lmbg',
                'srt_izin_plt.role_surat',
            )
            ->first();

        if (!$srt_izin_plt) {
            return redirect()->back()->with('error', 'Data not found');
        }

        if ($srt_izin_plt->tanggal_surat) {
            $srt_izin_plt->tanggal_surat = Carbon::parse($srt_izin_plt->tanggal_surat)->format('d-m-Y');
        }

        $qrUrl = url('/legal/srt_izin_plt/' . $srt_izin_plt->id);
        $qrCodePath = 'storage/qrcodes/qr-' . $srt_izin_plt->id . '.png';
        $qrCodeFullPath = public_path($qrCodePath);

        if (!File::exists(dirname($qrCodeFullPath))) {
            File::makeDirectory(dirname($qrCodeFullPath), 0755, true);
        }

        QrCode::format('png')->size(100)->generate($qrUrl, $qrCodeFullPath);

        $pdf = Pdf::loadView('srt_izin_plt.view', compact('srt_izin_plt', 'qrCodePath'));

        return $pdf->stream('Surat_Izin_Penelitian_' . $srt_izin_plt->nama . '.pdf');
    }

    function srt_pmhn_kmbali_biaya($id)
    {
        $srt_pmhn_kmbali_biaya = DB::table('srt_pmhn_kmbali_biaya')
            ->join('prodi', 'srt_pmhn_kmbali_biaya.prd_id', '=', 'prodi.id')
            ->join('users', 'srt_pmhn_kmbali_biaya.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('srt_pmhn_kmbali_biaya.id', $id)
            ->select(
                'srt_pmhn_kmbali_biaya.id',
                'srt_pmhn_kmbali_biaya.no_surat',
                'srt_pmhn_kmbali_biaya.tanggal_surat',
                'users.nama as nama_mhw',
                'users.id as users_id',
                'prodi.id as prd_id',
                'departement.id as dpt_id',
                'users.nim_nip',
                'prodi.nama_prd',
                'srt_pmhn_kmbali_biaya.role_surat',
            )
            ->first();

        if ($srt_pmhn_kmbali_biaya) {
            $srt_pmhn_kmbali_biaya->tanggal_surat = Carbon::parse($srt_pmhn_kmbali_biaya->tanggal_surat)->translatedFormat('d F Y');
        }

        return view('legal.srt_pmhn_kmbali_biaya', compact('srt_pmhn_kmbali_biaya'));
    }

    function lihat_srt_pmhn_kmbali_biaya($id)
    {
        $srt_pmhn_kmbali_biaya = DB::table('srt_pmhn_kmbali_biaya')
            ->join('prodi', 'srt_pmhn_kmbali_biaya.prd_id', '=', 'prodi.id')
            ->join('users', 'srt_pmhn_kmbali_biaya.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('srt_pmhn_kmbali_biaya.id', $id)
            ->select(
                'srt_pmhn_kmbali_biaya.id',
                'srt_pmhn_kmbali_biaya.no_surat',
                'srt_pmhn_kmbali_biaya.tanggal_surat',
                'users.nama as nama_mhw',
                'users.id as users_id',
                'prodi.id as prd_id',
                'departement.id as dpt_id',
                'users.nama',
                'users.nim_nip',
                'users.nowa',
                'users.email',
                'users.almt_asl',
                'departement.nama_dpt',
                'prodi.nama_prd',
                DB::raw('CONCAT(users.kota, ", ", DATE_FORMAT(users.tanggal_lahir, "%d-%m-%Y")) as ttl'),
                'srt_pmhn_kmbali_biaya.role_surat',
            )
            ->first();

        if (!$srt_pmhn_kmbali_biaya) {
            return redirect()->back()->with('error', 'Data not found');
        }

        if ($srt_pmhn_kmbali_biaya->tanggal_surat) {
            $srt_pmhn_kmbali_biaya->tanggal_surat = Carbon::parse($srt_pmhn_kmbali_biaya->tanggal_surat)->format('d-m-Y');
        }

        $qrUrl = url('/legal/srt_pmhn_kmbali_biaya/' . $srt_pmhn_kmbali_biaya->id);
        $qrCodePath = 'storage/qrcodes/qr-' . $srt_pmhn_kmbali_biaya->id . '.png';
        $qrCodeFullPath = public_path($qrCodePath);

        if (!File::exists(dirname($qrCodeFullPath))) {
            File::makeDirectory(dirname($qrCodeFullPath), 0755, true);
        }

        QrCode::format('png')->size(100)->generate($qrUrl, $qrCodeFullPath);

        $pdf = Pdf::loadView('srt_pmhn_kmbali_biaya.view', compact('srt_pmhn_kmbali_biaya', 'qrCodePath'));

        return $pdf->stream('Surat_Permohonan_Pengembalian_Biaya_' . $srt_pmhn_kmbali_biaya->nama . '.pdf');
    }

    function srt_bbs_pnjm($id)
    {
        $srt_bbs_pnjm = DB::table('srt_bbs_pnjm')
            ->join('prodi', 'srt_bbs_pnjm.prd_id', '=', 'prodi.id')
            ->join('users', 'srt_bbs_pnjm.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('srt_bbs_pnjm.id', $id)
            ->select(
                'srt_bbs_pnjm.id',
                'srt_bbs_pnjm.no_surat',
                'srt_bbs_pnjm.tanggal_surat',
                'users.nama as nama_mhw',
                'users.id as users_id',
                'prodi.id as prd_id',
                'departement.id as dpt_id',
                'users.nama',
                'users.nim_nip',
                'srt_bbs_pnjm.dosen_wali',
                'prodi.nama_prd',
                'srt_bbs_pnjm.role_surat',
            )
            ->first();

        if ($srt_bbs_pnjm) {
            $srt_bbs_pnjm->tanggal_surat = Carbon::parse($srt_bbs_pnjm->tanggal_surat)->translatedFormat('d F Y');
        }

        return view('legal.srt_bbs_pnjm', compact('srt_bbs_pnjm'));
    }

    function lihat_srt_bbs_pnjm($id)
    {
        $srt_bbs_pnjm = DB::table('srt_bbs_pnjm')
            ->join('prodi', 'srt_bbs_pnjm.prd_id', '=', 'prodi.id')
            ->join('users', 'srt_bbs_pnjm.users_id', '=', 'users.id')
            ->join('departement', 'prodi.dpt_id', '=', 'departement.id')
            ->where('srt_bbs_pnjm.id', $id)
            ->select(
                'srt_bbs_pnjm.id',
                'srt_bbs_pnjm.no_surat',
                'srt_bbs_pnjm.tanggal_surat',
                'users.nama as nama_mhw',
                'users.id as users_id',
                'prodi.id as prd_id',
                'departement.id as dpt_id',
                'users.nama',
                'users.nim_nip',
                'users.nowa',
                'departement.nama_dpt',
                'prodi.nama_prd',
                'srt_bbs_pnjm.dosen_wali',
                'srt_bbs_pnjm.almt_smg',
                'srt_bbs_pnjm.role_surat',
            )
            ->first();

        if (!$srt_bbs_pnjm) {
            return redirect()->back()->with('error', 'Data not found');
        }

        if ($srt_bbs_pnjm->tanggal_surat) {
            $srt_bbs_pnjm->tanggal_surat = Carbon::parse($srt_bbs_pnjm->tanggal_surat)->format('d-m-Y');
        }

        $qrUrl = url('/legal/srt_bbs_pnjm/' . $srt_bbs_pnjm->id);
        $qrCodePath = 'storage/qrcodes/qr-' . $srt_bbs_pnjm->id . '.png';
        $qrCodeFullPath = public_path($qrCodePath);

        if (!File::exists(dirname($qrCodeFullPath))) {
            File::makeDirectory(dirname($qrCodeFullPath), 0755, true);
        }

        QrCode::format('png')->size(100)->generate($qrUrl, $qrCodeFullPath);

        $pdf = Pdf::loadView('srt_bbs_pnjm.view', compact('srt_bbs_pnjm', 'qrCodePath'));

        return $pdf->stream('Surat_Bebas_Pinjam_' . $srt_bbs_pnjm->nama . '.pdf');
    }
}
