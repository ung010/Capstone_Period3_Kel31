@extends('admin.layout')

@section('content')
    <div class="container-fluid p-5">
        <div class="card d-inline-block intersection-card">
            <div class="card d-inline-block intersection-card">
                <div class="card-body d-flex gap-2 align-items-center">
                    <img src="{{ asset('asset/icons/big admin.png') }}" alt="big admin" class="heading-image">
                    <p class="heading-card">MANAJEMEN USER</p>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body my-3 px-5">
                <div class="d-flex justify-content-center align-items-center align-content-center">
                    <p class="heading-card">CEK DATA</p>
                </div>
                <div class="d-flex flex-column">
                    <h4>Nama : {{ $user->nama }}</h4>
                    <p>NIM: {{ $user->nmr_unik }}</p>
                    <p>Tempat Tanggal Lahir: {{ $user->ttl }}</p>
                    <p>Nama Ibu: {{ $user->nama_ibu }}</p>
                    <p>Jenjang Pendidikan: {{ $user->nama_jnjg }}</p>
                    <p>Departemen: {{ $user->nama_dpt }}</p>
                    <p>Prodgam Studi: {{ $user->jenjang_prodi }}</p>
                    <p>Alamat Asal: {{ $user->almt_asl }}</p>
                    <p>No Whatsapp: {{ $user->nowa }}</p>
                    <p>Status:
                        {{ $user->role === 'non_mahasiswa' ? 'Mahasiswa' : ($user->role === 'non_alumni' ? 'Alumni' : '') }}
                    </p>
                    <p>Kartu Tanda Mahasiswa</p>
                    <img src="{{ $user->foto ? asset('storage/foto/mahasiswa/' . $user->foto) : asset('asset/Rectangle 8.png') }}"
                        alt="ktm" class="w-50">
                </div>
                <br>
                <div class="d-flex justify-content-center align-items-center align-content-center gap-3">
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#catatanModal">Ditolak</button>
                    <form action="{{ route('admin.verifikasi.user', $user->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">Disetujui</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="catatanModal" tabindex="-1" aria-labelledby="catatanModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('admin.catatan', $user->id) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="catatanModalLabel">Tambah Catatan</h5>
                            <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="catatan_user" class="form-label">Catatan dari admin</label>
                                <textarea class="form-control" id="catatan_user" name="catatan_user" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Kirim</button>
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
        });
    </script>
@endsection
