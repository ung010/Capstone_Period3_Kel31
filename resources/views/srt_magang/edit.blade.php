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
                Surat Izin Magang
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
                <form action="{{ route('srt_magang.update', $data->id) }}" method="POST" class="row px-5">
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
                                <input type="text" id="nama_mhw" name="nama_mhw" value="{{ $user->nama_mhw }}"
                                    class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">NIM</label>
                                <input type="number" id="nim_nip" name="nim_nip" value="{{ $user->nim_nip }}"
                                    class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Departemen</label>
                                <input type="text" id="nama_dpt" name="nama_dpt"
                                    value="{{ $departemen->nama_dpt }}" class="form-control" readonly>
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
                            <div class="form-group">
                                <label for="">Email</label>
                                <input type="text" id="email" name="email" value="{{ $user->email }}"
                                    class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Alamat Di Semarang</label>
                                <input type="text" id="almt_smg" name="almt_smg" id="almt_smg"
                                    class="form-control" value="{{ $data->almt_smg }}">
                            </div>
                            <div class="form-group d-flex">
                                <label for="" class="col-4" style="line-height: 2;">Semester</label>
                                <div class="col-8">
                                    <select name="semester" id="semester" required class="form-select">
                                        @for ($i = 1; $i <= 14; $i++)
                                            <option value="{{ $i }}"
                                                {{ $data->semester == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="form-group d-flex">
                                <label for="" class="col-1" style="line-height: 2;">SKSK</label>
                                <div class="col-5">
                                    <input type="number" name="sksk" id="sksk" required class="form-control"
                                        value="{{ $data->sksk }}" min="1" max="170">
                                </div>
                                <label for="" class="m-0" style="padding: 0 10px; line-height: 2;">IPK</label>
                                <div class="col-5">
                                    <input type="number" name="ipk" id="ipk" required class="form-control"
                                        value="{{ $data->ipk }}" min="0.00" max="4.00" step="0.01">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-column gap-2">
                            <div class="form-group">
                                <label for="">Lembaga Yang Dituju</label>
                                <input type="text" name="nama_lmbg" id="nama_lmbg" required class="form-control"
                                    value="{{ $data->nama_lmbg }}">
                            </div>
                            <div class="form-group">
                                <label for="">Jabatan Pemimpin yang Dituju</label>
                                <input type="text" name="jbt_lmbg" id="jbt_lmbg" required class="form-control"
                                    value="{{ $data->jbt_lmbg }}">
                            </div>
                            <div class="form-group">
                                <label for="">Kota / Kabupaten Lembaga</label>
                                <input type="text" name="kota_lmbg" id="kota_lmbg" required class="form-control"
                                    value="{{ $data->kota_lmbg }}">
                            </div>
                            <div class="form-group">
                                <label for="">Alamat Lembaga</label>
                                <input type="text" name="almt_lmbg" id="almt_lmbg" required class="form-control"
                                    value="{{ $data->almt_lmbg }}">
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
        function validateSKSInput(input) {
            input.value = input.value.slice(0, 3);
        }

        document.getElementById('sksk').addEventListener('input', function() {
            validateSKSInput(this);
        });

        function validateIPKInput(input)

        document.getElementById('ipk').addEventListener('input', function() {
            validateIPKInput(this);
        });
    </script>

@endsection

@section('script')
    @include('user.form-script')
@endsection
