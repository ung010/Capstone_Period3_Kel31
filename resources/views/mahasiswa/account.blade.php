@extends('user.layout')

@section('content')
    <div class="d-flex justify-content-center align-items-center gap-4" style="margin-top: 1%;">
        <div class="card">
            <div class="card-body p-5 row">
                <div class="d-flex justify-content-center align-items-center">
                    <h3>DATA DIRI</h3>
                </div>
                <form action='{{ route('mahasiswa.update', $user->id) }}' method='post' enctype="multipart/form-data" class="row">
                    @csrf
                    <div class="col-6">
                        <div class="d-flex flex-column gap-2">
                            <div class="form-group">
                                <label for="">Email</label>
                                <input type="email" name="email" value="{{ $user->email }}" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Password</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Nama</label>
                                <input type="text" name="nama" value="{{ $user->nama }}" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">NIM</label>
                                <input type="number" name="nim_nip" value="{{ $user->nim_nip }}" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label for="">Nama Ibu</label>
                                <input type="text" name="nama_ibu" value="{{ $user->nama_ibu }}" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">No Handphone</label>
                                <input type="text" name="nowa" value="{{ $user->nowa }}" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Alamat Asal</label>
                                <input type="text" name="almt_asl" value="{{ $user->almt_asl }}" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-column gap-2">
                            <div class="form-group">
                                <label for="">Tempat Lahir</label>
                                <input type="text" name="kota" value="{{ $user->kota }}" class="form-control" readonly>
                            </div>
                            <div class="form-group d-flex">
                                <label for="" class="col-4">Tanggal Lahir</label>
                                <div class="col-4">
                                    <input type="date" name="tanggal_lahir" value="{{ $user->tanggal_lahir }}" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-6">
                                    <label for="">Departemen</label>
                                    <input type="text" class="form-control" value="{{ $user->prodi->departement->nama_dpt }}" readonly>
                                </div>
                                <div class="col-6">
                                    <label for="">Program Studi</label>
                                    <input type="text" class="form-control" value="{{ $user->prodi->nama_prd }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="">Identitas</label>
                                <img src="{{ asset('storage/foto/mahasiswa/' . $user->foto) }}" alt="identitas" style="max-width: 280px">
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
