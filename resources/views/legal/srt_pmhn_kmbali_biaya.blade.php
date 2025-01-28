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
            @if ($srt_pmhn_kmbali_biaya)
                @if ($srt_pmhn_kmbali_biaya->role_surat == 'mahasiswa')
                    <table class="table table-striped text-center">
                        <thead>
                            <tr>
                                <th class="col-md-1">No Surat</th>
                                <th class="col-md-1">Nama Mahasiswa</th>
                                <th class="col-md-1">NIM</th>
                                <th class="col-md-1">Program Studi</th>
                                <th class="col-md-1">Tanggal Surat</th>
                                <th class="col-md-1">File Surat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $srt_pmhn_kmbali_biaya->no_surat }}</td>
                                <td>{{ $srt_pmhn_kmbali_biaya->nama_mhw }}</td>
                                <td>{{ $srt_pmhn_kmbali_biaya->nim_nip }}</td>
                                <td>{{ $srt_pmhn_kmbali_biaya->nama_prd }}</td>
                                <td>{{ $srt_pmhn_kmbali_biaya->tanggal_surat }}</td>
                                <td>
                                    <a href='{{ url('/legal/srt_pmhn_kmbali_biaya/view/' . $srt_pmhn_kmbali_biaya->id) }}'
                                        class="btn btn-primary btn-sm">Lihat Surat</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                @else
                    <h3>Kosong</h3>
                @endif
            @else
                <h3>Kosong</h3>
            @endif
        </div>
    </div>
@endsection
