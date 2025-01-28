@extends('admin.layout')

@section('content')
    <div class="container-fluid p-5">
        <div class="card d-inline-block intersection-card">
            <div class="card-body d-flex gap-2 align-items-center">
                <img src="{{ asset('asset/icons/big mail.png') }}" alt="big mail" class="heading-image">
                <p class="heading-card">SURAT KETERANGAN BEBAS PINJAM</p>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body my-3 px-5">
                <div class="d-flex justify-content-center align-items-center align-content-center">
                    <p class="heading-card">CEK DATA</p>
                </div>
                <div class="d-flex flex-column">
                    <p>Nama: {{ $srt_bbs_pnjm->nama_mhw }}</p>
                    <p>NIM: {{ $srt_bbs_pnjm->nim_nip }}</p>
                    <p>Program Studi: {{ $srt_bbs_pnjm->nama_prd }}</p>
                    <p>Alamat di Semarang: {{ $srt_bbs_pnjm->almt_smg }}</p>
                    <p>No Whatsapp: {{ $srt_bbs_pnjm->nowa }}</p>
                    <p>Dosen Wali: {{ $srt_bbs_pnjm->dosen_wali }}</p>
                    <p>Kartu Tanda Mahasiswa</p>
                    <img src="{{ $srt_bbs_pnjm->foto ? asset('storage/foto/mahasiswa/' . $srt_bbs_pnjm->foto) : asset('asset/Rectangle 8.png') }}"
                        alt="ktm" class="w-50">
                </div>
                <br>
                <div class="d-flex justify-content-center align-items-center align-content-center gap-3">
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#tolakModal" type="button">Tolak</button>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#setujuModal" type="button">Setujui</button>
                </div>
            </div>
        </div>
    </div>

    @include('srt_bbs_pnjm.modal')
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#table').DataTable();
        });
    </script>
@endsection

