@extends('admin.layout')

@section('content')
    <div class="container-fluid p-5">
        <div class="mx-5">
            <div class="card d-inline-block intersection-card">
                <div class="card-body d-flex gap-2 align-items-center">
                    <img src="{{ asset('asset/icons/big mail.png') }}" alt="big mail" class="heading-image">
                    <p class="heading-card">SURAT KETERANGAN MASIH MAHASISWA</p>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body my-3 px-5">
                <div class="d-flex justify-content-center align-items-center align-content-center">
                    <p class="heading-card">CEK DATA</p>
                </div>
                <div class="d-flex flex-column">
                    <p>Nama: {{ $srt_masih_mhw->nama }}</p>
                    <p>Tempat, Tanggal Lahir: {{ $srt_masih_mhw->ttl }}</p>
                    <p>NIM: {{ $srt_masih_mhw->nim_nip }}</p>
                    <p>Alamat Asal: {{ $srt_masih_mhw->almt_asl }}</p>
                    <p>Departemen: {{ $srt_masih_mhw->nama_dpt }}</p>
                    <p>Program Studi: {{ $srt_masih_mhw->nama_prd }}</p>
                    <p>No Whatsapp: {{ $srt_masih_mhw->nowa }}</p>
                    <p>Alasan Pembuatan Surat: {{ $srt_masih_mhw->tujuan_buat_srt }}</p>
                    <p>Tujuan Tandat Tngan:
                        @if ($srt_masih_mhw->tujuan_akhir == 'manajer')
                            Manajer
                        @else
                            Wakil Dekan
                        @endif
                    </p>
                    <p>Kartu Tanda Mahasiswa</p>
                    <img src="{{ $srt_masih_mhw->foto ? asset('storage/foto/mahasiswa/' . $srt_masih_mhw->foto) : asset('asset/Rectangle 8.png') }}"
                        alt="ktm" class="w-50">
                </div>
                <br>
                <div class="d-flex justify-content-center align-items-center align-content-center gap-3">
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#tolakModal">Tolak</button>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#setujuModal">Setuju</button>
                </div>
            </div>
        </div>
    </div>

    @include('srt_masih_mhw.modal')
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#table').DataTable();
        });
    </script>
@endsection
