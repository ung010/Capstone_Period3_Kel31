@extends('user.layout')

@section('content')
<title>Akses denied</title>
    <div class="d-flex justify-content-center align-items-center gap-4" style="margin-top: 5%">
        <div class="card">
            <div class="card-body card-user">
                <div class="d-flex gap-3 flex-column">
                    <p style="font-size: 18px; font-weight: 600; margin:0">Untuk {{ auth()->user()->nama }}</p>
                    <p style="font-size: 18px; font-weight: 600; margin:0">Akun anda telah di suspend oleh admin, hubungi admin untuk mengembalikan akun anda</p>
                    <div class="d-flex gap-2">
                        <a href="/logout" class="btn btn-danger">Log Out</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

