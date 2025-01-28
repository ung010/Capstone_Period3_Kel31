<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Error</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <style>
        .full-height {
            height: 100vh;
        }

        body {
            background-image: url('{{ asset('asset/embung_feb2 1.png') }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
        }
    </style>
</head>

<body class="d-flex justify-content-center align-items-center full-height flex-column gap-3">
    @include('template/pesan')
    <div class="card" style="width: 510px">
        <div class="card-head p-2">
            <img src="{{ asset('asset/new-web-logo-feb 2.png') }}" alt="logo undip" style="width:100%">
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-center align-items-center p-3 flex-column">
                <h4>Silahkan lakukan login ulang atau kembali ke halaman awal</h4>
                <div class="d-flex justify-content-center align-items-center gap-3">
                    <a href="/logout" class="btn btn-danger">Logout</a>
                    @if (auth()->user()->role === 'mahasiswa')
                        <a href="/user" class="btn btn-primary">Home</a>
                    @elseif (auth()->user()->role === 'non_mahasiswa')
                        <a href="/non_user" class="btn btn-primary">Home</a>
                    @elseif (auth()->user()->role === 'del_mahasiswa')
                        <a href="/del_user" class="btn btn-primary">Home</a>
                    @elseif (auth()->user()->role === 'admin')
                        <a href="/admin" class="btn btn-primary">Home</a>
                    @elseif (auth()->user()->role === 'supervisor_akd')
                        <a href="/supervisor_akd" class="btn btn-primary">Home</a>
                    @elseif (auth()->user()->role === 'supervisor_sd')
                        <a href="/supervisor_sd" class="btn btn-primary">Home</a>
                    @elseif (auth()->user()->role === 'manajer')
                        <a href="/manajer" class="btn btn-primary">Home</a>
                    @elseif (auth()->user()->role === 'wd1')
                        <a href="/wd1" class="btn btn-primary">Home</a>
                    @elseif (auth()->user()->role === 'wd2')
                        <a href="/wd2" class="btn btn-primary">Home</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
