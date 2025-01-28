@extends('admin.layout')

@section('content')
    <div class="container-fluid p-5">
        <div class="card mx-3">
            <div class="mx-3">
                <div class="card d-inline-block intersection-card">
                    <div class="card-body d-flex gap-2 align-items-center">
                        <img src="{{ asset('asset/icons/big mail.png') }}" alt="big mail" class="heading-image">
                        <p class="heading-card">RIWAYAT LEGALISIR</p>
                    </div>
                </div>
                <br>
                <div class="card">
                    <div class="card-body">
                        <div class="container">
                            <div class="row g-2 justify-content-center">
                                <div class="col-md-3 col-sm-6">
                                    <a class="btn btn-secondary w-100" href="/riwayat_srt/admin/srt_mhw_asn">Keterangan Masih Kuliah</a>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <a class="btn btn-secondary w-100" href="/riwayat_srt/admin/srt_masih_mhw">Keterangan Mahasiswa</a>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <a class="btn btn-secondary w-100" href="/riwayat_srt/admin/srt_izin_plt">Penelitian</a>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <a class="btn btn-secondary w-100" href="/riwayat_srt/admin/legalisir">Legalisir</a>
                                </div>
                            </div>

                            <div class="row g-2 justify-content-center mt-2">
                                <div class="col-md-4 col-sm-6">
                                    <a class="btn btn-secondary w-100" href="/riwayat_srt/admin/srt_pmhn_kmbali_biaya">Pengembalian Biaya Pendidikan</a>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <a class="btn btn-secondary w-100" href="/riwayat_srt/admin/srt_bbs_pnjm">Bebas Pinjam</a>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <a class="btn btn-secondary w-100" href="/riwayat_srt/admin/srt_magang">Magang</a>
                                </div>
                            </div>
                        </div>
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
