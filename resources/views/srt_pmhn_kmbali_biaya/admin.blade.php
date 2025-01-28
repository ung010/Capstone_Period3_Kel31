@extends('admin.layout')

@section('content')
    <div class="container-fluid p-5">
        <div class="card mx-5">
            <div class="mx-5">
                <div class="card d-inline-block intersection-card">
                    <div class="card-body d-flex gap-2 align-items-center">
                        <img src="{{ asset('asset/icons/big mail.png') }}" alt="big mail" class="heading-image">
                        <p class="heading-card">SURAT PERMOHONAN PENGEMBALIAN BIAYA PENDIDIKAN</p>
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
                                {{-- <th>Status</th> --}}
                                {{-- <th>Unduh</th>
                                <th>Dikirim</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $item->nama_mhw }}</td>
                                    <td>
                                        <a href='{{ url('/srt_pmhn_kmbali_biaya/admin/cek_surat/' . $item->id) }}'
                                            class="btn btn-warning btn-sm">Cek Data</a>
                                    </td>
                                    {{-- <td>
                                        @if ($item->role_surat == 'admin')
                                            Verif Admin
                                        @elseif ($item->role_surat == 'supervisor_akd')
                                            Verif Supervisor Akademik
                                        @elseif ($item->role_surat == 'manajer')
                                            Verif Manajer
                                        @elseif ($item->role_surat == 'wd2')
                                            Verif Wakil Dekan 2
                                        @else
                                            Ditolak
                                        @endif
                                    </td> --}}
                                    {{-- <td>
                                        @if ($item->role_surat == 'manajer_sukses')
                                            <a href='{{ url('/srt_pmhn_kmbali_biaya/admin/download/' . $item->id) }}'
                                                class="btn btn-primary btn-sm">Unduh</a>
                                        @else
                                            <button class="btn btn-secondary btn-sm" disabled>Unduh</button>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->role_surat == 'manajer_sukses')
                                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#uploadModal" data-id="{{ $item->id }}"
                                                data-nama="{{ $item->nama_mhw }}">
                                                Unggah Surat
                                            </button>
                                        @else
                                            <button class="btn btn-secondary btn-sm" disabled>Unggah</button>
                                        @endif
                                    </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

     {{-- <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadModalLabel">Unggah Surat Permohonan Pengembalian Biaya Pendidikan</h5>
                        <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <form action="" method="POST" enctype="multipart/form-data" id="uploadForm">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="srt_pmhn_kmbali_biaya" class="form-label">Unggah Surat</label>
                                <input type="file" name="srt_pmhn_kmbali_biaya" id="srt_pmhn_kmbali_biaya"
                                    class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Unggah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div> --}}
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#table').DataTable();
        });
    </script>

    {{-- <script>
        $('#uploadModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var nama = button.data('nama');

            var modal = $(this);
            var form = modal.find('form');
            var action = "{{ route('srt_pmhn_kmbali_biaya.admin_unggah', ['id' => ':id']) }}";

            form.attr('action', action.replace(':id', id));
            modal.find('.modal-title').text('Unggah Surat Permohonan Pengembalian Biaya Pendidikan: ' + nama);
        });
    </script> --}}
@endsection
