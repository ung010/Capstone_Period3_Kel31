@extends('supervisor_sd.layout')

@section('content')
    <div class="container-fluid p-5">
        <div class="card d-inline-block intersection-card">
            <div class="card-body d-flex gap-2 align-items-center">
                <img src="{{ asset('asset/icons/big mail.png') }}" alt="big mail" class="heading-image">
                <p class="heading-card">SURAT PERMOHONAN PENGEMBALIAN BIAYA PENDIDIKAN</p>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body my-3 px-5">
                <div class="d-flex justify-content-center align-items-center align-content-center">
                    <p class="heading-card">CEK DATA - Supervisor</p>
                </div>
                <div class="d-flex flex-column">
                    <p>Nama: {{ $srt_pmhn_kmbali_biaya->nama_mhw }}</p>
                    <p>NIM: {{ $srt_pmhn_kmbali_biaya->nim_nip }}</p>
                    <p>Tempat Tanggal Lahir: {{ $srt_pmhn_kmbali_biaya->ttl }}</p>
                    <p>Program Studi: {{ $srt_pmhn_kmbali_biaya->nama_prd }}</p>
                    <p>Alamat Asal: {{ $srt_pmhn_kmbali_biaya->almt_asl }}</p>
                    <p>No Whatsapp: {{ $srt_pmhn_kmbali_biaya->nowa }}</p>
                    <p>Kartu Tanda Mahasiswa</p>
                    <img src="{{ $srt_pmhn_kmbali_biaya->foto ? asset('storage/foto/mahasiswa/' . $srt_pmhn_kmbali_biaya->foto) : asset('asset/Rectangle 8.png') }}"
                        alt="ktm" class="w-50 mb-2">
                    <p>SKL <a
                            href="{{ url('storage/pdf/srt_pmhn_kmbali_biaya/bukti_files/' . $srt_pmhn_kmbali_biaya->skl) }}"
                            target="_blank"><img src="{{ asset('asset/icons/file.png') }}" alt="file"
                                style="height: 30px"></a></p>
                    <p>Bukti Bayar <a
                            href="{{ url('storage/pdf/srt_pmhn_kmbali_biaya/bukti_files/' . $srt_pmhn_kmbali_biaya->bukti_bayar) }}"
                            target="_blank"><img src="{{ asset('asset/icons/file.png') }}" alt="file"
                                style="height: 30px"></a>
                    </p>
                    <p>Buku Tabungan <a
                            href="{{ url('storage/pdf/srt_pmhn_kmbali_biaya/bukti_files/' . $srt_pmhn_kmbali_biaya->buku_tabung) }}"
                            target="_blank"><img src="{{ asset('asset/icons/file.png') }}" alt="file"
                                style="height: 30px"></a></p>

                </div>
                <br>
                <div class="d-flex justify-content-center align-items-center align-content-center gap-3">
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#tolakModal"
                        type="button">Tolak</button>
                    <form action="{{ route('srt_pmhn_kmbali_biaya.sv_setuju', $srt_pmhn_kmbali_biaya->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-success" type="submit">Setujui</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('srt_pmhn_kmbali_biaya.modal_sv')
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#table').DataTable();
        });
    </script>
@endsection
