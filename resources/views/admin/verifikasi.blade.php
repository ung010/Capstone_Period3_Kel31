@extends('admin.layout')

@section('content')
    <div class="container-fluid p-5">
        <div class="card mx-5">
            <div class="mx-5">
                <div class="card d-inline-block intersection-card">
                    <div class="card-body d-flex gap-2 align-items-center">
                        <img src="{{ asset('asset/icons/big admin.png') }}" alt="big admin" class="heading-image">
                        <p class="heading-card">VERIFIKASI DATA MAHASISWA</p>
                    </div>
                </div>
            </div>
            <div class="card-body my-3 d-flex flex-column">
                <div class="d-flex justify-content-center align-items-center align-content-center flex-column gap-5">
                    <table class="table table-responsive" id="table">
                        <thead>
                    <tr>
                        <th class="col-md-1">No</th>
                        <th class="col-md-1">Nama Mahasiswa</th>
                        <th class="col-md-1">NIM</th>
                        <th class="col-md-1">Departemen</th>
                        <th class="col-md-2">Program Studi</th>
                        <th class="col-md-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach ($data as $item)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->nim_nip }}</td>
                            <td>{{ $item->nama_dpt }}</td>
                            <td>{{ $item->nama_prd }}</td>
                            <td>
                                <a href='{{ url('/admin/verif_user/cekdata/'.$item->id) }}' class="btn btn-warning btn-sm">Cek Data</a>
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

