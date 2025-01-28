@extends('admin.layout')

@section('content')
    <div class="container-fluid p-0">
        <div class="d-flex gap-5 justify-content-center align-items-stretch align-content-center mb-5">
            <a href="/srt_mhw_asn/admin" class="card flex-even" style="background-color: #FFC2AF; text-decoration: none;">
                <div class="card-body row">
                    <div class="d-flex align-items-center justify-content-center gap-3 mb-3">
                        <h4 class="card-title">Surat Keterangan Masih Kuliah <br>(Bagi ASN)</br></h4>
                    </div>
                    <div class="d-flex align-items-center justify-content-center">
                        <h5 class="card-title">{{ $srt_mhw_asn }}</h5>
                    </div>
                    <div class="container-fluid">
                        <div class="border-top my-3 border-dark"></div>
                    </div>
                    <div class="d-flex align-items-center justify-content-center">
                        <h5 class="m-0 card-title">Jumlah Surat Masuk</h5>
                    </div>
                </div>
            </a>
            <a href="/srt_masih_mhw/admin" class="card flex-even" style="background-color: #EDEDED; text-decoration: none;">
                <div class="card-body row">
                    <div class="d-flex align-items-center justify-content-center gap-3 mb-3">
                        <h4 class="card-title">Surat Keterangan Masih Mahasiswa</h4>
                    </div>
                    <div class="d-flex align-items-center justify-content-center">
                        <h5 class="card-title">{{ $srt_masih_mhw }}</h5>
                    </div>
                    <div class="container-fluid">
                        <div class="border-top my-3 border-dark"></div>
                    </div>
                    <div class="d-flex align-items-center justify-content-center">
                        <h5 class="m-0 card-title">Jumlah Surat Masuk</h5>
                    </div>
                </div>
            </a>
            <a href="/srt_magang/admin" class="card flex-even" style="background-color: #BFBFBF; text-decoration: none;">
                <div class="card-body row">
                    <div class="d-flex align-items-center justify-content-center gap-3 mb-3">
                        <h4 class="card-title">Surat Izin Magang</h4>
                    </div>
                    <div class="d-flex align-items-center justify-content-center">
                        <h5 class="card-title">{{ $srt_magang }}</h5>
                    </div>
                    <div class="container-fluid">
                        <div class="border-top my-3 border-dark"></div>
                    </div>
                    <div class="d-flex align-items-center justify-content-center">
                        <h5 class="m-0 card-title">Jumlah Surat Masuk</h5>
                    </div>
                </div>
            </a>
            <a href="/srt_izin_plt/admin" class="card flex-even" style="background-color: #D1FAF8; text-decoration: none;">
                <div class="card-body row">
                    <div class="d-flex align-items-center justify-content-center gap-3 mb-3">
                        <h4 class="card-title">Surat Izin Penelitian</h4>
                    </div>
                    <div class="d-flex align-items-center justify-content-center">
                        <h5 class="card-title">{{ $srt_izin_plt }}</h5>
                    </div>
                    <div class="container-fluid">
                        <div class="border-top my-3 border-dark"></div>
                    </div>
                    <div class="d-flex align-items-center justify-content-center">
                        <h5 class="m-0 card-title">Jumlah Surat Masuk</h5>
                    </div>
                </div>
            </a>
        </div>
        <div class="d-flex gap-5 justify-content-center align-items-stretch align-content-center">
            <a href="/srt_pmhn_kmbali_biaya/admin" class="card flex-even" style="background-color: #DDE5E9; text-decoration: none;">
                <div class="card-body row">
                    <div class="d-flex align-items-center justify-content-center gap-3 mb-3">
                        <h4 class="card-title">Surat Permohonan Pengembalian Biaya Pendidikan</h4>
                    </div>
                    <div class="d-flex align-items-center justify-content-center">
                        <h5 class="card-title">{{ $srt_pmhn_kmbali_biaya }}</h5>
                    </div>
                    <div class="container-fluid">
                        <div class="border-top my-3 border-dark"></div>
                    </div>
                    <div class="d-flex align-items-center justify-content-center">
                        <h5 class="m-0 card-title">Jumlah Surat Masuk</h5>
                    </div>
                </div>
            </a>
            <a href="/srt_bbs_pnjm/admin" class="card flex-even" style="background-color: #FAEEAF; text-decoration: none;">
                <div class="card-body row">
                    <div class="d-flex align-items-center justify-content-center gap-3 mb-3">
                        <h4 class="card-title">Surat Keterangan Bebas Pinjam</h4>
                    </div>
                    <div class="d-flex align-items-center justify-content-center">
                        <h5 class="card-title">{{ $srt_bbs_pnjm }}</h5>
                    </div>
                    <div class="container-fluid">
                        <div class="border-top my-3 border-dark"></div>
                    </div>
                    <div class="d-flex align-items-center justify-content-center">
                        <h5 class="m-0 card-title">Jumlah Surat Masuk</h5>
                    </div>
                </div>
            </a>
            <a href="/legalisir/admin/ditempat/ijazah" class="card flex-even" style="background-color: #EDEDED; text-decoration: none;">
                <div class="card-body row">
                    <div class="d-flex align-items-center justify-content-center gap-3 mb-3">
                        <h4 class="card-title">Legalisir</h>
                    </div>
                    <div class="d-flex align-items-center justify-content-center">
                        <h5 class="card-title">{{ $legalisir }}</h5>
                    </div>
                    <div class="container-fluid">
                        <div class="border-top my-3 border-dark"></div>
                    </div>
                    <div class="d-flex align-items-center justify-content-center">
                        <h5 class="m-0 card-title">Jumlah Surat Masuk</h5>
                    </div>
                </div>
            </a>
            <a href="/riwayat_srt/admin/srt_mhw_asn" class="card flex-even" style="background-color: #C7FAB9; text-decoration: none;">
                <div class="card-body row">
                    <div class="d-flex align-items-center justify-content-center gap-3 mb-3">
                        <h4 class="card-title">Surat Selesai</h4>
                    </div>
                    <div class="d-flex align-items-center justify-content-center">
                        <h5 class="card-title">{{ $total_surat }}</h5>
                    </div>
                    <div class="container-fluid">
                        <div class="border-top my-3 border-dark"></div>
                    </div>
                    <div class="d-flex align-items-center justify-content-center">
                        <h5 class="m-0 card-title">Jumlah Surat Masuk</h5>
                    </div>
                </div>
            </a>
        </div>
        <div class="d-flex gap-5 justify-content-center align-items-stretch align-content-center mt-5">
            <a href="/admin/verif_user" class="card flex-even" style="background-color: #5bc3ca; text-decoration: none;">
                <div class="card-body row">
                    <div class="d-flex align-items-center justify-content-center gap-3 mb-3">
                        <h4 class="card-title">Akun Mahasiswa yang belum terverifikasi</h4>
                    </div>
                    <div class="d-flex align-items-center justify-content-center">
                        <h5 class="card-title">{{ $verif_akun }}</h5>
                    </div>
                    <div class="container-fluid">
                        <div class="border-top my-3 border-dark"></div>
                    </div>
                    <div class="d-flex align-items-center justify-content-center">
                        <h5 class="m-0 card-title">Jumlah akun</h5>
                    </div>
                </div>
            </a>
        </div>
    </div>
@endsection

