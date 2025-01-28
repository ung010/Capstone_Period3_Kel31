@extends('manajer.layout')

@section('content')
    <div class="container-fluid p-5">
        <div class="card d-inline-block intersection-card">
            <div class="card-body d-flex gap-2 align-items-center">
                <img src="{{ asset('asset/icons/big mail.png') }}" alt="big mail" class="heading-image">
                <p class="heading-card">SURAT IZIN PENELITIAN</p>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body my-3 px-5">
                <div class="d-flex justify-content-center align-items-center align-content-center">
                    <p class="heading-card">CEK DATA</p>
                </div>
                <div class="d-flex flex-column">
                    <p>Nama: {{ $srt_izin_plt->nama }}</p>
                    <p>NIM: {{ $srt_izin_plt->nim_nip }}</p>
                    <p>Departemen: {{ $srt_izin_plt->nama_dpt }}</p>
                    <p>Program Studi: {{ $srt_izin_plt->nama_prd }}</p>
                    <p>Alamat di Semarang: {{ $srt_izin_plt->almt_asl }}</p>
                    <p>No Whatsapp: {{ $srt_izin_plt->nowa }}</p>
                    <p>Email: {{ $srt_izin_plt->email }}</p>
                    <p>Lembaga yang Dituju: {{ $srt_izin_plt->nama_lmbg }}</p>
                    <p>Jabatan Pimpinan yang Dituju: {{ $srt_izin_plt->jbt_lmbg }}</p>
                    <p>Kota / Kabupaten Lembaga: {{ $srt_izin_plt->kota_lmbg }}</p>
                    <p>Alamat Lembaga: {{ $srt_izin_plt->almt_lmbg }}</p>
                    <p>Lampiran: {{ $srt_izin_plt->lampiran }}</p>
                    <p>Kartu Tanda Mahasiswa</p>
                    <img src="{{ $srt_izin_plt->foto ? asset('storage/foto/mahasiswa/' . $srt_izin_plt->foto) : asset('asset/Rectangle 8.png') }}"
                        alt="ktm" class="w-50">
                </div>
                <br>
                <div class="d-flex justify-content-center align-items-center align-content-center gap-3">
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#tolakModal" type="button">Tolak</button>
                    <form action="{{ route('srt_izin_plt.setuju_manajer', $srt_izin_plt->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-success" type="submit">Setujui</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('srt_izin_plt.modal_manajer')
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#table').DataTable();
        });
    </script>
@endsection
