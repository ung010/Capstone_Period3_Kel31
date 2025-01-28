@extends('manajer.layout')

@section('content')
    <div class="container-fluid p-5">
        <div class="card mx-5">
            <div class="mx-5">
                <div class="card d-inline-block intersection-card">
                    <div class="card-body d-flex gap-2 align-items-center">
                        <img src="{{ asset('asset/icons/big admin.png') }}" alt="big admin" class="heading-image">
                        <p class="heading-card">MANAJEMEN SUPERVISOR</p>
                    </div>
                </div>
            </div>
            <div class="card-body my-3">
                <div class="container-fluid">
                    <table class="table table-responsive" id="table">
                        <thead>
                            <tr>
                                <th class="col-md-1">No</th>
                                <th class="col-md-1">Nama Admin</th>
                                <th class="col-md-1">NIP</th>
                                <th class="col-md-1">Email</th>
                                <th class="col-md-1">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->nim_nip }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#editUser" data-id="{{ $item->id }}"
                                            data-nama="{{ $item->nama }}" data-email="{{ $item->email }}"
                                            data-nip="{{ $item->nim_nip }}">
                                            Edit
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editUser" tabindex="-1" role="dialog" aria-labelledby="editUserLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-center align-items-center align-content-center">
                    <h5 class="modal-title" id="editUserLabel">Edit</h5>
                </div>
                <form action="" method="POST" enctype="multipart/form-data" id="uploadForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" id="edit-nama">
                        </div>
                        <div class="form-group">
                            <label for="nim_nip" class="form-label">NIP</label>
                            <input type="number" name="nim_nip" class="form-control" id="edit-nim_nip">
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" id="edit-email">
                        </div>
                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" id="password-edit">
                        </div>
                        <div class="form-group">
                            <label for="password" class="form-label">Konfirmasi Password</label>
                            <input type="password" id="confirm-password-edit" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center align-items-center align-content-center">
                        <button type="submit" class="btn btn-warning">Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="create_akd_modal" tabindex="-1" aria-labelledby="create_akd_modalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('supervisor_akd.create_akd') }}" method="POST" id="register-form">
                    @csrf
                    <div class="modal-header d-flex justify-content-center align-items-center align-content-center">
                        <h5 class="modal-title" id="create_akd_modalLabel">Tambah Admin</h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" id="edit-nama">
                        </div>
                        <div class="form-group">
                            <label for="nim_nip" class="form-label">NIP</label>
                            <input type="number" name="nim_nip" class="form-control" id="edit-nim_nip">
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" id="edit-email">
                        </div>
                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" id="password">
                        </div>
                        <div class="form-group">
                            <label for="password" class="form-label">Konfirmasi Password</label>
                            <input type="password" id="confirm-password" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center align-items-center align-content-center">
                        <button type="submit" class="btn btn-success">Tambah</button>
                    </div>
                </form>
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
            var nip = button.data('nip');
            var email = button.data('email');

            $('#edit-nama').val(nama);
            $('#edit-nim_nip').val(nip);
            $('#edit-email').val(email);

            var modal = $(this);
            var form = modal.find('form');
            var action = "{{ route('manajer.edit_spv', ['id' => ':id']) }}";

            form.attr('action', action.replace(':id', id));
            modal.find('.modal-title').text('Edit Admin: ' + nama);
        });
    </script>
@endsection
