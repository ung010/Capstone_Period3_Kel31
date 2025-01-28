@extends('manajer.layout')

@section('content')

    <head>
        <title>Edit Account - {{ auth()->user()->nama }}</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    </head>

    <body>
        <div class="container py-5">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Edit Account</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('manajer.update_account', $user->id) }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" name="nama" value="{{ $user->nama }}" class="form-control"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label for="nim_nip" class="form-label">NIP</label>
                                <input type="text" name="nim_nip" value="{{ $user->nim_nip }}" class="form-control"
                                    required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password <small class="text-muted">(Kosongkan jika
                                    tidak ingin mengubah)</small></label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="/manajer" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
@endsection
