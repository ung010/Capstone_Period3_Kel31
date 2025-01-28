<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
</head>

<body class="body-user">
    <nav class="navbar navbar-expand">
        <div class="container-fluid d-flex gap-2 justify-content-between px-4" style="overflow-y:unset">
            <div class="d-flex gap-5 align-items-center">
                @if (auth()->user()->role === 'mahasiswa')
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle navbar-text" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ $user->foto ? asset('storage/foto/mahasiswa/' . $user->foto) : asset('asset/default avatar.png') }}"
                                alt="avatar" class="rounded-circle" style="width: 40px; height: 40px">
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/user/my_account">My
                                    Account</a></li>
                            <li><a class="dropdown-item" href="/logout">Logout</a></li>
                        </ul>
                    </div>
                    <a href="{{ route('mahasiswa.index') }}" class="navbar-text">DASHBOARD</a>
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle navbar-text" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">PERSURATAN</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/srt_mhw_asn">Surat Keterangan Masih Kuliah (Bagi
                                    ASN)</a></li>
                            <li><a class="dropdown-item" href="/srt_masih_mhw">Surat Keterangan Masih Mahasiswa</a></li>
                            <li><a class="dropdown-item" href="/srt_magang">Surat Izin Magang</a></li>
                            <li><a class="dropdown-item" href="/srt_izin_plt">Surat Izin Penelitian</a></li>
                            <li><a class="dropdown-item" href="/srt_pmhn_kmbali_biaya">Surat Permohonan Pengembalian
                                    Biaya
                                    Pendidikan</a></li>
                            <li><a class="dropdown-item" href="/srt_bbs_pnjm">Surat Bebas Pinjam</a></li>
                        </ul>
                    </div>
                    <a href="/legalisir" class="navbar-text">LEGALISIR</a>
                @else
                    <div class="nav-item dropdown">
                        <a class="navbar-text" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ $user->foto ? asset('storage/foto/mahasiswa/' . $user->foto) : asset('asset/default avatar.png') }}"
                                alt="avatar" class="rounded-circle" style="width: 40px; height: 40px">
                        </a>
                    </div>
                    <a class="navbar-text disabled" aria-disabled="true">DASHBOARD</a>
                    <div class="nav-item dropdown">
                        <a class="nav-link navbar-text disabled" href="#" role="button" aria-expanded="false"
                            aria-disabled="true">PERSURATAN</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item disabled" href="#" aria-disabled="true">Surat Keterangan
                                    Masih Kuliah (Bagi ASN)</a></li>
                            <li><a class="dropdown-item disabled" href="#" aria-disabled="true">Surat Keterangan
                                    Masih Mahasiswa</a></li>
                            <li><a class="dropdown-item disabled" href="/srt_magang">Surat Izin Magang</a></li>
                            <li><a class="dropdown-item disabled" href="#" aria-disabled="true">Surat Izin
                                    Penelitian</a></li>
                            <li><a class="dropdown-item disabled" href="/srt_pmhn_kmbali_biaya">Surat Permohonan
                                    Pengembalian
                                    Biaya Pendidikan</a></li>
                            <li><a class="dropdown-item disabled" href="#" aria-disabled="true">Surat Bebas
                                    Pinjam</a></li>
                        </ul>
                    </div>
                    <a class="navbar-text disabled" aria-disabled="true">LEGALISIR</a>
                @endif
            </div>
            <div class="d-flex gap-2 align-items-center">
                <p class="navbar-text">LAYANAN ADMINISTRASI MAHASISWA</p>
                <img src="{{ asset('asset/new-web-logo-feb 1.png') }}" style="max-width: 240px;" alt="logo-undip">
            </div>
        </div>
    </nav>

    @include('template/pesan')

    <div class="container-fluid">
        @yield('content')
    </div>

    <footer class="footer-user">
        <div class="d-flex justify-content-between" style="padding: 2rem 10rem 0 10rem">
            <div class="d-flex gap-4 flex-column align-items-center">
                <img src="{{ asset('asset/Location.png') }}" alt="location" class="image-footer">
                <div class="d-flex flex-column align-items-center">
                    <p class="text-footer">Jl. Prof. Moeljono Trastotenojo,</p>
                    <p class="text-footer">Tembalang, Semarang, Indonesia</p>
                </div>
            </div>
            <div class="d-flex gap-4 flex-column align-items-center">
                <img src="{{ asset('asset/Call Center.png') }}" alt="location" class="image-footer">
                <div class="d-flex flex-column align-items-center">
                    <p class="text-footer">Tel : +62 24 76486841</p>
                    <p class="text-footer">Tel : +62 24 76486850</p>
                    <p class="text-footer">Whatsapp : +62 82-12502-3660</p>
                </div>
            </div>
            <div class="d-flex gap-4 flex-column align-items-center">
                <img src="{{ asset('asset/Mail.png') }}" alt="location" class="image-footer">
                <div class="d-flex flex-column align-items-center">
                    <p class="text-footer">feb@live.undip.ac.id</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script>
        document.querySelectorAll('.disabled').forEach(function(element) {
            element.addEventListener('click', function(event) {
                event.preventDefault(); // Mencegah klik
            });
        });
    </script>
    @yield('script')
</body>

</html>
