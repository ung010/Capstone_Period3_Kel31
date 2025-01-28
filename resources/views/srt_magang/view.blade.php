@extends('user.surat')
@section('content')
<div style="padding: 0 1rem 0 1rem">
    <table style="text-align: justify;">
        <tr>
            <td>
                <p style="font-size: 12px; font-family: 'Times New Roman', Times, serif">Nomor</p>
            </td>
            <td>
                <p style="font-size: 12px; font-family: 'Times New Roman', Times, serif">:</p>
            </td>
            <td>
                <p style="font-size: 12px; font-family: 'Times New Roman', Times, serif">{{ $srt_magang->no_surat }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p style="font-size: 12px; font-family: 'Times New Roman', Times, serif">Lampiran</p>
            </td>
            <td>
                <p style="font-size: 12px; font-family: 'Times New Roman', Times, serif">:</p>
            </td>
            <td>
                <p style="font-size: 12px; font-family: 'Times New Roman', Times, serif"></p>
            </td>
        </tr>
        <tr>
            <td>
                <p style="font-size: 12px; font-family: 'Times New Roman', Times, serif">Hal</p>
            </td>
            <td>
                <p style="font-size: 12px; font-family: 'Times New Roman', Times, serif">:</p>
            </td>
            <td>
                <p style="font-size: 12px; font-family: 'Times New Roman', Times, serif">Ijin Magang</p>
            </td>
        </tr>
    </table>
</div>
<br>
<p style="font-size: 12px; margin-bottom: 1rem; text-align: justify; padding: 0 1rem 0 1rem">
    Yth {{ $srt_magang->jbt_lmbg }}
</p>
<br />
<div style="padding: 0 1rem 0 1rem">
    <p style="font-size: 12px; margin-bottom: 1rem; text-align: justify">
        Dalam rangka mempersiapkan mahasiswa Fakultas Ekonomika dan Bisnis Universitas Diponegoro mengenal praktik bisnis/organisasi dan
        membandingkannya dengan teori yang diperoleh selama kuliah, maka kami
        mendorong mahasiswa untuk melakukan kegiatan magang pada perusahaan atau
        instansi pemerintah.
    </p>
    <p style="font-size: 12px; margin: 0; text-align: justify">
        Sehubungan dengan hal tersebut di atas, kami mohon dapat diijinkan melaksanakan Magang pada perusahaan/instansi
        yang Saudara pimpin, bagi mahasiswa Fakultas Ekonomika dan Bisnis tersebut di bawah ini :
    </p>
    <br />
    <table
        style="
        margin-left: 5%;
        text-align: justify;
        font-size: 12px;
        font-family: 'Times New Roman', Times, serif;
    ">
        <tr>
            <td>Nama</td>
            <td>:</td>
            <td>{{ $srt_magang->nama }}</td>
        </tr>
        <tr>
            <td>NIM</td>
            <td>:</td>
            <td>{{ $srt_magang->nim_nip }}</td>
        </tr>
        <tr>
            <td>Departemen / Program Studi</td>
            <td>:</td>
            <td>{{ $srt_magang->nama_dpt }} / {{ $srt_magang->nama_prd }}</td>
        </tr>
        <tr>
            <td>Alamat Rumah</td>
            <td>:</td>
            <td>{{ $srt_magang->almt_smg }}</td>
        </tr>
        <tr>
            <td>No. Telp. / HP</td>
            <td>:</td>
            <td>{{ $srt_magang->nowa }}</td>
        </tr>
        <tr>
            <td>Alamat Email</td>
            <td>:</td>
            <td>{{ $srt_magang->email }}</td>
        </tr>
    </table>
    <br />
    <p style="font-size: 12px; margin: 0; text-align: justify">
        Demikian permohonan kami dan atas segala bantuan serta kerja sama yang baik kami ucapkan terima kasih.
    </p>
    <br />
    <div style="display: grid; grid-template-columns: 1fr auto; align-items: center; text-align: center;">
        <table
            style="
            text-align: left;
            font-size: 12px;
            font-family: 'Times New Roman', Times, serif;
            width: 100%;
            border-spacing: 0;
            "
        >
            <tr>
                <td style="width: 33%; vertical-align: top; padding-right: 10px;">
                </td>
                <td style="width: 33%; vertical-align: top; text-align: center;">
                </td>
                <td style="width: 33%; vertical-align: top; padding-left: 10px; text-align: left;">
                Semarang, {{ $srt_magang->tanggal_surat }}
                </td>
                <p></p>
            <tr>
                <td style="width: 33%; vertical-align: top; padding-right: 10px;">
                </td>
                <td style="width: 33%; vertical-align: top; text-align: center;">
                </td>
                <td style="width: 33%; vertical-align: top; padding-left: 10px; text-align: left;">
                    an. Dekan,
                </td>
            </tr>
            <tr>
                <td style="width: 33%; vertical-align: top; padding-right: 10px;">
                </td>
                <td style="width: 33%; vertical-align: top; text-align: center;">
                </td>
                <td style="width: 33%; vertical-align: top; padding-left: 10px; text-align: left;">
                    Wakil Dekan Akademik dan Kemahasiswaan,
                </td>
            </tr>
            <tr>
                <td style="width: 33%; vertical-align: top; padding-right: 10px;">
                </td>
                <td style="width: 33%; vertical-align: top; text-align: center;">
                    <img src="{{ public_path($qrCodePath) }}" alt="QR Code" style="max-width: 100px;"/>
                </td>
                <td style="width: 33%; vertical-align: top; padding-left: 10px; text-align: left;">
                    {{-- <img src="{{ public_path('asset/ttd-wd1.png') }}" alt="Tanda Tangan" style="max-width: 150px; margin: 0 auto;"/> --}}
                </td>
            </tr>
            <tr>
                <td style="width: 33%; vertical-align: top; padding-right: 10px;">
                </td>
                <td style="width: 23%; vertical-align: top; text-align: center;">
                </td>
                <td style="width: 43%; vertical-align: top; padding-left: 10px; text-align: left;">
                  Prof. Dr. Harjum Muharam, S.E., M.E.
                </td>
            </tr>
            <tr>
                <td style="width: 33%; vertical-align: top; padding-right: 10px;">
                </td>
                <td style="width: 33%; vertical-align: top; text-align: center;">
                </td>
                <td style="width: 33%; vertical-align: top; padding-left: 10px; text-align: left;">
                  NIP. 197202182000031001
                </td>
            </tr>
        </table>
    </div>
</div>
@endsection
