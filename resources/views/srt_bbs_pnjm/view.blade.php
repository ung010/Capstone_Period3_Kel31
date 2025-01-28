@extends('user.surat') @section('content')
    <table style="width: 100%; text-align: center">
        <tr>
            <td>
                <strong
                    style="
          font-size: 16px;
          font-family: 'Times New Roman', Times, serif;
          text-align: justify;
        "><u>KETERANGAN
                        BEBAS PINJAM</u></strong>
            </td>
        </tr>
        <tr>
            <td>
                <p style="font-size: 12px; margin: 0">
                    Nomor: {{ $srt_bbs_pnjm->no_surat }}
                </p>
            </td>
        </tr>
    </table>
    <br />
    <div style="padding: 0 1rem 0 1rem">
        <p style="font-size: 12px; margin: 0; text-align: justify">
            Yang bertanda tangan di bawah ini, Supervisor Sumberdaya Fakultas Ekonomika dan Bisnis Universitas Diponegoro,
            menerangkan bahwa mahasiswa :
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
                <td>{{ $srt_bbs_pnjm->nama_mhw }}</td>
            </tr>
            <tr>
                <td>NIM</td>
                <td>:</td>
                <td>{{ $srt_bbs_pnjm->nim_nip }}</td>
            </tr>
            <tr>
                <td>Program Studi</td>
                <td>:</td>
                <td>{{ $srt_bbs_pnjm->nama_prd }}</td>
            </tr>
            <tr>
                <td>Dosen Wali</td>
                <td>:</td>
                <td>{{ $srt_bbs_pnjm->dosen_wali }}</td>
            </tr>
            <tr>
                <td>Alamat Semarang</td>
                <td>:</td>
                <td>{{ $srt_bbs_pnjm->almt_smg }}</td>
            </tr>
            <tr>
                <td>No Whatsapp</td>
                <td>:</td>
                <td>{{ $srt_bbs_pnjm->nowa }}</td>
            </tr>
        </table>
        <br />
        <p style="font-size: 12px; margin: 0; text-align: justify">
            Dinyatakan telah "Bebas Pinjam" (tidak meminjam asset yang dimiliki oleh Fakultas Ekonomika dan Bisnis
            Universitas Diponegoro)
        </p>
        <br />
        <table
            style="
            text-align: left;
            font-size: 12px;
            font-family: 'Times New Roman', Times, serif;
            width: 100%;
            border-spacing: 0;
        ">
            <tr>
                <td style="width: 33%; vertical-align: top; padding-right: 10px;">
                </td>
                <td style="width: 33%; vertical-align: top; text-align: center;">
                </td>
                <td style="width: 33%; vertical-align: top; padding-left: 10px; text-align: left;">
                    Semarang, {{ $srt_bbs_pnjm->tanggal_surat }}
                </td>
            </tr>
            <tr>
                <td style="width: 33%; vertical-align: top; padding-right: 10px;">
                </td>
                <td style="width: 33%; vertical-align: top; text-align: center;">
                </td>
                <td style="width: 33%; vertical-align: top; padding-left: 10px; text-align: left;">
                    Supervisor Sumberdaya
                </td>
            </tr>
            <tr>
                <td style="width: 33%; vertical-align: top; padding-right: 10px;">
                    <p></p>
                </td>
                <td style="width: 33%; vertical-align: top; text-align: center;">
                    <img src="{{ public_path($qrCodePath) }}" alt="QR Code" style="max-width: 100px;"/>
                </td>
                <td style="padding-bottom: 1rem;">
                    <img src="{{ public_path('asset/ttd-spvsd.png') }}" alt="Tanda Tangan" style="max-width: 100px;"/>
                </td>
            </tr>
            <tr>
                <td style="width: 33%; vertical-align: top; padding-right: 10px;">
                </td>
                <td style="width: 33%; vertical-align: top; text-align: center;">
                </td>
                <td style="width: 33%; vertical-align: top; padding-left: 10px; text-align: left;">
                    Suryani, S.E.
                </td>
            </tr>
            <tr>
                <td style="width: 33%; vertical-align: top; padding-right: 10px;">
                </td>
                <td style="width: 33%; vertical-align: top; text-align: center;">
                </td>
                <td style="width: 33%; vertical-align: top; padding-left: 10px; text-align: left;">
                    NPPU. H.7.198601242009082001
                </td>
            </tr>
        </table>
    </div>
@endsection
