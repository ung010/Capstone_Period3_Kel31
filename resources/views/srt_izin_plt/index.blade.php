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
        <button class="btn btn-primary" onclick="addData()">Buat Surat</button>

        <div class="container-fluid">
            <table class="table table-responsive" id="asn">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jenis Surat</th>
                        <th>Lembaga Tujuan</th>
                        <th>Nama / NIM</th>
                        <th>Semester</th>
                        <th>Alamat</th>
                        <th class="text-center">Lacak</th>
                        <th>Status</th>
                        <th>Unduh</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach ($data as $item)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $item->jenis_surat }}</td>
                            <td>{{ $item->nama_lmbg }}</td>
                            <td>
                                {{ $item->nama_mhw }}/{{ $item->nim_nip }}
                            </td>
                            <td>{{ $item->semester }}</td>
                            <td>{{ $item->almt_lmbg }}</td>
                            @include('user.lacak')
                            <td>
                                @if ($item->role_surat == 'mahasiswa')
                                    <button class="btn btn-success btn-sm" disabled>Berhasil</button>
                                @elseif ($item->role_surat == 'tolak')
                                    <a href="{{ route('srt_izin_plt.edit', ['id' => Hashids::encode($item->id)]) }}"
                                        class="btn btn-danger btn-sm">Ditolak</a>
                                @else
                                    <button class="btn btn-primary btn-sm" disabled>Menunggu</button>
                                @endif
                            </td>
                            <td>
                                @if ($item->role_surat == 'mahasiswa')
                                    <a href="{{ url('/srt_izin_plt/download/' . $item->id) }}"
                                        class="btn btn-primary btn-sm">Unduh</a>
                                @else
                                    <button class="btn btn-secondary btn-sm" disabled>Unduh</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card w-100 d-none" id="card-tambah">
            <div class="card-body d-flex flex-column gap-3">
                <div class="d-flex justify-content-center align-items-center">
                    <h3>ISI DATA</h3>
                </div>
                <form action="{{ route('srt_izin_plt.store') }}" method="POST" class="row px-5">
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
                                    <label for="">Permohonan Data Untuk</label>
                                    <select name="jenis_surat" id="jenis_surat" required class="form-select">
                                        <option value="Kerja Praktek">Kerja Praktek</option>
                                        <option value="Tugas Akhir Penelitian Mahasiswa">
                                            Tugas Akhir Penelitian Mahasiswa</option>
                                        <option value="Ijin Penelitian">Ijin Penelitian</option>
                                        <option value="Survey">Survey</option>
                                        <option value="Thesis">Thesis</option>
                                        <option value="Disertasi">Disertasi</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label for="">Lampiran</label>
                                    <select name="lampiran" id="lampiran" required class="form-select">
                                        <option value="1 Eksemplar">1 Eksemplar</option>
                                        <option value="2 Eksemplar">2 Eksemplar</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">Lembaga yang Dituju</label>
                                <input type="text" name="nama_lmbg" id="nama_lmbg" required class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Jabatan Pimpinan yang Dituju</label>
                                <input type="text" name="jbt_lmbg" id="jbt_lmbg" required class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Kota / Kabupaten Lembaga</label>
                                <input type="text" name="kota_lmbg" id="kota_lmbg" required class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Alamat Lembaga</label>
                                <input type="text" name="almt_lmbg" id="almt_lmbg" required class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Judul / Tema Pengambilan Data</label>
                                <input type="text" name="judul_data" id="judul_data" required class="form-control">
                            </div>
                            <div class="form-group d-flex">
                                <label for="" class="col-4" style="line-height: 2;">Semester</label>
                                <div class="col-8">
                                    <select name="semester" id="semester" required class="form-select">
                                        @for ($i = 1; $i <= 14; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row py-3">
                        <div class="col-6">
                            <a href="{{ route('mahasiswa.index') }}" class="btn btn-danger">Kembali</a>
                        </div>
                        <div class="col-6">
                            <button type="submit" class="btn btn-success">Simpan</button>
                            <button class="btn btn-secondary" onclick="resetData()" type="button">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('user.form-script')
@endsection
