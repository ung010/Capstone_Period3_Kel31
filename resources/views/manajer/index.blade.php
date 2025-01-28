@extends('manajer.layout')

@section('content')
    <div class="container-fluid p-0">
        <div class="d-flex gap-5 justify-content-center align-items-stretch align-content-center mb-5">
            <a href="/srt_mhw_asn/manajer" class="card flex-even" style="background-color: #FFC2AF; text-decoration: none;">
                <div class="card-body row">
                    <div class="d-flex align-items-center  gap-3 mb-3">
                        <h4 class="card-title">Surat Keterangan Masih Kuliah (Bagi ASN)</h4>
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
            <a href="/srt_masih_mhw/manajer" class="card flex-even" style="background-color: #EDEDED; text-decoration: none;">
                <div class="card-body row">
                    <div class="d-flex align-items-center  gap-3 mb-3">
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
            <a href="/srt_magang/manajer" class="card flex-even" style="background-color: #BFBFBF; text-decoration: none;">
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
            <a href="/srt_izin_plt/manajer" class="card flex-even" style="background-color: #D1FAF8; text-decoration: none;">
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
            <a href="/srt_pmhn_kmbali_biaya/manajer" class="card flex-even" style="background-color: #DDE5E9; text-decoration: none;">
                <div class="card-body row">
                    <div class="d-flex align-items-center justify-content-center gap-3 mb-3">
                        <h4 class="card-title text-center">Surat Permohonan Pengembalian Biaya Pendidikan</h4>
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
            <a href="/riwayat_srt/manajer/srt_mhw_asn" class="card flex-even" style="background-color: #C7FAB9; text-decoration: none;">
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
            {{-- <a href="/legalisir/manajer/ditempat/ijazah" class="card flex-even" style="background-color: #FAEEAF; text-decoration: none;">
                <div class="card-body row">
                    <div class="d-flex align-items-center justify-content-center gap-3 mb-3">
                        <h4 class="card-title">Legalisir</h4>
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
            </a> --}}
        </div>
    </div>
@endsection

