<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Riwayat_Surat_Controller extends Controller
{
    function admin_srt_mhw_asn(Request $request) {
        $search = $request->input('search');

        $query = DB::table('srt_mhw_asn')
            ->join('users', 'srt_mhw_asn.users_id', '=', 'users.id')
            ->select(
                'srt_mhw_asn.id',
                'users.nama as nama_mhw',
                'srt_mhw_asn.role_surat',
            )
            ->Where('srt_mhw_asn.role_surat', 'mahasiswa');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('riwayat_admin.srt_mhw_asn', compact('data'));
    }

    function admin_srt_masih_mhw(Request $request) {
        $search = $request->input('search');

        $query = DB::table('srt_masih_mhw')
            ->join('users', 'srt_masih_mhw.users_id', '=', 'users.id')
            ->select(
                'srt_masih_mhw.id',
                'users.nama as nama_mhw',
                'srt_masih_mhw.role_surat',
            )
            ->Where('srt_masih_mhw.role_surat', 'mahasiswa');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('riwayat_admin.srt_masih_mhw', compact('data'));
    }

    function admin_legalisir(Request $request) {
        $search = $request->input('search');

        $query = DB::table('legalisir')
            ->join('users', 'legalisir.users_id', '=', 'users.id')
            ->select(
                'legalisir.id',
                'users.nama as nama_mhw',
                'legalisir.role_surat',
            )
            ->Where('legalisir.role_surat', 'mahasiswa');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('riwayat_admin.legalisir', compact('data'));
    }

    function admin_srt_bbs_pnjm(Request $request) {
        $search = $request->input('search');

        $query = DB::table('srt_bbs_pnjm')
            ->join('users', 'srt_bbs_pnjm.users_id', '=', 'users.id')
            ->select(
                'srt_bbs_pnjm.id',
                'users.nama as nama_mhw',
                'srt_bbs_pnjm.role_surat',
            )
            ->Where('srt_bbs_pnjm.role_surat', 'mahasiswa');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('riwayat_admin.srt_bbs_pnjm', compact('data'));
    }

    function admin_srt_izin_plt(Request $request) {
        $search = $request->input('search');

        $query = DB::table('srt_izin_plt')
            ->join('users', 'srt_izin_plt.users_id', '=', 'users.id')
            ->select(
                'srt_izin_plt.id',
                'users.nama as nama_mhw',
                'srt_izin_plt.role_surat',
            )
            ->Where('srt_izin_plt.role_surat', 'mahasiswa');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('riwayat_admin.srt_izin_plt', compact('data'));
    }

    function admin_srt_pmhn_kmbali_biaya(Request $request) {
        $search = $request->input('search');

        $query = DB::table('srt_pmhn_kmbali_biaya')
            ->join('users', 'srt_pmhn_kmbali_biaya.users_id', '=', 'users.id')
            ->select(
                'srt_pmhn_kmbali_biaya.id',
                'users.nama as nama_mhw',
                'srt_pmhn_kmbali_biaya.role_surat',
            )
            ->Where('srt_pmhn_kmbali_biaya.role_surat', 'mahasiswa');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('riwayat_admin.srt_pmhn_kmbali_biaya', compact('data'));
    }

    function admin_srt_magang(Request $request) {
        $search = $request->input('search');

        $query = DB::table('srt_magang')
            ->join('users', 'srt_magang.users_id', '=', 'users.id')
            ->select(
                'srt_magang.id',
                'users.nama as nama_mhw',
                'srt_magang.role_surat',
            )
            ->Where('srt_magang.role_surat', 'mahasiswa');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('riwayat_admin.srt_magang', compact('data'));
    }

    function manajer_srt_mhw_asn(Request $request) {
        $search = $request->input('search');

        $query = DB::table('srt_mhw_asn')
            ->join('users', 'srt_mhw_asn.users_id', '=', 'users.id')
            ->select(
                'srt_mhw_asn.id',
                'users.nama as nama_mhw',
                'srt_mhw_asn.role_surat',
            )
            ->Where('srt_mhw_asn.role_surat', 'mahasiswa');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('riwayat_manajer.srt_mhw_asn', compact('data'));
    }

    function manajer_srt_masih_mhw(Request $request) {
        $search = $request->input('search');

        $query = DB::table('srt_masih_mhw')
            ->join('users', 'srt_masih_mhw.users_id', '=', 'users.id')
            ->select(
                'srt_masih_mhw.id',
                'users.nama as nama_mhw',
                'srt_masih_mhw.role_surat',
            )
            ->Where('srt_masih_mhw.role_surat', 'mahasiswa');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('riwayat_manajer.srt_masih_mhw', compact('data'));
    }

    // function manajer_legalisir(Request $request) {
    //     $search = $request->input('search');

    //     $query = DB::table('legalisir')
    //         ->select(
    //             'id',
    //             'nama_mhw',
    //             'role_surat',
    //         )
    //         ->Where('role_surat', 'mahasiswa');

    //     if ($search) {
    //         $query->where(function ($q) use ($search) {
    //             $q->where('nama_mhw', 'like', "%{$search}%");
    //         });
    //     }

    //     $data = $query->get();

    //     return view('riwayat_manajer.legalisir', compact('data'));
    // }

    function manajer_srt_izin_plt(Request $request) {
        $search = $request->input('search');

        $query = DB::table('srt_izin_plt')
            ->join('users', 'srt_izin_plt.users_id', '=', 'users.id')
            ->select(
                'srt_izin_plt.id',
                'users.nama as nama_mhw',
                'srt_izin_plt.role_surat',
            )
            ->Where('srt_izin_plt.role_surat', 'mahasiswa');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('riwayat_manajer.srt_izin_plt', compact('data'));
    }

    function manajer_srt_pmhn_kmbali_biaya(Request $request) {
        $search = $request->input('search');

        $query = DB::table('srt_pmhn_kmbali_biaya')
            ->join('users', 'srt_pmhn_kmbali_biaya.users_id', '=', 'users.id')
            ->select(
                'srt_pmhn_kmbali_biaya.id',
                'users.nama as nama_mhw',
                'srt_pmhn_kmbali_biaya.role_surat',
            )
            ->Where('srt_pmhn_kmbali_biaya.role_surat', 'mahasiswa');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('riwayat_manajer.srt_pmhn_kmbali_biaya', compact('data'));
    }

    function manajer_srt_magang(Request $request) {
        $search = $request->input('search');

        $query = DB::table('srt_magang')
            ->join('users', 'srt_magang.users_id', '=', 'users.id')
            ->select(
                'srt_magang.id',
                'users.nama as nama_mhw',
                'srt_magang.role_surat',
            )
            ->Where('srt_magang.role_surat', 'mahasiswa');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('riwayat_manajer.srt_magang', compact('data'));
    }

    function sv_akd_srt_mhw_asn(Request $request) {
        $search = $request->input('search');

        $query = DB::table('srt_mhw_asn')
            ->join('users', 'srt_mhw_asn.users_id', '=', 'users.id')
            ->select(
                'srt_mhw_asn.id',
                'users.nama as nama_mhw',
                'srt_mhw_asn.role_surat',
            )
            ->Where('srt_mhw_asn.role_surat', 'mahasiswa');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('riwayat_sv_akd.srt_mhw_asn', compact('data'));
    }

    function sv_akd_srt_masih_mhw(Request $request) {
        $search = $request->input('search');

        $query = DB::table('srt_masih_mhw')
            ->join('users', 'srt_masih_mhw.users_id', '=', 'users.id')
            ->select(
                'srt_masih_mhw.id',
                'users.nama as nama_mhw',
                'srt_masih_mhw.role_surat',
            )
            ->Where('srt_masih_mhw.role_surat', 'mahasiswa');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('riwayat_sv_akd.srt_masih_mhw', compact('data'));
    }

    function sv_akd_legalisir(Request $request) {
        $search = $request->input('search');

        $query = DB::table('legalisir')
            ->join('users', 'legalisir.users_id', '=', 'users.id')
            ->select(
                'legalisir.id',
                'users.nama as nama_mhw',
                'legalisir.role_surat',
            )
            ->Where('legalisir.role_surat', 'mahasiswa');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('riwayat_sv_akd.legalisir', compact('data'));
    }

    function sv_sd_srt_bbs_pnjm(Request $request) {
        $search = $request->input('search');

        $query = DB::table('srt_bbs_pnjm')
            ->join('users', 'srt_bbs_pnjm.users_id', '=', 'users.id')
            ->select(
                'srt_bbs_pnjm.id',
                'users.nama as nama_mhw',
                'srt_bbs_pnjm.role_surat',
            )
            ->Where('srt_bbs_pnjm.role_surat', 'mahasiswa');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('riwayat_sv_sd.srt_bbs_pnjm', compact('data'));
    }

    function sv_akd_srt_izin_plt(Request $request) {
        $search = $request->input('search');

        $query = DB::table('srt_izin_plt')
            ->join('users', 'srt_izin_plt.users_id', '=', 'users.id')
            ->select(
                'srt_izin_plt.id',
                'users.nama as nama_mhw',
                'srt_izin_plt.role_surat',
            )
            ->Where('srt_izin_plt.role_surat', 'mahasiswa');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('riwayat_sv_akd.srt_izin_plt', compact('data'));
    }

    function sv_sd_srt_pmhn_kmbali_biaya(Request $request) {
        $search = $request->input('search');

        $query = DB::table('srt_pmhn_kmbali_biaya')
            ->join('users', 'srt_pmhn_kmbali_biaya.users_id', '=', 'users.id')
            ->select(
                'srt_pmhn_kmbali_biaya.id',
                'users.nama as nama_mhw',
                'srt_pmhn_kmbali_biaya.role_surat',
            )
            ->Where('srt_pmhn_kmbali_biaya.role_surat', 'mahasiswa');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('riwayat_sv_sd.srt_pmhn_kmbali_biaya', compact('data'));
    }

    function sv_akd_srt_magang(Request $request) {
        $search = $request->input('search');

        $query = DB::table('srt_magang')
            ->join('users', 'srt_magang.users_id', '=', 'users.id')
            ->select(
                'srt_magang.id',
                'users.nama as nama_mhw',
                'srt_magang.role_surat',
            )
            ->Where('srt_magang.role_surat', 'mahasiswa');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('riwayat_sv_akd.srt_magang', compact('data'));
    }

    function wd2_srt_pmhn_kmbali_biaya(Request $request) {
        $search = $request->input('search');

        $query = DB::table('srt_pmhn_kmbali_biaya')
            ->join('users', 'srt_pmhn_kmbali_biaya.users_id', '=', 'users.id')
            ->select(
                'srt_pmhn_kmbali_biaya.id',
                'users.nama as nama_mhw',
                'srt_pmhn_kmbali_biaya.role_surat',
            )
            ->Where('srt_pmhn_kmbali_biaya.role_surat', 'mahasiswa');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('riwayat_wd2.srt_pmhn_kmbali_biaya', compact('data'));
    }

    function wd1_srt_izin_plt(Request $request) {
        $search = $request->input('search');

        $query = DB::table('srt_izin_plt')
            ->join('users', 'srt_izin_plt.users_id', '=', 'users.id')
            ->select(
                'srt_izin_plt.id',
                'users.nama as nama_mhw',
                'srt_izin_plt.role_surat',
            )
            ->Where('srt_izin_plt.role_surat', 'mahasiswa');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('riwayat_wd1.srt_izin_plt', compact('data'));
    }

    function wd1_srt_pmhn_kmbali_biaya(Request $request) {
        $search = $request->input('search');

        $query = DB::table('srt_pmhn_kmbali_biaya')
            ->join('users', 'srt_pmhn_kmbali_biaya.users_id', '=', 'users.id')
            ->select(
                'srt_pmhn_kmbali_biaya.id',
                'users.nama as nama_mhw',
                'srt_pmhn_kmbali_biaya.role_surat',
            )
            ->Where('srt_pmhn_kmbali_biaya.role_surat', 'mahasiswa');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('riwayat_wd1.srt_pmhn_kmbali_biaya', compact('data'));
    }

    function wd1_srt_magang(Request $request) {
        $search = $request->input('search');

        $query = DB::table('srt_magang')
            ->join('users', 'srt_magang.users_id', '=', 'users.id')
            ->select(
                'srt_magang.id',
                'users.nama as nama_mhw',
                'srt_magang.role_surat',
            )
            ->Where('srt_magang.role_surat', 'mahasiswa');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('riwayat_wd1.srt_magang', compact('data'));
    }

    function wd1_srt_masih_mhw(Request $request) {
        $search = $request->input('search');

        $query = DB::table('srt_masih_mhw')
            ->join('users', 'srt_masih_mhw.users_id', '=', 'users.id')
            ->select(
                'srt_masih_mhw.id',
                'users.nama as nama_mhw',
                'srt_masih_mhw.role_surat',
            )
            ->Where('srt_masih_mhw.role_surat', 'mahasiswa')
            ->where('srt_masih_mhw.tujuan_akhir', 'wd');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.nama', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        return view('riwayat_wd1.srt_masih_mhw', compact('data'));
    }
}
