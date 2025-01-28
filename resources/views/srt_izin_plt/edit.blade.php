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
                Surat Izin Penelitian
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
                <form action="{{ route('srt_izin_plt.update', $data->id) }}" method="POST" class="row px-5">
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
                                <input type="text" id="jenjang_prodi" name="jenjang_prodi"
                                    value="{{ $prodi->nama_prd }}" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Email</label>
                                <input type="text" id="email" name="email" value="{{ $user->email }}"
                                    class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">No Whatsapp</label>
                                <input type="text" id="nowa" name="nowa" value="{{ $user->nowa }}"
                                    class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Alamat Asal</label>
                                <input type="text" id="nowa" name="nowa" value="{{ $user->almt_asl }}"
                                    class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-column gap-2">
                            <div class="form-group">
                                <div class="col-6">
                                    <label for="">Lampiran</label>
                                    <select name="lampiran" id="lampiran" required class="form-select">
                                        <option value="1 Eksemplar"
                                            {{ $data->lampiran == '1 Eksemplar' ? 'selected' : '' }}>1
                                            Eksemplar</option>
                                        <option value="2 Eksemplar"
                                            {{ $data->lampiran == '2 Eksemplar' ? 'selected' : '' }}>2
                                            Eksemplar</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">Lembaga yang Dituju</label>
                                <input type="text" name="nama_lmbg" id="nama_lmbg" required class="form-control"  value="{{ $data->nama_lmbg }}">
                            </div>
                            <div class="form-group">
                                <label for="">Jabatan Pimpinan yang Dituju</label>
                                <input type="text" name="jbt_lmbg" id="jbt_lmbg" required class="form-control"  value="{{ $data->jbt_lmbg }}">
                            </div>
                            <div class="form-group">
                                <label for="">Kota / Kabupaten Lembaga</label>
                                <input type="text" name="kota_lmbg" id="kota_lmbg" required class="form-control"  value="{{ $data->kota_lmbg }}">
                            </div>
                            <div class="form-group">
                                <label for="">Alamat Lembaga</label>
                                <input type="text" name="almt_lmbg" id="almt_lmbg" required class="form-control"  value="{{ $data->almt_lmbg }}">
                            </div>
                            <div class="form-group">
                                <label for="">Judul / Tema Pengambilan Data</label>
                                <input type="text" name="judul_data" id="judul_data" required class="form-control"  value="{{ $data->judul_data }}">
                            </div>
                            <div class="form-group">
                                <label for="">Semester</label>
                                <div class="col-8">
                                    <select name="semester" id="semester" required class="form-select">
                                        @for ($i = 1; $i <= 14; $i++)
                                            <option value="{{ $i }}"
                                                {{ $data->semester == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
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
@endsection
