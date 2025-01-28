@extends('legal.layout')

@section('content')
    <div class="d-flex flex-column justify-content-center align-items-center gap-3"
        style="margin-top: 2%; margin-left: 5%; margin-right: 5%;">
        <div class="position-relative w-100" style="overflow: hidden;">
            <img src="{{ asset('asset/Mask group.png') }}" alt="header" class="w-100">

            <!-- Overlay dengan transparansi -->
            <div class="position-absolute top-0 start-0 w-100 h-100" style="background-color: rgba(0, 0, 0, 0.5);"></div>

            <h2 class="position-absolute top-50 start-50 translate-middle text-white" style="font-size: 2.5rem;">Cek Legalitas Surat</h2>
        </div>
        <br>

        <div class="container-fluid">
            @if ($srt_masih_mhw)
                @if ($srt_masih_mhw->role_surat == 'mahasiswa')
                    <table class="table table-striped text-center">
                        <thead>
                            <tr>
                                <th class="col-md-1">No Surat</th>
                                <th class="col-md-1">Nama Mahasiswa</th>
                                <th class="col-md-1">NIM</th>
                                <th class="col-md-1">Program Studi</th>
                                <th class="col-md-1">Alasan Surat</th>
                                <th class="col-md-1">Tanggal Surat</th>
                                <th class="col-md-1">File Surat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $srt_masih_mhw->no_surat }}</td>
                                <td>{{ $srt_masih_mhw->nama_mhw }}</td>
                                <td>{{ $srt_masih_mhw->nim_nip }}</td>
                                <td>{{ $srt_masih_mhw->nama_prd }}</td>
                                <td>{{ $srt_masih_mhw->tujuan_buat_srt }}</td>
                                <td>{{ $srt_masih_mhw->tanggal_surat }}</td>
                                @if ($srt_masih_mhw->tujuan_akhir == 'wd')
                                <td>
                                    <a href='{{ url('/legal/srt_masih_mhw/view/wd/' . $srt_masih_mhw->id) }}'
                                        class="btn btn-primary btn-sm">Lihat Surat</a>
                                </td>
                                @else
                                <td>
                                    <a href='{{ url('/legal/srt_masih_mhw/view/manajer/' . $srt_masih_mhw->id) }}'
                                        class="btn btn-primary btn-sm">Lihat Surat</a>
                                </td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                @else
                    <h3>Surat belum legal</h3>
                @endif
            @else
                <h3>Surat kosong</h3>
            @endif
        </div>
    </div>
@endsection
