<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSS only -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <style>
        /* Style for the submenu */
        .dropdown-submenu {
            position: relative;
        }

        .dropdown-submenu .dropdown-menu {
            top: 0;
            left: 100%;
            margin-top: -1px;
        }
    </style>
    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        @auth
                        <li class="nav-item">
                            <a class="nav-link" href="/admin">{{ auth()->user()->nama }}</a>
                        </li>
                    @endauth
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Manajemen User
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="/admin/user" class="dropdown-item">Manajemen User</a>
                            </li>
                            <li><a href="/admin/verif_user" class="dropdown-item">Verifikasi User</a>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Manajemen Surat
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/srt_mhw_asn/admin">Surat keterangan untuk anak ASN</a>
                            </li>
                            <li><a class="dropdown-item" href="/srt_masih_mhw/admin/manajer">Surat Keterangan Masih
                                    Mahasiswa</a></li>
                            <li><a class="dropdown-item" href="/srt_masih_mhw/admin/wd">Surat Keterangan Masih
                                    Mahasiswa - Dari Manajer untuk WD</a></li>
                            <li><a class="dropdown-item" href="/srt_magang/admin">Surat Magang</a></li>
                            <li><a class="dropdown-item" href="/srt_izin_plt/admin">Surat Izin Penelitian</a></li>
                            <li><a class="dropdown-item" href="/srt_pmhn_kmbali_biaya/admin">Surat Permohonan
                                    Pengembalian Biaya Pendidikan</a></li>
                            <li><a class="dropdown-item" href="/srt_bbs_pnjm/admin">Surat Bebas Pinjam</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdownMenuLink" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Manajemen Legalisir
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <li class="dropdown-submenu">
                                <a class="dropdown-item dropdown-toggle" href="#">Legalisir Diambil Ditempat</a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="/legalisir/admin/ditempat/ijazah">Ijazah</a></li>
                                    <li><a class="dropdown-item" href="/legalisir/admin/ditempat/transkrip">Transkrip</a>
                                    </li>
                                    <li><a class="dropdown-item" href="/legalisir/admin/ditempat/ijz_trs">Ijazah
                                            dan Transkrip</a></li>
                                </ul>
                            </li>
                            <li class="dropdown-submenu">
                                <a class="dropdown-item dropdown-toggle" href="#">Legalisir Dikirim</a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="/legalisir/admin/dikirim/ijazah">Ijazah</a></li>
                                    <li><a class="dropdown-item" href="/legalisir/admin/dikirim/transkrip">Transkrip</a>
                                    </li>
                                    <li><a class="dropdown-item" href="/legalisir/admin/dikirim/ijz_trs">Ijazah
                                            dan Transkrip</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Tracking Surat
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/tracking/manajer">Manajer</a>
                            </li>
                            <li><a class="dropdown-item" href="/tracking/sv_akd">Supervisor Akademik</a></li>
                            <li><a class="dropdown-item" href="/tracking/sv_sd">Supervisor Sumber Daya</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Riwayat Surat
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/riwayat_srt/admin/srt_mhw_asn">Surat keterangan untuk anak ASN</a>
                            </li>
                            <li><a class="dropdown-item" href="/riwayat_srt/admin/srt_masih_mhw">Surat Keterangan Masih
                                    Mahasiswa</a></li>
                            <li><a class="dropdown-item" href="/riwayat_srt/admin/srt_magang">Surat Magang</a></li>
                            <li><a class="dropdown-item" href="/riwayat_srt/admin/srt_izin_plt">Surat Izin Penelitian</a></li>
                            <li><a class="dropdown-item" href="/riwayat_srt/admin/srt_pmhn_kmbali_biaya">Surat Permohonan
                                    Pengembalian Biaya Pendidikan</a></li>
                            <li><a class="dropdown-item" href="/riwayat_srt/admin/srt_bbs_pnjm">Surat Bebas Pinjam</a></li>
                            <li><a class="dropdown-item" href="/riwayat_srt/admin/legalisir">Legalisir</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="/survey/admin">
                            Survey
                        </a>
                    </li>
                </ul>
                <a class="navbar-brand" href="/logout">Logout</a>
            </div>
        </div>
    </nav>
</head>

<body style="background-image: url('https://cdn.crispedge.com/fffe7a.png')">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <div class="container py-6">
        @include('template/pesan')
        @yield('inti_data')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var dropdownSubmenus = document.querySelectorAll('.dropdown-submenu');

                dropdownSubmenus.forEach(function (submenu) {
                    submenu.addEventListener('mouseover', function () {
                        this.querySelector('.dropdown-menu').classList.add('show');
                    });

                    submenu.addEventListener('mouseleave', function () {
                        this.querySelector('.dropdown-menu').classList.remove('show');
                    });
                });
            });
        </script>
    </div>
</body>

</html>
