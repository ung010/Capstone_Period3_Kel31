@extends('supervisor_akd.layout')

@section('content')
    <div class="container-fluid p-5">
        <div class="card d-inline-block intersection-card">
            <div class="card d-inline-block intersection-card">
                <div class="card-body d-flex gap-2 align-items-center">
                    <img src="{{ asset('asset/icons/big mail.png') }}" alt="big mail" class="heading-image">
                    <p class="heading-card">LEGALISIR DIAMBIL DI TEMPAT</p>
                </div>
            </div>
            <br>
            <div class="card d-inline-block">
                <div class="card-header d-flex align-items-center gap-2">
                    <a class="btn btn-secondary btn-fixed-size" href="/legalisir/sv/ditempat/ijazah">Ijazah</a>
                    <a class="btn btn-secondary btn-fixed-size" href="/legalisir/sv/ditempat/transkrip">Transkrip</a>
                    <a class="btn btn-secondary btn-fixed-size" href="/legalisir/sv/ditempat/ijz_trs">Ijazah dan Transkrip</a>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body my-3 px-5">
                <div class="d-flex justify-content-center align-items-center align-content-center">
                    <p class="heading-card">CEK DATA</p>
                </div>
                <div class="d-flex flex-column">
                    <p>
                        Jenis Legalisir:
                        @if ($legalisir->jenis_lgl == 'ijazah')
                            Ijazah
                        @elseif($legalisir->jenis_lgl == 'transkrip')
                            Transkrip
                        @elseif($legalisir->jenis_lgl == 'ijazah_transkrip')
                            Ijazah dan Transkrip
                        @else
                            Tidak Ada
                        @endif
                    </p>
                    <p>Keperluan: {{ $legalisir->keperluan }}</p>
                    <p>Nama: {{ $legalisir->nama_mhw }}</p>
                    <p>NIM: {{ $legalisir->nim_nip }}</p>
                    <p>Departemen: {{ $legalisir->nama_dpt }}</p>
                    <p>Program Studi: {{ $legalisir->nama_prd }}</p>
                    <p>Alamat Asal: {{ $legalisir->almt_asl }}</p>
                    <p>No Whatsapp: {{ $legalisir->nowa }}</p>
                    <p>
                        Metode Pengambilan:
                        @if ($legalisir->ambil == 'dikirim')
                            Mahasiswa meminta untuk legalisir dikirim
                        @else
                            Mahasiswa akan mengambil ke kantor fakultas
                        @endif
                    </p>
                    @if ($legalisir->ambil == 'dikirim')
                        <p>Alamat Tujuan: {{ $legalisir->almt_kirim ? $legalisir->almt_kirim : '-' }}</p>
                        <p>Kelurahan: {{ $legalisir->klh_kirim ? $legalisir->klh_kirim : '-' }}</p>
                        <p>Kecamatan: {{ $legalisir->kcmt_kirim ? $legalisir->kcmt_kirim : '-' }}</p>
                        <p>Kota / Kabupaten: {{ $legalisir->kota_kirim ? $legalisir->kota_kirim : '-' }}</p>
                        <p>Kode Pos: {{ $legalisir->kdps_kirim ? $legalisir->kdps_kirim : '-' }}</p>
                    @else
                    @endif
                    <div class="d-flex gap-3">
                        @if (Storage::exists('pdf/legalisir/ijazah/' . $legalisir->file_ijazah) || Storage::exists('pdf/legalisir/transkrip/' . $legalisir->file_transkrip))
                            @if (Storage::exists('pdf/legalisir/ijazah/' . $legalisir->file_ijazah))
                                <p>Ijazah <a href="{{ url('storage/pdf/legalisir/ijazah/' . $legalisir->file_ijazah) }}" target="_blank">
                                    <img src="{{ asset('asset/icons/file.png') }}" alt="file" style="height: 30px">
                                </a></p>
                            @endif

                            @if (Storage::exists('pdf/legalisir/transkrip/' . $legalisir->file_transkrip))
                                <p>Transkrip <a href="{{ url('storage/pdf/legalisir/transkrip/' . $legalisir->file_transkrip) }}" target="_blank">
                                    <img src="{{ asset('asset/icons/file.png') }}" alt="file" style="height: 30px">
                                </a></p>
                            @endif
                        @else
                            <div class="alert alert-danger">
                                File ijazah dan transkrip tidak ada
                            </div>
                        @endif
                    </div>
                </div>
                <br>
                <div class="d-flex justify-content-center align-items-center align-content-center gap-3">
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#tolakModal">Tolak</button>
                    <form action="{{ route('legalisir_sv.setuju_sv_ditempat_ijz_trs', $legalisir->id) }}"
                        method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">Setujui</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="tolakModal" tabindex="-1" aria-labelledby="tolakModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('legalisir_sv.tolak_sv_ditempat_ijz_trs', $legalisir->id) }}" method="POST" id="tolakForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="catatan_surat" class="form-label">Alasan Legalisir Ditolak</label>
                            <textarea class="form-control" id="catatan_surat" name="catatan_surat" rows="3" maxlength="250" oninput="updateCharacterCount()"></textarea>
                            <small id="charCount" class="form-text text-muted">0/250 karakter</small>
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

    <script>
        document.getElementById('tolakForm').addEventListener('submit', function(e) {
            let textarea = document.getElementById('catatan_surat');
            let suffix = " - Supervisor Akademik";

            if (!textarea.value.includes(suffix)) {
                textarea.value += suffix;
            }
        });

        function updateCharacterCount() {
            var maxLength = 250;
            var currentLength = document.getElementById('catatan_surat').value.length;
            var charCount = document.getElementById('charCount');

            charCount.textContent = currentLength + '/' + maxLength + ' karakter';
        }
    </script>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#table').DataTable();
        });
    </script>
@endsection
