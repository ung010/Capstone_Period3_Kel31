<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign Up Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <style>
        body {
            background-image: url('{{ asset('asset/embung_feb2 1.png') }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
        }
    </style>
</head>

<body class="d-flex justify-content-center align-items-center flex-column gap-3">
    @include('template/pesan')
    <div class="card my-5" style="width: 510px">
        <div class="card-body">
            <form action="{{ route('register.create') }}" class="px-5 pb-5" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="d-flex justify-content-center align-items-center">
                    <h1>Isi Data Diri</h1>
                </div>
                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="contoh@students.undip.ac.id"
                        value="{{ Session::get('email') }}" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="">Password</label>
                    <input type="password" id="password" name="password" placeholder="Minimal 8 Karakter"
                        value="{{ Session::get('password') }}" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="">Nama</label>
                    <input type="text" name="nama" value="{{ Session::get('nama') }}" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="">NIM</label>
                    <input type="number" name="nim_nip" value="{{ Session::get('nim_nip') }}" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="">Tempat Lahir</label>
                    <input type="text" name="kota" value="{{ Session::get('kota') }}" class="form-control">
                </div>
                <div class="form-group mb-3 d-flex">
                    <label for="" class="col-4">Tanggal Lahir</label>
                    <div class="col-4">
                        <input type="date" name="tanggal_lahir" value="{{ Session::get('tanggal_lahir') }}"
                            class="form-control">
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label for="">Nama Ibu</label>
                    <input type="text" name="nama_ibu" value="{{ Session::get('nama_ibu') }}" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="">No Handphone</label>
                    <input type="number" name="nowa" placeholder="089342774921" value="{{ Session::get('nowa') }}"
                        class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label for="">Alamat Asal</label>
                    <input type="text" name="almt_asl" value="{{ Session::get('almt_asl') }}" id=""
                        class="form-control">
                </div>
                <label for="">Sebelum memilih prodi, pilih departemen terlebih dahulu</label>
                <br>
                <div class="form-group mb-3 d-flex justify-content-center gap-3 px-2">
                    <div class="col-6">
                        <label for="">Departemen</label>
                        <select class="form-select" name='dpt_id' id="dpt_id">
                            <option value="" selected>Select Option</option>
                            @foreach ($departemen as $dpt)
                                <option value="{{ $dpt->id }}">{{ $dpt->nama_dpt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <label for="">Program Studi</label>
                        <select class="form-select" name='prd_id' id="prd_id">
                            <option value="" selected>Select Option</option>
                        </select>
                    </div>
                </div>
                <div class="form-group mb-3 d-flex flex-column col-6">
                    <div class="col-6 d-flex flex-column gap-1">
                        <label for="">Status</label>
                        <div class="d-flex gap-2">
                            <input type="radio" name="status" id="" value="mahasiswa"
                                {{ Session::get('status') == 'mahasiswa' ? 'checked' : '' }}> Mahasiswa Aktif
                        </div>
                        <div class="d-flex gap-2">
                            <input type="radio" name="status" id="" value="alumni"
                                {{ Session::get('status') == 'alumni' ? 'checked' : '' }}> Alumni
                        </div>
                    </div>
                    <p class="text-danger" style="font-size: 10px; font-weight: 700;">*Mahasiswa Aktif silahkan Upload
                        KTM
                        *Alumni Silahkan Upload Screenshot Akun SIAP Alumni
                    </p>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <label for="foto" class="btn btn-secondary">Upload Identitas</label>
                    <input type="file" name="foto" id="foto" class="d-none">
                    <button type="submit" name="submit" class="btn btn-warning">Sign Up</button>
                </div>
                <div class="mt-3">
                    <img id="img-preview" src="#" alt="Preview Image" class="img-fluid" style="display: none; max-height: 200px;">
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('foto').addEventListener('change', function() {
            const [file] = this.files;
            if (file) {
                const imgPreview = document.getElementById('img-preview');
                imgPreview.src = URL.createObjectURL(file);
                imgPreview.style.display = 'block'; // Show the image
            }
        });

        document.getElementById('dpt_id').addEventListener('change', function() {
            var departemenId = this.value;
            var prdDropdown = document.getElementById('prd_id');

            prdDropdown.innerHTML = '<option value="" selected>Select Option</option>'; // Clear existing options

            if (departemenId) {
                fetch('/get-prodi/' + departemenId)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(function(prodi) {
                            var option = document.createElement('option');
                            option.value = prodi.id;
                            option.text = prodi.nama_prd;
                            prdDropdown.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error:', error));
            }
        });
    </script>

    <script>
        document.getElementById('email').addEventListener('input', function () {
            var emailInput = this.value;
            var domain = '@students.undip.ac.id';

            if (emailInput.includes('@') && emailInput.indexOf(domain) === -1) {
                this.value = emailInput.split('@')[0] + domain;
            }
        });
    </script>
</body>

</html>
