@extends('supervisor_akd.layout')

@section('content')
    <div class="container-fluid p-5">
        <div class="card mx-5">
            <div class="mx-5">
                <div class="card d-inline-block intersection-card">
                    <div class="card-body d-flex gap-2 align-items-center">
                        <img src="{{ asset('asset/icons/big mail.png') }}" alt="big mail" class="heading-image">
                        <p class="heading-card">LEGALISIR DIKIRIM</p>
                    </div>
                </div>
                <br>
                <div class="card d-inline-block">
                    <div class="card-header d-flex align-items-center gap-2">
                        <a class="btn btn-secondary btn-fixed-size" href="/legalisir/sv/dikirim/ijazah">Ijazah</a>
                        <a class="btn btn-secondary btn-fixed-size" href="/legalisir/sv/dikirim/transkrip">Transkrip</a>
                        <a class="btn btn-secondary btn-fixed-size" href="/legalisir/sv/dikirim/ijz_trs">Ijazah dan Transkrip</a>
                    </div>
                </div>
            </div>
            <div class="card-body my-3">
                <div class="d-flex justify-content-center align-items-center align-content-center">
                    <table class="table table-responsive" id="table">
                        <thead>
                            <tr>
                                <th class="col-md-1">No</th>
                                <th class="col-md-1">Nama Mahasiswa</th>
                                <th class="col-md-1">NIM</th>
                                <th class="col-md-1">Program Studi</th>
                                <th class="col-md-1">Keperluan</th>
                                <th class="col-md-1">Cek Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $item->nama_mhw }}</td>
                                    <td>{{ $item->nim_nip }}</td>
                                    <td>{{ $item->nama_prd }}</td>
                                    <td>{{ $item->keperluan }}</td>
                                    <td>
                                        <a href="{{ url('/legalisir/sv/dikirim/ijazah/cek_legal/' . $item->id) }}"
                                            class="btn btn-warning btn-sm">Cek Data</a>
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
