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
            @if ($srt_mhw_asn)
                @if ($srt_mhw_asn->role_surat == 'mahasiswa')
                    <table class="table table-striped text-center">
                        <thead>
                            <tr>
                                <th class="col-md-1">No Surat</th>
                                <th class="col-md-1">Nama Mahasiswa</th>
                                <th class="col-md-1">NIM</th>
                                <th class="col-md-1">Periode</th>
                                <th class="col-md-1">Tanggal Surat</th>
                                <th class="col-md-1">File Surat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $srt_mhw_asn->no_surat }}</td>
                                <td>{{ $srt_mhw_asn->nama_mhw }}</td>
                                <td>{{ $srt_mhw_asn->nim_nip }}</td>
                                <td>{{ $srt_mhw_asn->thn_awl }} / {{ $srt_mhw_asn->thn_akh }}</td>
                                <td>{{ $srt_mhw_asn->tanggal_surat }}</td>
                                <td>
                                    <a href='{{ url('/legal/srt_mhw_asn/view/' . $srt_mhw_asn->id) }}'
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
