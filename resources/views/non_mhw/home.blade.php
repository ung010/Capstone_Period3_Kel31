@extends('user.layout')

@section('content')
    <div class="d-flex justify-content-center align-items-center gap-4" style="margin-top: 5%">
        <div class="card">
            <div class="card-body card-user">
                <div class="d-flex gap-3 flex-column">
                    <p style="font-size: 18px; font-weight: 600; margin:0">Halo, {{ auth()->user()->nama }}</p>
                    <p style="font-size: 18px; font-weight: 600; margin:0">Akun anda sedang dalam proses verifikasi
                        data, mohon ditunggu </p>
                    <div class="d-flex gap-2">
                        <a href="/logout" class="btn btn-danger">Log Out</a>
                        <a href="{{ route('non_mhw.edit') }}" class="btn btn-primary">My Account</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
