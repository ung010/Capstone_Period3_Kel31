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
        <button class="btn btn-primary" onclick="addData()">Buat Surat</button>

        <div class="container-fluid">
            <table class="table table-responsive" id="asn">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Mahasiswa</th>
                        <th>Semester Saat Ini-TA</th>
                        <th>Nama Orang Tua</th>
                        <th>NIP / Pensiun</th>
                        <th>Instansi Orang Tua / Pangkat</th>
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
                            <td>{{ $item->nama_mhw }}</td>
                            <td>
                                Smt {{ $item->semester }} -
                                {{ $item->thn_awl }}/{{ $item->thn_akh }}
                            </td>
                            <td>{{ $item->nama_ortu }}</td>
                            <td>{{ $item->nip_ortu }}</td>
                            <td>{{ $item->ins_ortu }}</td>
                            @include('user.lacak_manajer')
                            <td>
                                @if ($item->role_surat == 'mahasiswa')
                                    <button class="btn btn-success btn-sm" disabled>Berhasil</button>
                                @elseif ($item->role_surat == 'tolak')
                                    <a href="{{ route('srt_mhw_asn.edit', ['id' => Hashids::encode($item->id)]) }}"
                                        class="btn btn-danger btn-sm">Ditolak</a>
                                @else
                                    <button class="btn btn-primary btn-sm" disabled>Menunggu</button>
                                @endif
                            </td>
                            <td>
                                @if ($item->role_surat == 'mahasiswa')
                                    <a href='{{ url('/srt_mhw_asn/download/' . $item->id) }}'
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
                <form action="{{ route('srt_mhw_asn.store') }}" method="POST" class="row px-5">
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
                                <input type="text" id="nim_nip" name="nim_nip" value="{{ $user->nim_nip }}"
                                    class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Program Studi</label>
                                <input type="text" name="nama_prd" id="nama_prd" value="{{ $prodi->nama_prd }}"
                                    class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">No Whatsapp</label>
                                <input type="text" id="nowa" name="nowa" value="{{ $user->nowa }}"
                                    class="form-control" readonly>
                            </div>
                            <div class="form-group d-flex">
                                <label for="" class="col-4">Tahun Ajaran</label>
                                <div class="col-8 d-flex">
                                    <input type="number" name="thn_awl" id="thn_awl" class="form-control" min="2000" max="2100" maxlength="4" required>
                                    <p class="m-0" style="padding: 0 10px; line-height: 2;">/</p>
                                    <input type="number" name="thn_akh" id="thn_akh" class="form-control" min="2000" max="2100" maxlength="4" required>
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
                                <label for="">Nama Orang Tua</label>
                                <input type="text" name="nama_ortu" id="nama_ortu" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">NIP</label>
                                <input type="text" name="nip_ortu" id="nip_ortu" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Instansi Orang Tua Bekerja</label>
                                <input type="text" name="ins_ortu" id="ins_ortu" class="form-control">
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
