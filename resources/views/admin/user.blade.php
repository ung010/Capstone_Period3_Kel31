@extends('admin.layout')

@section('content')
    <div class="container-fluid p-5">
        <div class="card mx-5">
            <div class="mx-5">
                <div class="card d-inline-block intersection-card">
                    <div class="card-body d-flex gap-2 align-items-center">
                        <img src="{{ asset('asset/icons/big admin.png') }}" alt="big admin" class="heading-image">
                        <p class="heading-card">MANAJEMEN USER</p>
                    </div>
                </div>
            </div>
            <div class="card-body my-3 d-flex flex-column">
                <div class="d-flex justify-content-center align-items-center align-content-center flex-column gap-5">
                    <table class="table table-responsive" id="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Mahasiswa</th>
                                <th>NIM</th>
                                <th>Departemen</th>
                                <th>Program Studi</th>
                                <th>No Handphone</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->nmr_unik }}</td>
                                    <td>{{ $item->nama_dpt }}</td>
                                    <td>{{ $item->nama_prd }}</td>
                                    <td>{{ $item->nowa }}</td>
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#editUser" data-id="{{ $item->id }}"
                                            data-nama="{{ $item->nama }}" data-email="{{ $item->email }}"
                                            data-status="{{ $item->status }}">Edit</button>
                                        <form action="{{ route('admin.soft_delete', $item->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus sementara akun ini?')">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="float: right" align="right" class="p-3">
                    <button type="button"
                        class="btn btn-light rounded-circle border border-3 d-flex justify-content-center align-items-center align-content-center"
                        style="width: 60px; height: 60px" data-bs-toggle="modal" data-bs-target="#deletedUser">
                        <img src="{{ asset('asset/icons/big trash.png') }}" alt="big trash">
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editUser" tabindex="-1" role="dialog" aria-labelledby="editUserLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserLabel">Edit User</h5>
                    <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" enctype="multipart/form-data" id="uploadForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="status" class="form-label">Status Mahasiswa</label>
                            <select name="status" id="status" class="form-select">
                                <option value="mahasiswa">Mahasiswa Aktif</option>
                                <option value="alumni">Alumni</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" id="edit-email">
                        </div>
                        <div class="form-group">
                            <label for="password" class="form-label">Password (Kosongkan jika tidak ingin mengubah)</label>
                            <input type="password" name="password" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-warning">Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deletedUser" tabindex="-1" role="dialog" aria-labelledby="deletedUserLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deletedUserLabel">Deleted User Account</h5>
                    <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-responsive" id="tableDeleted">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Mahasiswa</th>
                                <th>NIM</th>
                                <th>Departemen</th>
                                <th>Program Studi</th>
                                <th>No Handphone</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach ($dataDeleted as $item)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->nmr_unik }}</td>
                                    <td>{{ $item->nama_dpt }}</td>
                                    <td>{{ $item->nama_prd }}</td>
                                    <td>{{ $item->nowa }}</td>
                                    <td>
                                        <form action="{{ route('admin.restore', $item->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin mengembalikan akun ini?')">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">Restore</button>
                                        </form>
                                        <form onsubmit="return confirm('Yakin ingin menghapus permanen akun ini?')"
                                            class="d-inline" action="{{ route('admin.hard_delete', $item->id) }}"
                                            method="post">
                                            @csrf
                                            <button type="submit" name="submit"
                                                class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    @endsection

    @section('script')
        <script>
            $(document).ready(function() {
                $('#table').DataTable();
                $('#tableDeleted').DataTable();
            });
        </script>

        <script>
            $('#editUser').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var nama = button.data('nama');
                var email = button.data('email');
                var status = button.data('status');

                $('#edit-email').val(email);

                $('#status').val(status);

                var modal = $(this);
                var form = modal.find('form');
                var action = "{{ route('admin.update', ['id' => ':id']) }}";

                form.attr('action', action.replace(':id', id));
                modal.find('.modal-title').text('Edit User: ' + nama);
            });
        </script>
    @endsection
