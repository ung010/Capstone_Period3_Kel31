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
                Surat Keterangan Masih Kuliah (Bagi ASN)
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

    <div class="d-flex flex-column justify-content-center align-items-center gap-3" style="margin-left: 5%; margin-right: 5%;">
        <div class="card w-100" id="card-tambah">
            <div class="card-body d-flex flex-column gap-3">
                <div class="d-flex justify-content-center align-items-center">
                    <h3>Perbaikan Data</h3>
                </div>
                <form action="{{ route('srt_mhw_asn.update', $data->id) }}" method="POST" class="row px-5">
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
                    <div class="col-6">
                        <div class="d-flex flex-column gap-2">
                            <div class="form-group">
                                <label for="">Nama Mahasiswa</label>
                                <input type="text" id="nama_mhw" name="nama_mhw" value="{{ $user->nama }}"
                                    class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">NIM</label>
                                <input type="text" id="nim_mhw" name="nim_mhw" value="{{ $user->nim_nip }}"
                                    class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Program Studi</label>
                                <input type="text" name="jenjang_prodi" id="jenjang_prodi" value="{{ $prodi->nama_prd }}"
                                    class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">No Whatsapp</label>
                                <input type="text" id="nowa_mhw" name="nowa_mhw" value="{{ $user->nowa }}"
                                    class="form-control" readonly>
                            </div>
                            <div class="form-group d-flex">
                                <label for="" class="col-4">Tahun Ajaran</label>
                                <div class="col-8 d-flex">
                                    <input type="number" name="thn_awl" id="thn_awl" value="{{ $data->thn_awl }}"
                                        class="form-control" min="2000" max="2100" maxlength="4" required>
                                    <p class="m-0" style="padding: 0 10px; line-height: 2;">/</p>
                                    <input type="number" name="thn_akh" id="thn_akh" value="{{ $data->thn_akh }}"
                                        class="form-control" min="2000" max="2100" maxlength="4" required>
                                </div>
                            </div>
                            <div class="form-group d-flex">
                                <label for="" class="col-4">Semester</label>
                                <div class="col-8">
                                    <select name="semester" id="semester" required class="form-select">
                                        @for ($i = 1; $i <= 14; $i++)
                                            <option value="{{ $i }}"
                                                {{ $data->semester == $i ? 'selected' : '' }}>
                                                {{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-column gap-2">
                            <div class="form-group">
                                <label for="">Nama Orang Tua</label>
                                <input type="text" name="nama_ortu" id="nama_ortu" class="form-control"
                                    value="{{ $data->nama_ortu }}">
                            </div>
                            <div class="form-group">
                                <label for="">NIP</label>
                                <input type="text" name="nip_ortu" id="nip_ortu" class="form-control"
                                    value="{{ $data->nip_ortu }}">
                            </div>
                            <div class="form-group">
                                <label for="">Instansi Orang Tua Bekerja</label>
                                <input type="text" name="ins_ortu" id="ins_ortu" class="form-control"
                                    value="{{ $data->ins_ortu }}">
                            </div>
                        </div>
                    </div>
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
        function validateYearInput(input) {
            input.value = input.value.slice(0, 4);
        }

        document.getElementById('thn_awl').addEventListener('input', function() {
            validateYearInput(this);
        });

        document.getElementById('thn_akh').addEventListener('input', function() {
            validateYearInput(this);
        });
    </script>
@endsection

@section('script')
    @include('user.form-script')
@endsection
