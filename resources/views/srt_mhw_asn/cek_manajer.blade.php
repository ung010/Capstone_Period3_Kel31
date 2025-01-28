@extends('manajer.layout')

@section('content')
    <div class="container-fluid p-5">
        <div class="card d-inline-block intersection-card">
            <div class="card-body d-flex gap-2 align-items-center">
                <img src="{{ asset('asset/icons/big mail.png') }}" alt="big mail" class="heading-image">
                <p class="heading-card">SURAT KETERANGAN MASIH KULIAH (BAGI ASN)</p>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body my-3 px-5">
                <div class="d-flex justify-content-center align-items-center align-content-center">
                    <p class="heading-card">CEK DATA</p>
                </div>
                <div class="d-flex flex-column">
                    <p>Nama: {{ $srt_mhw_asn->nama }}</p>
                    <p>NIM: {{ $srt_mhw_asn->nim_nip }}</p>
                    <p>Departemen: {{ $srt_mhw_asn->nama_dpt }}</p>
                    <p>Program Studi: {{ $srt_mhw_asn->nama_prd }}</p>
                    <p>Tahun Ajaran: {{ $srt_mhw_asn->thn_awl }} / {{ $srt_mhw_asn->thn_akh }}</p>
                    <p>Alamat di Semarang: {{ $srt_mhw_asn->almt_asl }}</p>
                    <p>No Whatsapp: {{ $srt_mhw_asn->nowa }}</p>
                    <p>Email: {{ $srt_mhw_asn->email }}</p>
                    <p>Nama Orangtua / Wali: {{ $srt_mhw_asn->nama_ortu }}</p>
                    <p>NIP / NRP: {{ $srt_mhw_asn->nip_ortu }}</p>
                    <p>Instansi / Perusahaan: {{ $srt_mhw_asn->ins_ortu }}</p>
                    <p>Kartu Tanda Mahasiswa</p>
                    <img src="{{ $srt_mhw_asn->foto ? asset('storage/foto/mahasiswa/' . $srt_mhw_asn->foto) : asset('asset/Rectangle 8.png') }}"
                        alt="ktm" class="w-50">
                </div>
                <br>
                <div class="d-flex justify-content-center align-items-center align-content-center gap-3">
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#tolakModal" type="button">Tolak</button>
                    <form action="{{ route('srt_mhw_asn.setuju_manajer', $srt_mhw_asn->id) }}"
                        method="POST">
                        @csrf
                        <button class="btn btn-success" type="submit">Setujui</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('srt_mhw_asn.modal_manajer')
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#table').DataTable();
        });
    </script>
@endsection
