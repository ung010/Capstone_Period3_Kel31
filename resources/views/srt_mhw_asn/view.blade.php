@extends('user.surat')

@section('content')
    <div style="padding: 0 1rem 0 1rem">
        <table
            style="
            text-align: justify;
            font-size: 11px;
            font-family: 'Times New Roman', Times, serif;
          ">
            <tr>
                <td>Lampiran</td>
                <td>:</td>
                <td>
                    Surat Edaran Bersama Menteri Keuangan dan Kepala Badan Kepegawaian
                    Negara SE.1.38/DJA/1.0/7/1980 No.SE/117/1980
                </td>
            </tr>
            <tr>
                <td>Nomor</td>
                <td>:</td>
                <td>19/SE/1980 Tanggal, 7 Juli 1980</td>
            </tr>
        </table>
    </div>
    <br />
    <table style="width: 100%; text-align: center">
        <tr>
            <td>
                <strong
                    style="
                font-size: 12px;
                font-family: 'Times New Roman', Times, serif;
                text-align: justify;
              "><u>SURAT
                        PERNYATAAN MASIH SEKOLAH / KULIAH</u></strong>
            </td>
        </tr>
        <tr>
            <td>
                <p style="font-size: 11px; margin: 0">
                    Nomor: {{ $srt_mhw_asn->no_surat }}
                </p>
            </td>
        </tr>
    </table>
    <br />
    <div style="padding: 0 1rem 0 1rem">
        <p style="font-size: 12px; margin: 0; text-align: justify">
            Dengan ini Pimpinan Fakultas Ekonomika dan Bisnis Universitas
            Diponegoro Semarang :
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
                <td>Mia Prameswari, S.E., M.Si.</td>
            </tr>
            <tr>
                <td>NIP</td>
                <td>:</td>
                <td>197901142006042001</td>
            </tr>
            <tr>
                <td>Pangkat/Golongan</td>
                <td>:</td>
                <td>Pembina / IV.a</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>Manajer / Kepala Bagian Tata Usaha</td>
            </tr>
            <tr>
                <td>Instansi/Perusahaan</td>
                <td>:</td>
                <td>
                    Fakultas Ekonomika dan Bisnis Universitas Diponegoro Semarang
                </td>
            </tr>
        </table>
        <br />
        <p style="font-size: 12px; margin: 0; text-align: justify">
            Dengan ini Pimpinan Fakultas Ekonomika dan Bisnis Universitas
            Diponegoro Semarang :
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
                <td>{{ $srt_mhw_asn->nama }}</td>
            </tr>
            <tr>
                <td>NIM</td>
                <td>:</td>
                <td>{{ $srt_mhw_asn->nim_nip }}</td>
            </tr>
            <tr>
                <td>Departemen/Semester</td>
                <td>:</td>
                <td>{{ $srt_mhw_asn->nama_prd }} / {{ $srt_mhw_asn->semester }}</td>
            </tr>
            <tr>
                <td>Pada Tahun Ajaran</td>
                <td>:</td>
                <td>{{ $srt_mhw_asn->thn_awl }} / {{ $srt_mhw_asn->thn_akh }}</td>
            </tr>
            <tr>
                <td>Nama Orang Tua / Wali</td>
                <td>:</td>
                <td>{{ $srt_mhw_asn->nama_ortu }}</td>
            </tr>
            <tr>
                <td>NIP / NRP</td>
                <td>:</td>
                <td>{{ $srt_mhw_asn->nip_ortu }}</td>
            </tr>
            <tr>
                <td>Instansi / Perusahaan</td>
                <td>:</td>
                <td>{{ $srt_mhw_asn->ins_ortu }}</td>
            </tr>
        </table>
        <br />
        <p
            style="
            text-align: justify;
            font-size: 12px;
            font-family: 'Times New Roman', Times, serif;
          ">
            Demikian surat pernyataan ini kami buat dengan sesungguhnya, dan
            apabila dikemudian hari surat pernyataan ini tidak benar yang
            mengakibatkan kerugian Negara Republik Indonesia, maka kami bersedia
            menanggung sesuai dengan ketentuan yang berlaku.
        </p>
        <br />
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
                Semarang, {{ $srt_mhw_asn->tanggal_surat }}
            </td>
            <p></p>
            </tr>
            <tr>
            <td style="width: 33%; vertical-align: top; padding-right: 10px;">
            </td>
            <td style="width: 33%; vertical-align: top; text-align: center;">
            </td>
            <td style="width: 33%; vertical-align: top; padding-left: 10px; text-align: left;">
                Dekan,
            </td>
            </tr>
            <tr>
            <td style="width: 33%; vertical-align: top; padding-right: 10px;">
            </td>
            <td style="width: 33%; vertical-align: top; text-align: center;">
            </td>
            <td style="width: 33%; vertical-align: top; padding-left: 10px; text-align: left;">
                Ub. Kepala Bagian/Manajer Bagian Tata Usaha
            </td>
            </tr>
            <tr>
            <td style="width: 33%; vertical-align: top; padding-right: 10px;">
            </td>
            <td style="width: 33%; vertical-align: top; text-align: center;">
                <img src="{{ public_path($qrCodePath) }}" alt="QR Code" style="max-width: 100px;"/>
            </td>
            <td style="width: 33%; vertical-align: middle; padding-left: 10px; text-align: left;">
                <img src="{{ public_path('asset/ttd-manajer.png') }}" alt="Tanda Tangan" style="max-width: 200px; margin: 0 auto;"/>
            </td>
            </tr>
            <tr>
            <td style="width: 33%; vertical-align: top; padding-right: 10px;">
            </td>
            <td style="width: 33%; vertical-align: top; text-align: center;">
            </td>
            <td style="width: 33%; vertical-align: top; padding-left: 10px; text-align: left;">
                Mia Prameswari, S.E., M.Si.
            </td>
            </tr>
            <tr>
            <td style="width: 33%; vertical-align: top; padding-right: 10px;">
            </td>
            <td style="width: 33%; vertical-align: top; text-align: center;">
            </td>
            <td style="width: 33%; vertical-align: top; padding-left: 10px; text-align: left;">
                NIP. 197901142006042001
            </td>
            </tr>
        </table>
    </div>
@endsection
