@extends('admin.layout')

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
                        <a class="btn btn-secondary btn-fixed-size" href="/legalisir/admin/dikirim/ijazah">Ijazah</a>
                        <a class="btn btn-secondary btn-fixed-size" href="/legalisir/admin/dikirim/transkrip">Transkrip</a>
                        <a class="btn btn-secondary btn-fixed-size" href="/legalisir/admin/dikirim/ijz_trs">Ijazah dan Transkrip</a>
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
                                <th>Cek Data</th>
                                <th>Status</th>
                                <th>Unduh</th>
                                <th>No Resi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $item->nama_mhw }}</td>
                                    <td>
                                        @if ($item->role_surat == 'admin')
                                            <a href="{{ url('/legalisir/admin/dikirim/ijz_trs/cek_legal/' . $item->id) }}"
                                                class="btn btn-warning btn-sm">Cek Data</a>
                                        @else
                                            <button class="btn btn-secondary btn-sm" disabled>Cek Data</button>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->role_surat == 'admin')
                                            Menunggu Verifikasi Admin
                                        @elseif ($item->role_surat == 'supervisor_akd')
                                            Menunggu Verifikasi Supervisor Akademik
                                        @elseif ($item->role_surat == 'dekan')
                                            Supervisor telah melakukann verifikasi
                                        @else
                                            Ditolak
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->role_surat == 'dekan')
                                            <a href="{{ url('/legalisir/admin/dikirim/ijz_trs/download/' . $item->id) }}"
                                                class="btn btn-primary btn-sm">Unduh</a>
                                        @else
                                            <button class="btn btn-secondary btn-sm" disabled>Unduh</button>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->role_surat == 'dekan')
                                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                                                data-target="#uploadModal" data-id="{{ $item->id }}"
                                                data-nama="{{ $item->nama_mhw }}">
                                                Kirim
                                            </button>
                                        @else
                                            <button class="btn btn-secondary btn-sm" disabled>Kirim</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadModalLabel">No Resi Pengiriman</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ isset($item) ? route('legalisir_admin.admin_dikirim_ijz_trs_resi', $item->id) : '#' }}"
                        method="POST" id="uploadForm">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="no_resi">No Resi</label>
                                <input type="text" class="form-control" id="no_resi" name="no_resi" required>
                            </div>
                            <input type="hidden" id="id" name="id">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#table').DataTable();
        });
    </script>
@endsection
