@extends('user.layout')

@section('content')
    <div class="d-flex flex-column justify-content-center align-items-center gap-3"
        style="margin-top: 2%; margin-left: 5%; margin-right: 5%;">
        <div class="position-relative w-100" style="overflow: hidden;">
            <img src="{{ asset('asset/Mask group.png') }}" alt="header" class="w-100">
            <!-- Overlay dengan transparansi -->
            <div class="position-absolute top-0 start-0 w-100 h-100" style="background-color: rgba(0, 0, 0, 0.5);">
            </div>
            <h2 class="position-absolute top-50 start-50 translate-middle text-white text-center" style="font-size: 2.5rem; ; white-space: nowrap;">
                Legalisir
            </h2>
        </div>
    </div>

    <div class="d-flex mt-5 mb-3" style="margin-left: 5%; margin-right: 5%;">
        <div class="card">
            <div class="card-body p-3">
                <h3>Alasan Surat Di Tolak: {{ $data->catatan_surat }}</h3>
            </div>
        </div>
    </div>

    <div class="d-flex flex-column justify-content-center align-items-center gap-3"
        style="margin-left: 5%; margin-right: 5%;">
        <div class="card w-100" id="card-tambah">
            <div class="card-body d-flex flex-column gap-3">
                <div class="d-flex justify-content-center align-items-center">
                    <h3>ISI DATA</h3>
                </div>
                <form action="{{ route('legalisir.update', $data->id) }}" method="POST" class="row px-5"
                    enctype="multipart/form-data">
                    @csrf
                    @if ($errors->any())
                        <div>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="col-6 d-flex flex-column gap-2 h-100">
                        <div class="d-flex flex-column gap-2 h-100">
                            <div class="form-group">
                            <label for="">Jenis Legalisir</label>
                                <select name="jenis_lgl" id="jenis_lgl" required class="form-select">
                                    <option value="">Select Option</option>
                                    <option value="ijazah" {{ $data->jenis_lgl == 'ijazah' ? 'selected' : '' }}>Ijazah
                                    </option>
                                    <option value="transkrip" {{ $data->jenis_lgl == 'transkrip' ? 'selected' : '' }}>
                                        Transkrip
                                    </option>
                                    <option value="ijazah_transkrip"
                                        {{ $data->jenis_lgl == 'ijazah_transkrip' ? 'selected' : '' }}>
                                        Ijazah dan Transkrip</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Nama Mahasiswa</label>
                                <input type="text" id="nama_mhw" name="nama_mhw" value="{{ $user->nama_mhw }}"
                                    class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">NIM</label>
                                <input type="number" id="nmr_unik" name="nmr_unik" value="{{ $user->nmr_unik }}"
                                    class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Departemen</label>
                                <input type="text" id="nama_dpt" name="nama_dpt" value="{{ $departemen->nama_dpt }}"
                                    class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Program Studi</label>
                                <input type="text" id="jenjang_prodi" name="jenjang_prodi"
                                    value="{{ $prodi->nama_prd }}" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Alamat Asal</label>
                                <input type="text" id="almt_asl" name="almt_asl" value="{{ $user->almt_asl }}"
                                    class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">No Whatsapp</label>
                                <input type="text" id="nowa" name="nowa" value="{{ $user->nowa }}"
                                    class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Keperluan</label>
                                <input type="text" name="keperluan" id="keperluan" class="form-control"
                                    value="{{ $data->keperluan }}">
                            </div>
                            <div class="form-group d-flex">
                                <label for="" class="col-2" style="line-height: 2;">Tanggal Lulus</label>
                                <div class="col-10">
                                    <input type="date" name="tgl_lulus" id="tgl_lulus" class="form-control"
                                        value="{{ $data->tgl_lulus }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-column gap-2">
                            <div class="form-group">
                            <label for="">Metode Ambil</label>
                                <div class="col-10">
                                    <select name="ambil" id="ambil" required class="form-select">
                                        <option value="">Select Option</option>
                                        <option value="ditempat" {{ $data->ambil == 'ditempat' ? 'selected' : '' }}>
                                            Diambil di Tempat
                                        </option>
                                        <option value="dikirim" {{ $data->ambil == 'dikirim' ? 'selected' : '' }}>Dikirim
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" id="file_ijazah_group" style="visibility:hidden">
                                <label for="" class="col-4">Ijazah</label>
                                <div class="col-8">
                                    <input type="file" name="file_ijazah" id="file_ijazah" class="form-control">
                                </div>
                            </div>
                            <div class="form-group" id="file_transkrip_group" style="visibility:hidden">
                                <label for="" class="col-4">Transkrip</label>
                                <div class="col-8">
                                    <input type="file" name="file_transkrip" id="file_transkrip"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="form-group" id="alamat_group" style="visibility:hidden;">
                                <label for="">Alamat Tujuan Pengiriman</label>
                                <input type="text" name="almt_smg" id="almt_smg" class="form-control"
                                    value="{{ $data->almt_kirim }}">
                            </div>
                            <div class="form-group" id="kodepos_group" style="visibility:hidden;">
                                <label for="" class="col-2">Kodepos</label>
                                <div class="col-10">
                                    <input type="number" name="kdps_kirim" id="kdps_kirim" class="form-control"
                                        value="{{ $data->kdps_kirim }}">
                                </div>
                            </div>
                            <div class="form-group" id="kelurahan_group" style="visibility:hidden;">
                                <label for="">Kelurahan</label>
                                <input type="text" name="klh_kirim" id="klh_kirim" class="form-control"
                                    value="{{ $data->klh_kirim }}">
                            </div>
                            <div class="form-group" id="kecamatan_group" style="visibility:hidden;">
                                <label for="">Kecamatan</label>
                                <input type="text" name="kcmt_kirim" id="kcmt_kirim" class="form-control"
                                    value="{{ $data->kcmt_kirim }}">
                            </div>
                            <div class="form-group" id="kota_group" style="visibility:hidden;">
                                <label for="">Kota / Kabupaten</label>
                                <input type="text" name="kota_kirim" id="kota_kirim" class="form-control"
                                    value="{{ $data->kota_kirim }}">
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-6">
                        <div class="d-flex flex-column gap-2">
                            <div class="form-group">
                                <label for="">Jenis Legalisir</label>
                                <select name="jenis_lgl" id="jenis_lgl" required class="form-select">
                                    <option value="">Select Option</option>
                                    <option value="ijazah" {{ $data->jenis_lgl == 'ijazah' ? 'selected' : '' }}>Ijazah
                                    </option>
                                    <option value="transkrip" {{ $data->jenis_lgl == 'transkrip' ? 'selected' : '' }}>
                                        Transkrip
                                    </option>
                                    <option value="ijazah_transkrip"
                                        {{ $data->jenis_lgl == 'ijazah_transkrip' ? 'selected' : '' }}>
                                        Ijazah dan Transkrip</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Nama Mahasiswa</label>
                                <input type="text" id="nama_mhw" name="nama_mhw" value="{{ $user->nama_mhw }}"
                                    class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Departemen</label>
                                <input type="text" id="nama_dpt" name="nama_dpt"
                                    value="{{ $departemen->nama_dpt }}" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Alamat Asal</label>
                                <input type="text" id="almt_asl" name="almt_asl" value="{{ $user->almt_asl }}"
                                    class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Alamat Tujuan Pengiriman</label>
                                <input type="text" name="almt_smg" id="almt_smg" class="form-control"
                                    value="{{ $data->almt_kirim }}">
                            </div>
                            <div class="form-group">
                                <label for="">Kecamatan</label>
                                <input type="text" name="kcmt_kirim" id="kcmt_kirim" class="form-control"
                                    value="{{ $data->kcmt_kirim }}">
                            </div>
                            <div class="form-group d-flex">
                                <label for="" class="col-2">Kodepos</label>
                                <div class="col-10">
                                    <input type="number" name="kdps_kirim" id="kdps_kirim" class="form-control"
                                        value="{{ $data->kdps_kirim }}">
                                </div>
                            </div>
                            <div class="form-group d-flex">
                                <label for="" class="col-4">Ijazah</label>
                                <div class="col-8">
                                    <input type="file" name="file_ijazah" id="file_ijazah" class="form-control">
                                </div>
                            </div>
                            <div class="form-group d-flex">
                                <label for="" class="col-4">Transkrip</label>
                                <div class="col-8">
                                    <input type="file" name="file_transkrip" id="file_transkrip"
                                        class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-column gap-2">
                            <div class="form-group">
                                <label for="">Keperluan</label>
                                <input type="text" name="keperluan" id="keperluan" class="form-control"
                                    value="{{ $data->keperluan }}">
                            </div>
                            <div class="form-group">
                                <label for="">NIM</label>
                                <input type="number" id="nmr_unik" name="nmr_unik" value="{{ $user->nmr_unik }}"
                                    class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Program Studi</label>
                                <input type="text" id="nama_prd" name="nama_prd"
                                    value="{{ $prodi->nama_prd }}" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">No Whatsapp</label>
                                <input type="text" id="nowa" name="nowa" value="{{ $user->nowa }}"
                                    class="form-control" readonly>
                            </div>
                            <div class="form-group d-flex">
                                <label for="" class="col-2">Tanggal Lulus</label>
                                <div class="col-10">
                                    <input type="date" name="tgl_lulus" id="tgl_lulus" class="form-control"
                                        value="{{ $data->tgl_lulus }}">
                                </div>
                            </div>
                            <div class="form-group d-flex">
                                <label for="" class="col-2">Pengambilan</label>
                                <div class="col-10">
                                    <select name="ambil" id="ambil" required class="form-select">
                                        <option value="">Select Option</option>
                                        <option value="ditempat" {{ $data->ambil == 'ditempat' ? 'selected' : '' }}>
                                            Diambil di Tempat
                                        </option>
                                        <option value="dikirim" {{ $data->ambil == 'dikirim' ? 'selected' : '' }}>Dikirim
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">Kelurahan</label>
                                <input type="text" name="klh_kirim" id="klh_kirim" class="form-control"
                                    value="{{ $data->klh_kirim }}">
                            </div>
                            <div class="form-group">
                                <label for="">Kota / Kabupaten</label>
                                <input type="text" name="kota_kirim" id="kota_kirim" class="form-control"
                                    value="{{ $data->kota_kirim }}">
                            </div>
                        </div>
                    </div> -->
                    <div class="row py-5">
                        <div class="col d-flex justify-content-center align-items-center">
                            <button type="submit" class="btn btn-success">Perbarui</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var jenisLegalisir = document.getElementById('jenis_lgl');
            var ijazahGroup = document.getElementById('file_ijazah_group');
            var transkripGroup = document.getElementById('file_transkrip_group');

            function toggleVisibility() {
                var value = jenisLegalisir.value;
                if (value === 'ijazah') {
                    ijazahGroup.style.visibility = 'visible';
                    transkripGroup.style.visibility = 'hidden';
                } else if (value === 'transkrip') {
                    ijazahGroup.style.visibility = 'hidden';
                    transkripGroup.style.visibility = 'visible';
                } else if (value === 'ijazah_transkrip') {
                    ijazahGroup.style.visibility = 'visible';
                    transkripGroup.style.visibility = 'visible';
                } else {
                    ijazahGroup.style.visibility = 'hidden';
                    transkripGroup.style.visibility = 'hidden';
                }
            }

            jenisLegalisir.addEventListener('change', toggleVisibility);

            toggleVisibility();
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ambilSelect = document.getElementById('ambil');
            var alamatGroup = document.getElementById('alamat_group');
            var kecamatanGroup = document.getElementById('kecamatan_group');
            var kodeposGroup = document.getElementById('kodepos_group');
            var kelurahanGroup = document.getElementById('kelurahan_group');
            var kotaGroup = document.getElementById('kota_group');

            function toggleVisibility() {
                var value = ambilSelect.value;
                if (value === 'dikirim') {
                    alamatGroup.style.visibility = 'visible';
                    kecamatanGroup.style.visibility = 'visible';
                    kodeposGroup.style.visibility = 'visible';
                    kelurahanGroup.style.visibility = 'visible';
                    kotaGroup.style.visibility = 'visible';
                } else {
                    alamatGroup.style.visibility = 'hidden';
                    kecamatanGroup.style.visibility = 'hidden';
                    kodeposGroup.style.visibility = 'hidden';
                    kelurahanGroup.style.visibility = 'hidden';
                    kotaGroup.style.visibility = 'hidden';
                }
            }

            ambilSelect.addEventListener('change', toggleVisibility);

            toggleVisibility();
        });
    </script>

    <script>
        const kodeposInput = document.getElementById('kdps_kirim');

        kodeposInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');

            if (this.value.length > 5) {
                this.value = this.value.slice(0, 5);
            }
        });
    </script>
@endsection

@section('script')
    @include('user.form-script')
@endsection

