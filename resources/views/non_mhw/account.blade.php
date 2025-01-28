@extends('user.layout')

@section('content')
    <div class="d-flex justify-content-center align-items-center gap-4" style="margin-top: 1%;">
        <div class="card">
            <div class="card-body p-5 row">
                <div class="d-flex justify-content-center align-items-center">
                    <h3>DATA DIRI</h3>
                </div>
                <form action='{{ route('non_mhw.account_non_mhw', $user->id) }}' method='post' enctype="multipart/form-data"
                    class="row">
                    @csrf
                    <div class="col-6">
                        <div class="d-flex flex-column gap-2">
                            <div class="form-group">
                                <label for="">Email</label>
                                <input type="email" name="email" value="{{ $user->email }}" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Password</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Nama</label>
                                <input type="text" name="nama" value="{{ $user->nama }}" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">NIM</label>
                                <input type="number" name="nim_nip" value="{{ $user->nim_nip }}" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Nama Ibu</label>
                                <input type="text" name="nama_ibu" value="{{ $user->nama_ibu }}" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">No Handphone</label>
                                <input type="text" name="nowa" value="{{ $user->nowa }}" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Alamat Asal</label>
                                <input type="text" name="almt_asl" value="{{ $user->almt_asl }}" class="form-control">
                            </div>
                            <div class="mt-3">
                                <h5>Alasan penolakan: {{ $user->catatan_user }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-column gap-2">
                            <div class="form-group">
                                <label for="">Tempat Lahir</label>
                                <input type="text" name="kota" value="{{ $user->kota }}" class="form-control">
                            </div>
                            <div class="form-group d-flex">
                                <label for="" class="col-4">Tanggal Lahir</label>
                                <div class="col-4">
                                    <input type="date" name="tanggal_lahir" value="{{ $user->tanggal_lahir }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-6">
                                    <label for="prd_id">Program Studi</label>
                                    <select class="form-select" name='prd_id' id="prd_id">
                                        <option value="" selected>Select Option</option>
                                        @foreach ($prodi as $prd)
                                            <option value="{{ $prd->id }}" data-dept-id="{{ $prd->dpt_id }}"
                                                {{ $prd->id == $user->prd_id ? 'selected' : '' }}>
                                                {{ $prd->nama_prd }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label for="">Departemen</label>
                                    <select class="form-select hide-select-arrow" name='dpt_id' id="dpt_id" disabled>
                                        <option value="" selected>Select Option</option>
                                        @foreach ($departemen as $dpt)
                                            <option value="{{ $dpt->id }}"
                                                {{ $dpt->id == $user->prodi->departement->id ? 'selected' : '' }}>
                                                {{ $dpt->nama_dpt }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-6 d-flex flex-column gap-1">
                                    <div class="d-flex gap-2">
                                        <input type="radio" name="status" value="mahasiswa"
                                            {{ $user->status == 'mahasiswa' ? 'checked' : '' }}> Mahasiswa Aktif
                                    </div>
                                    <div class="d-flex gap-2">
                                        <input type="radio" name="status" value="alumni"
                                            {{ $user->status == 'alumni' ? 'checked' : '' }}> Alumni
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="foto" class="btn btn-secondary">Ganti Gambar</label>
                                <input type="file" name="foto" id="foto" class="d-none" accept="image/*">
                            </div>
                            <div class="mt-3">
                                <img id="img-preview" src="#" alt="Preview Image" class="img-fluid"
                                    style="display: none; max-height: 200px;">
                                <img id="img-old" src="{{ asset('storage/foto/mahasiswa/' . $user->foto) }}"
                                    alt="identitas" style="max-width: 280px;">
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-3">
                        <a href="/non_user" class="btn btn-primary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            var selectedProdi = $('#prd_id').find(':selected');
            if (selectedProdi.length) {
                var deptId = selectedProdi.data('dept-id');
                $('#dpt_id').val(deptId);
            }

            $('#prd_id').change(function() {
                var selectedProdi = $(this).find(':selected');
                var deptId = selectedProdi.data('dept-id');
                $('#dpt_id').val(deptId);
            });
        });
    </script>

    <script>
        document.getElementById('foto').addEventListener('change', function(event) {
            const fileInput = event.target;
            const preview = document.getElementById('img-preview');
            const oldImage = document.getElementById('img-old');

            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    oldImage.style.display = 'none';
                }

                reader.readAsDataURL(fileInput.files[0]);
            }
        });
    </script>
@endsection
