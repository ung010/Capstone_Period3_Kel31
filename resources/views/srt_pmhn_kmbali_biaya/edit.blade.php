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
                Surat Permohonan Pengembalian Biaya Pendidikan
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
                <form action="{{ route('srt_pmhn_kmbali_biaya.update', $data->id) }}" method="POST"
                    enctype="multipart/form-data" class="row px-5">
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
                                <label for="">Tempat, Tanggal Lahir</label>
                                <input type="text" id="ttl" name="ttl" value="{{ $kota_tanggal_lahir }}"
                                    class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Alamat Rumah</label>
                                <input type="text" id="nowa" name="nowa" value="{{ $user->almt_asl }}"
                                    class="form-control" readonly>
                            </div>
                            <div class="form-group d-flex">
                                <label for="" class="col-4">SKL</label>
                                <div class="col-8">
                                    <input type="file" name="skl" id="skl" required class="form-control">
                                </div>
                            </div>
                            <div class="form-group d-flex">
                                <label for="" class="col-4">Buti Bayar</label>
                                <div class="col-8">
                                    <input type="file" name="bukti_bayar" id="bukti_bayar" required
                                        class="form-control">
                                </div>
                            </div>
                            <div class="form-group d-flex">
                                <label for="" class="col-4">Buku Tabungan</label>
                                <div class="col-8">
                                    <input type="file" name="buku_tabung" id="buku_tabung" required
                                        class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-column gap-2">
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
                                <label for="">No Whatsapp</label>
                                <input type="text" id="nowa" name="nowa" value="{{ $user->nowa }}"
                                    class="form-control" readonly>
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
