@extends('supervisor_akd.layout')

@section('content')
    <div class="container-fluid p-5">
        <div class="card d-inline-block intersection-card">
            <div class="card-body d-flex gap-2 align-items-center">
                <img src="{{ asset('asset/icons/big mail.png') }}" alt="big mail" class="heading-image">
                <p class="heading-card">SURAT IZIN MAGANG</p>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body my-3 px-5">
                <div class="d-flex justify-content-center align-items-center align-content-center">
                    <p class="heading-card">CEK DATA</p>
                </div>
                <div class="d-flex flex-column">
                    <p>Nama: {{ $srt_magang->nama }}</p>
                    <p>NIM: {{ $srt_magang->nim_nip }}</p>
                    <p>Departemen: {{ $srt_magang->nama_dpt }}</p>
                    <p>Program Studi: {{ $srt_magang->nama_prd }}</p>
                    <p>Alamat di Semarang: {{ $srt_magang->almt_smg }}</p>
                    <p>IPK: {{ $srt_magang->ipk }}</p>
                    <p>SKSK: {{ $srt_magang->sksk }}</p>
                    <p>No Whatsapp: {{ $srt_magang->nowa }}</p>
                    <p>Email: {{ $srt_magang->email }}</p>
                    <p>Lembaga yang Dituju: {{ $srt_magang->nama_lmbg }}</p>
                    <p>Jabatan Pimpinan yang Dituju: {{ $srt_magang->jbt_lmbg }}</p>
                    <p>Kota / Kabupaten Lembaga: {{ $srt_magang->kota_lmbg }}</p>
                    <p>Alamat Lembaga: {{ $srt_magang->almt_lmbg }}</p>
                    <p>Kartu Tanda Mahasiswa</p>
                    <img src="{{ $srt_magang->foto ? asset('storage/foto/mahasiswa/' . $srt_magang->foto) : asset('asset/Rectangle 8.png') }}"
                        alt="ktm" class="w-50">
                </div>
                <br>
                <div class="d-flex justify-content-center align-items-center align-content-center gap-3">
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#tolakModal">Tolak</button>
                    <form action="{{ route('srt_magang.setuju_sv', $srt_magang->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-success" type="submit">Setujui</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('srt_magang.modal_sv')
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#table').DataTable();
        });
    </script>
@endsection
