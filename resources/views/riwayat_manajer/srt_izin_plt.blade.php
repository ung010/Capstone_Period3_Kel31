@extends('wd1.layout')

@section('content')
    <div class="container-fluid p-5">
        <div class="card mx-5">
            <div class="mx-5">
                <div class="card d-inline-block intersection-card">
                    <div class="card-body d-flex gap-2 align-items-center">
                        <img src="{{ asset('asset/icons/big mail.png') }}" alt="big mail" class="heading-image">
                        <p class="heading-card">RIWAYAT SURAT IZIN PENELITIAN</p>
                    </div>
                </div>
                <br>
                <div class="card d-inline-block">
                    <div class="card-header d-flex align-items-center gap-2">
                        <a class="btn btn-secondary btn-fixed-size" href="/riwayat_srt/manajer/srt_mhw_asn">Keterangan Masih Kuliah</a>
                        <a class="btn btn-secondary btn-fixed-size" href="/riwayat_srt/manajer/srt_masih_mhw">Keterangan Mahasiswa</a>
                        <a class="btn btn-secondary btn-fixed-size" href="/riwayat_srt/manajer/srt_magang">Magang</a>
                        <a class="btn btn-secondary btn-fixed-size" href="/riwayat_srt/manajer/srt_izin_plt">Penelitian</a>
                        <a class="btn btn-secondary btn-fixed-size" href="/riwayat_srt/manajer/srt_pmhn_kmbali_biaya">Pengembalian Biaya</a>
                    </div>
                </div>
            </div>
            <div class="card-body my-3">
                <div class="d-flex justify-content-center align-items-center align-content-center">
                    <table class="table table-responsive" id="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Mahasiswa</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $item->nama_mhw }}</td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" disabled>Selesai</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#table').DataTable();
        });
    </script>
@endsection

