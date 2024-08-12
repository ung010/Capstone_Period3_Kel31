<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\departemen;
use App\Models\jenjang_pendidikan;
use App\Models\prodi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\Dosen::create([

        //     'nama_dosen' => 'Pak Ibra',

        // ]);

        $prodis = [
            [

                'nama_prd' => 'Manajemen'
            ],
            [

                'nama_prd' => 'Digital Bisnis'
            ],
            [

                'nama_prd' => 'Ekonomi'
            ],
            [

                'nama_prd' => 'Ekonomi Islam'
            ],
            [

                'nama_prd' => 'Akuntansi'
            ],
            [

                'nama_prd' => 'Pendidikan Profesi Akuntan'
            ],
            [

                'nama_prd' => 'Doktor Ilmu Ekonomi'
            ],
        ];
        foreach ($prodis as $prodi) {
            prodi::create($prodi);
        }

        $jenjangs = [
            [

                'nama_jnjg' => 'S1'
            ],
            [

                'nama_jnjg' => 'S2'
            ],
            [

                'nama_jnjg' => 'S3'
            ],
        ];
        foreach ($jenjangs as $jenjang) {
            jenjang_pendidikan::create($jenjang);
        }

        $departements = [
            [

                'nama_dpt' => 'Manajemen'
            ],
            [

                'nama_dpt' => 'IESP'
            ],
            [

                'nama_dpt' => 'Akuntansi'
            ],
            [

                'nama_dpt' => 'Doktor Ilmu Ekonomi'
            ],
        ];
        foreach ($departements as  $departement) {
            departemen::create($departement);
        }

        $faker = Faker::create('id_ID');
        $gender = 'female';

        foreach (range(1, 50) as $index) {
            DB::table('users')->insert([
                'nama' => $faker->name,
                'nmr_unik' => '211201' . $faker->unique()->numerify('########'),
                'email' => $faker->unique()->userName . '@gmail.com',
                'password' => Hash::make('mountain082'),
                'role' => $faker->randomElement(['non_mahasiswa', 'mahasiswa', 'del_mahasiswa']),
                'status' => $faker->randomElement(['mahasiswa', 'alumni']),
                'nowa' => $faker->phoneNumber,
                'kota' => $faker->city,
                'nama_ibu' => $faker->firstName($gender) . ' ' . $faker->lastName,
                'tanggal_lahir' => $faker->date($format = 'Y-m-d', $max = '2008-01-01', $min = '1999-01-01',),
                'almt_asl' => $faker->address,
                'catatan_user' => '-',
                'foto' => $faker->imageUrl(400, 400, 'people'),
                'dpt_id' => $faker->numberBetween(1, 4),
                'prd_id' => $faker->numberBetween(1, 7),
                'jnjg_id' => $faker->numberBetween(1, 3),
            ]);
        }

        $users = [
            [

                'nama' => 'Romusha TA',
                'nmr_unik' => '21120120150155',
                'email' => 'mahasiswa@gmail.com',
                'password' => bcrypt('mountain082'),
                'role' => 'mahasiswa',
                'status' => 'mahasiswa',
                'kota' => 'Blitar',
                'tanggal_lahir' => Carbon::createFromFormat('d - m - Y', '5 - 10 - 2010'),
                'nowa' => '081214549624',
                'almt_asl' => 'Jl Kenari No 20 RT 2 RW 3 Tembalang Semarang',
                'dpt_id' => 1,
                'prd_id' => 2,
                'jnjg_id' => 2,
                'catatan_user' => '-',
                'nama_ibu' => 'Rosa0',
            ],
            [

                'nama' => 'Leo',
                'nmr_unik' => '64568775634',
                'email' => 'leo@gmail.com',
                'password' => bcrypt('mountain082'),
                'role' => 'mahasiswa',
                'status' => 'mahasiswa',
                'kota' => 'leo',
                'tanggal_lahir' => Carbon::createFromFormat('d - m - Y', '5 - 11 - 2003'),
                'nowa' => '081214549624',
                'almt_asl' => 'Jl Kenari No 20 RT 2 RW 3 Tembalang Semarang',
                'dpt_id' => 2,
                'prd_id' => 2,
                'jnjg_id' => 3,
                'catatan_user' => '-',
                'nama_ibu' => 'leo',
            ],
            [

                'nama' => 'Raung Calon Sarjana',
                'nmr_unik' => '211201201444444',
                'email' => 'alumni@gmail.com',
                'password' => bcrypt('mountain082'),
                'role' => 'mahasiswa',
                'status' => 'alumni',
                'kota' => 'Blitar',
                'tanggal_lahir' => Carbon::createFromFormat('d - m - Y', '5 - 10 - 2010'),
                'nowa' => '081214549624',
                'almt_asl' => 'Jl Perkutut No 20 RT 2 RW 3 Beru Kendari',
                'dpt_id' => 2,
                'prd_id' => 6,
                'jnjg_id' => 2,
                'catatan_user' => '-',
                'nama_ibu' => 'Rosa1',
            ],
            [

                'nama' => 'admin1',
                'nmr_unik' => '101',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('mountain082'),
                'role' => 'admin',
            ],
            [

                'nama' => 'admin2',
                'nmr_unik' => '102',
                'email' => 'testingadmin1@gmail.com',
                'password' => bcrypt('mountain082'),
                'role' => 'admin',
            ],
            [

                'nama' => 'Supervisor akademik',
                'nmr_unik' => '201',
                'email' => 'akademik@gmail.com',
                'password' => bcrypt('mountain082'),
                'role' => 'supervisor_akd',
            ],
            [

                'nama' => 'Supervisor Sumber Daya',
                'nmr_unik' => '301',
                'email' => 'sumber@gmail.com',
                'password' => bcrypt('mountain082'),
                'role' => 'supervisor_sd',
            ],
            [

                'nama' => 'Manajer',
                'nmr_unik' => '401',
                'email' => 'manajer@gmail.com',
                'password' => bcrypt('mountain082'),
                'role' => 'manajer',
            ],
        ];
        foreach ($users as  $user) {
            User::create($user);
        }

        $faker_srt_mhw_asn = Faker::create('id_ID');
        $excluded_roles = ['non_mahasiswa', 'del_mahasiswa', 'admin', 'supervisor_akd', 'supervisor_sd', 'manajer'];
        $user_ids = DB::table('users')
            ->whereNotIn('role', $excluded_roles)
            ->pluck('id')
            ->toArray();

        $list_role = ['mahasiswa', 'admin', 'supervisor_akd', 'manajer'];

        foreach (range(1, 40) as $index) {

            $random_user_id = $faker_srt_mhw_asn->randomElement($user_ids);
            $user = DB::table('users')->where('id', $random_user_id)->first();
            $nama_mhw = $user->nama;
            $nim_mhw = $user->nmr_unik;
            $nowa_mhw = $user->nowa;
            $thn_awl = $faker_srt_mhw_asn->numberBetween(2010, 2020);
            $thn_akh = $thn_awl + 1;
            $semester = $faker_srt_mhw_asn->numberBetween(3, 13);
            $role_surat = $faker_srt_mhw_asn->randomElement($list_role);
            $tanggal_surat = Carbon::now()->toDateString();

            $jenjang = DB::table('jenjang_pendidikan')->where('id', $user->jnjg_id)->value('nama_jnjg');
            $prodi = DB::table('prodi')->where('id', $user->prd_id)->value('nama_prd');
            $jenjang_prodi = $jenjang . ' - ' . $prodi;

            DB::table('srt_mhw_asn')->insert([
                'users_id' => $random_user_id,
                'nama_mhw' => $nama_mhw,
                'nim_mhw' => $nim_mhw,
                'nowa_mhw' => $nowa_mhw,
                'jenjang_prodi' => $jenjang_prodi,
                'dpt_id' => $user->dpt_id,
                'prd_id' => $user->prd_id,
                'jnjg_id' => $user->jnjg_id,
                'thn_awl' => $thn_awl,
                'thn_akh' => $thn_akh,
                'semester' => $semester,
                'role_surat' => $role_surat,
                'nama_ortu' => $faker_srt_mhw_asn->name(),
                'nip_ortu' => $faker_srt_mhw_asn->unique()->numerify('########'),
                'ins_ortu' => $faker_srt_mhw_asn->company(),
                'tanggal_surat' => $tanggal_surat,
            ]);
        }

        $faker_srt_masih_mhw = Faker::create('id_ID');
        $excluded_roles = ['non_mahasiswa', 'del_mahasiswa', 'admin', 'supervisor_akd', 'supervisor_sd', 'manajer'];
        $user_ids = DB::table('users')
            ->whereNotIn('role', $excluded_roles)
            ->pluck('id')
            ->toArray();

        $alasan_acak = ['sakit', 'berpegian', 'menjenguk', 'acara keluarga', 'urusan pribadi'];
        $list_role = ['mahasiswa', 'admin', 'supervisor_akd', 'manajer', 'manajer_sukses'];

        foreach (range(1, 40) as $index) {

            $random_user_id = $faker_srt_masih_mhw->randomElement($user_ids);
            $user = DB::table('users')->where('id', $random_user_id)->first();
            $nama_mhw = $user->nama;
            $thn_awl = $faker_srt_masih_mhw->numberBetween(2010, 2020);
            $thn_akh = $thn_awl + 1;
            $semester = $faker_srt_masih_mhw->numberBetween(3, 13);
            $tujuan_buat_srt = $faker_srt_masih_mhw->randomElement($alasan_acak);
            $role_surat = $faker_srt_masih_mhw->randomElement($list_role);
            $tanggal_surat = Carbon::now()->toDateString();

            DB::table('srt_masih_mhw')->insert([
                'users_id' => $random_user_id,
                'nama_mhw' => $nama_mhw,
                'dpt_id' => $user->dpt_id,
                'prd_id' => $user->prd_id,
                'jnjg_id' => $user->jnjg_id,
                'thn_awl' => $thn_awl,
                'thn_akh' => $thn_akh,
                'semester' => $semester,
                'role_surat' => $role_surat,
                'tujuan_buat_srt' => $tujuan_buat_srt,
                'tanggal_surat' => $tanggal_surat,
                'almt_smg' => $faker_srt_masih_mhw->address(),
                'tujuan_akhir' => $faker_srt_masih_mhw->randomElement(['manajer', 'wd']),
            ]);
        }

        $faker_srt_magang = Faker::create('id_ID');
        $excluded_roles = ['non_mahasiswa', 'del_mahasiswa', 'admin', 'supervisor_akd', 'supervisor_sd', 'manajer'];
        $user_ids = DB::table('users')
            ->whereNotIn('role', $excluded_roles)
            ->pluck('id')
            ->toArray();

        foreach (range(5, 35) as $index) {

            $random_user_id = $faker_srt_magang->randomElement($user_ids);
            $user = DB::table('users')->where('id', $random_user_id)->first();
            $nama_mhw = $user->nama;
            $tanggal_surat = Carbon::now()->toDateString();
            $ipk = round($faker_srt_magang->randomFloat(2, 3.00, 3.99), 2);
            $sksk = $faker_srt_magang->numberBetween(120, 140);
            $semester = $faker_srt_magang->numberBetween(3, 13);
            $jbt_lmbg = $faker_srt_magang->jobTitle();

            DB::table('srt_magang')->insert([
                'users_id' => $random_user_id,
                'nama_mhw' => $nama_mhw,
                'dpt_id' => $user->dpt_id,
                'prd_id' => $user->prd_id,
                'jnjg_id' => $user->jnjg_id,
                'ipk' => $ipk,
                'sksk' => $sksk,
                'jbt_lmbg' => $jbt_lmbg,
                'role_surat' => $faker_srt_magang->randomElement(['mahasiswa', 'admin', 'supervisor_akd', 'manajer', 'manajer_sukses']),
                'nama_lmbg' => $faker_srt_magang->company(),
                'kota_lmbg' => $faker_srt_magang->city(),
                'almt_lmbg' => $faker_srt_magang->address(),
                'tanggal_surat' => $tanggal_surat,
                'semester' => $semester,
                'almt_smg' => $faker_srt_magang->address(),
            ]);
        }

        $faker_srt_plt = Faker::create('id_ID');
        $excluded_roles = ['non_mahasiswa', 'del_mahasiswa', 'admin', 'supervisor_akd', 'supervisor_sd', 'manajer'];
        $user_ids = DB::table('users')
            ->whereNotIn('role', $excluded_roles)
            ->pluck('id')
            ->toArray();

        $judul = ['Surat TA', 'Surat Magang', 'Surat Menikah Dengan Waifu 2D'];
        $jenis = [
            'Kerja Praktek', 'Tugas Akhir Penelitian Mahasiswa', 'Ijin Penelitian',
            'Survey,',
            'Thesis',
            'Disertasi'
        ];

        foreach (range(1, 35) as $index) {

            $random_user_id = $faker_srt_plt->randomElement($user_ids);
            $user = DB::table('users')->where('id', $random_user_id)->first();
            $nama_mhw = $user->nama;
            $tanggal_surat = Carbon::now()->toDateString();
            $judul_data = $faker_srt_plt->randomElement($judul);
            $jenis_surat = $faker_srt_plt->randomElement($jenis);
            $lampiran = $faker_srt_plt->randomElement(['1 Eksemplar', '2 Eksemplar']);
            $semester = $faker_srt_plt->numberBetween(3, 13);
            $jbt_lmbg = $faker_srt_plt->jobTitle();

            DB::table('srt_izin_plt')->insert([
                'users_id' => $random_user_id,
                'nama_mhw' => $nama_mhw,
                'dpt_id' => $user->dpt_id,
                'prd_id' => $user->prd_id,
                'jnjg_id' => $user->jnjg_id,
                'judul_data' => $judul_data,
                'lampiran' => $lampiran,
                'jbt_lmbg' => $jbt_lmbg,
                'jenis_surat' => $jenis_surat,
                'role_surat' => $faker_srt_plt->randomElement(['mahasiswa', 'admin', 'supervisor_akd', 'manajer', 'manajer_sukses']),
                'nama_lmbg' => $faker_srt_plt->company(),
                'kota_lmbg' => $faker_srt_plt->city(),
                'almt_lmbg' => $faker_srt_plt->address(),
                'tanggal_surat' => $tanggal_surat,
                'semester' => $semester,
            ]);
        }

        $faker_srt_pmhn_kmbali_biaya = Faker::create('id_ID');
        $excluded_roles = ['non_mahasiswa', 'del_mahasiswa', 'admin', 'supervisor_akd', 'supervisor_sd', 'manajer'];
        $user_ids = DB::table('users')
            ->whereNotIn('role', $excluded_roles)
            ->pluck('id')
            ->toArray();

        foreach (range(5, 25) as $index) {

            $random_user_id = $faker_srt_pmhn_kmbali_biaya->randomElement($user_ids);
            $user = DB::table('users')->where('id', $random_user_id)->first();
            $nama_mhw = $user->nama;
            $tanggal_surat = Carbon::now()->toDateString();

            DB::table('srt_pmhn_kmbali_biaya')->insert([
                'users_id' => $random_user_id,
                'nama_mhw' => $nama_mhw,
                'dpt_id' => $user->dpt_id,
                'prd_id' => $user->prd_id,
                'jnjg_id' => $user->jnjg_id,
                'role_surat' => $faker_srt_pmhn_kmbali_biaya->randomElement(['mahasiswa', 'admin', 'supervisor_sd', 'manajer', 'manajer_sukses']),
                'tanggal_surat' => $tanggal_surat,
            ]);
        }

        $faker_srt_bbs_pnjm = Faker::create('id_ID');
        $excluded_roles = ['non_mahasiswa', 'del_mahasiswa', 'admin', 'supervisor_akd', 'supervisor_sd', 'manajer'];
        $user_ids = DB::table('users')
            ->whereNotIn('role', $excluded_roles)
            ->pluck('id')
            ->toArray();

        foreach (range(5, 20) as $index) {

            $random_user_id = $faker_srt_bbs_pnjm->randomElement($user_ids);
            $user = DB::table('users')->where('id', $random_user_id)->first();
            $nama_mhw = $user->nama;
            $tanggal_surat = Carbon::now()->toDateString();
            $almt_smg = $faker_srt_bbs_pnjm->address();
            $dosen_wali = $faker_srt_bbs_pnjm->name();

            DB::table('srt_bbs_pnjm')->insert([
                'users_id' => $random_user_id,
                'nama_mhw' => $nama_mhw,
                'almt_smg' => $almt_smg,
                'dosen_wali' => $dosen_wali,
                'dpt_id' => $user->dpt_id,
                'prd_id' => $user->prd_id,
                'jnjg_id' => $user->jnjg_id,
                'role_surat' => $faker_srt_bbs_pnjm->randomElement(['mahasiswa', 'admin', 'supervisor_sd']),
                'tanggal_surat' => $tanggal_surat,
            ]);
        }

        $faker_lgl = Faker::create('id_ID');
        $excluded_roles = ['non_mahasiswa', 'del_mahasiswa', 'admin', 'supervisor_akd', 'supervisor_sd', 'manajer'];
        $user_ids = DB::table('users')
            ->whereNotIn('role', $excluded_roles)
            ->pluck('id')
            ->toArray();

        $urusan = ['Bekerja di luar negeri', 'Melamar Kerja', 'Melamar Istri', 'Ambil pendidikan tinggi di LN'];
        $jenis_legalisir = ['ijazah', 'transkrip', 'ijazah_transkrip'];
        $cara_pengambilan = ['ditempat', 'dikirim'];


        foreach (range(1, 35) as $index) {

            $random_user_id = $faker_lgl->randomElement($user_ids);
            $user = DB::table('users')->where('id', $random_user_id)->first();
            $nama_mhw = $user->nama;
            $tanggal_surat = Carbon::now()->toDateString();
            $keperluan = $faker_lgl->randomElement($urusan);
            $tanggal_lulus = $faker_lgl->date($format = 'Y-m-d', $max = '2024-08-01', $min = '2019-01-01',);

            DB::table('legalisir')->insert([
                'users_id' => $random_user_id,
                'nama_mhw' => $nama_mhw,
                'tgl_lulus' => $tanggal_lulus,
                'keperluan' => $keperluan,
                'jenis_lgl' => $faker_lgl->randomElement($jenis_legalisir),
                'ambil' => $faker_lgl->randomElement($cara_pengambilan),
                'role_surat' => $faker_lgl->randomElement(['mahasiswa', 'admin', 'supervisor_akd', 'manajer', 'manajer_sukses']),
                'dpt_id' => $user->dpt_id,
                'prd_id' => $user->prd_id,
                'jnjg_id' => $user->jnjg_id,
                'tanggal_surat' => $tanggal_surat,
            ]);
        }

        $faker_survey = Faker::create('id_ID');
        $excluded_roles = ['non_mahasiswa', 'del_mahasiswa', 'admin', 'supervisor_akd', 'supervisor_sd', 'manajer'];
        $user_ids = DB::table('users')
            ->whereNotIn('role', $excluded_roles)
            ->pluck('id')
            ->toArray();

        $ratings = ['sangat_puas', 'puas', 'netral', 'kurang_puas', 'tidak_puas'];

        foreach (range(1, 40) as $index) {
            $random_user_id = $faker_survey->randomElement($user_ids);
            $user = DB::table('users')->where('id', $random_user_id)->first();
            $rating = $faker_survey->randomElement($ratings);
            $tanggal_surat = Carbon::now()->toDateString();

            DB::table('survey')->insert([
                'users_id' => $random_user_id,
                'nama_mhw' => $faker_survey->name,
                'rating' => $rating,
                'feedback' => $faker_survey->sentence,
                'tanggal_surat' => $tanggal_surat,
                'dpt_id' => $user->dpt_id,
                'prd_id' => $user->prd_id,
                'jnjg_id' => $user->jnjg_id,
            ]);
        }
    }
}
