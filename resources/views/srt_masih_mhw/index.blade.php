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
                Surat Keterangan Masih Mahasiswa
            </h2>
        </div>
        <button class="btn btn-primary" onclick="addData()">Buat Surat</button>

        <div class="container-fluid">
            <table class="table table-responsive" id="asn">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama / NIM</th>
                        <th>Alamat</th>
                        <th>Semester Saat Ini-TA</th>
                        <th>Tempat, Tanggal Lahir</th>
                        <th>Alasan Surat</th>
                        <th>Lacak</th>
                        <th>Status</th>
                        <th>Unduh</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach ($data as $item)
                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $item->nama_mhw }} / {{ $item->nim_nip }}</td>
                            <td>{{ $item->almt_smg }}</td>
                            <td>
                                {{ $item->semester }}, {{ $item->thn_awl }}/{{ $item->thn_akh }}
                            </td>
                            <td>{{ $item->ttl }}</td>
                            <td>{{ $item->tujuan_buat_srt }}</td>
                            @if ($item->tujuan_akhir == 'manajer' )
                                @include('user.lacak_manajer')
                            @else
                                @include('user.lacak')
                            @endif
                            <td>
                                @if ($item->role_surat == 'mahasiswa')
                                    <button class="btn btn-success btn-sm" disabled>Berhasil</button>
                                @elseif ($item->role_surat == 'tolak')
                                    <a href="{{ route('srt_masih_mhw.edit', ['id' => Hashids::encode($item->id)]) }}"
                                        class="btn btn-danger btn-sm">Ditolak</a>
                                @else
                                    <button class="btn btn-primary btn-sm" disabled>Menunggu</button>
                                @endif
                            </td>
                            <td>
                                @if ($item->role_surat == 'mahasiswa')
                                    @if ($item->tujuan_akhir == 'manajer')
                                        <a href="{{ url('/srt_masih_mhw/manajer/download/' . $item->id) }}"
                                            class="btn btn-primary btn-sm">Unduh</a>
                                    @elseif ($item->tujuan_akhir == 'wd')
                                        <a href="{{ route('srt_masih_mhw.download_wd', ['id' => $item->id]) }}"
                                            class="btn btn-primary btn-sm">Unduh</a>
                                    @else
                                        <button class="btn btn-secondary btn-sm" disabled>Unduh</button>
                                    @endif
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
                <form action="{{ route('srt_masih_mhw.store') }}" method="POST" class="row px-5">
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
                                <input type="text" id="nama_prd" name="nama_prd" value="{{ $prodi->nama_prd }}"
                                    class="form-control" readonly>
                            </div>
                            <div class="form-group d-flex">
                                <label for="" class="col-4">Tahun Ajaran</label>
                                <div class="col-8 d-flex">
                                    <input type="number" name="thn_awl" id="thn_awl" class="form-control" min="2000" maxlength="4" required>
                                    <p class="m-0" style="padding: 0 10px; line-height: 2;">/</p>
                                    <input type="number" name="thn_akh" id="thn_akh" class="form-control" min="2000" maxlength="4" required>
                                </div>
                            </div>
                            <div class="form-group d-flex">
                                <label for="" class="col-4">Semester</label>
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
                    <div class="col-6">
                        <div class="d-flex flex-column gap-2">
                            <div class="form-group">
                                <label for="">Alamat</label>
                                <input type="text" id="almt_smg" name="almt_smg" id="almt_smg"
                                    class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Tempat, Tanggal Lahir</label>
                                <input type="text" id="kota_tanggal_lahir" name="kota_tanggal_lahir"
                                    value="{{ $kota_tanggal_lahir }}" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Tujuan Pembuatan Surat</label>
                                <input type="text" name="tujuan_buat_srt" id="tujuan_buat_srt" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">No Whatsapp</label>
                                <input type="text" id="nowa" name="nowa" value="{{ $user->nowa }}"
                                    class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="" class="mb-2">Tanda Tangan (*Pilih Salah Satu)</label>
                                <div class="d-flex flex-column gap-1">
                                    <div class="d-flex gap-2">
                                        <input type="radio" name="tujuan_akhir" value="wd" id="manajer">
                                        <p>Wakil Dekan Akademik</p>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <input type="radio" name="tujuan_akhir" value="manajer" id="wd">
                                        <p>Manajer</p>
                                    </div>
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
