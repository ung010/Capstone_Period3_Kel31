<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\departemen;
use App\Models\prodi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {

        $this->call(SurveySeeder::class);

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
        ];
        foreach ($departements as  $departement) {
            departemen::create($departement);
        }
        $prodis = [
            [

                'nama_prd' => 'S1 - Manajemen',
                'dpt_id' => 1,
            ],
            [

                'nama_prd' => 'S2 - Manajemen',
                'dpt_id' => 1,
            ],
            [

                'nama_prd' => 'S1 - Digital Bisnis',
                'dpt_id' => 1,
            ],
            [

                'nama_prd' => 'S1 - Ekonomi',
                'dpt_id' => 2,
            ],
            [

                'nama_prd' => 'S2 - Ekonomi',
                'dpt_id' => 2,
            ],
            [

                'nama_prd' => 'Doktor Ilmu Ekonomi',
                'dpt_id' => 2,
            ],
            [

                'nama_prd' => 'Ekonomi Islam',
                'dpt_id' => 2,
            ],
            [

                'nama_prd' => 'S1 - Akuntansi',
                'dpt_id' => 3,
            ],
            [

                'nama_prd' => 'S2 - Akuntansi',
                'dpt_id' => 3,
            ],
            [

                'nama_prd' => 'Pendidikan Profesi Akuntan',
                'dpt_id' => 3,
            ],
        ];
        foreach ($prodis as $prodi) {
            prodi::create($prodi);
        }

        $faker = Faker::create('id_ID');
        $imageDirectory = public_path('storage/foto/mahasiswa');
        $imageFiles = File::files($imageDirectory);
        $randomImage = $faker->randomElement($imageFiles);
        $imageName = basename($randomImage);

        $users = [
            [
                'id' => mt_rand(1000000000000, 9999999999999),
                'nama' => 'Raung',
                'nim_nip' => '21120120150155',
                'email' => 'raung@students.undip.ac.id',
                'password' => bcrypt('1234567'),
                'role' => 'mahasiswa',
                'status' => 'mahasiswa',
                'kota' => 'Blitar',
                'tanggal_lahir' => Carbon::createFromFormat('d - m - Y', '5 - 10 - 2010'),
                'nowa' => '081214549624',
                'almt_asl' => 'Jl Kenari No 20 RT 2 RW 3 Tembalang Semarang',
                'prd_id' => 1,
                'catatan_user' => '-',
                'nama_ibu' => 'Rosa0',
                'foto' => $imageName,
            ],
            [
                'id' => mt_rand(1000000000000, 9999999999999),
                'nama' => 'Rosa',
                'nim_nip' => '21120120160155',
                'email' => 'satu@gmail.com',
                'password' => bcrypt('1234567'),
                'role' => 'mahasiswa',
                'status' => 'mahasiswa',
                'kota' => 'Wonosobo',
                'tanggal_lahir' => Carbon::createFromFormat('d - m - Y', '5 - 11 - 2001'),
                'nowa' => '081214549624',
                'almt_asl' => 'Jl Kenari No 20 RT 2 RW 3 Wonosobo Jawa Timur',
                'prd_id' => 1,
                'catatan_user' => '-',
                'nama_ibu' => 'Zahra',
                'foto' => $imageName,
            ],
            [
                'id' => mt_rand(1000000000000, 9999999999999),
                'nama' => 'Test',
                'nim_nip' => '21120120170155',
                'email' => 'test@gmail.com',
                'password' => bcrypt('1234567'),
                'role' => 'mahasiswa',
                'status' => 'mahasiswa',
                'kota' => 'Wonosobo',
                'tanggal_lahir' => Carbon::createFromFormat('d - m - Y', '5 - 11 - 2001'),
                'nowa' => '081214549624',
                'almt_asl' => 'Jl Kenari No 20 RT 2 RW 3 Wonosobo Jawa Timur',
                'prd_id' => 1,
                'catatan_user' => '-',
                'nama_ibu' => 'Testing',
                'foto' => $imageName,
            ],
            [
                'id' => mt_rand(1000000000000, 9999999999999),
                'nama' => 'Andi Prihandoyo, S.T.',
                'nim_nip' => 'H.7.197704082021101001',
                'email' => 'andiprihandoyo01@staff.undip.ac.id',
                'password' => bcrypt('1234567'),
                'role' => 'admin',
            ],
            [
                'id' => mt_rand(1000000000000, 9999999999999),
                'nama' => 'Luluk Evriyanti, S.E',
                'nim_nip' => 'H.7.199504252024052001',
                'email' => 'lulukevriyanti02@staff.undip.ac.id',
                'password' => bcrypt('1234567'),
                'role' => 'admin',
            ],
            [
                'id' => mt_rand(1000000000000, 9999999999999),
                'nama' => 'Ex - Admin',
                'nim_nip' => 'xxx',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('1234567'),
                'role' => 'admin',
            ],
            [
                'id' => mt_rand(1000000000000, 9999999999999),
                'nama' => 'R M Endhar Priyo Utomo, S.S',
                'nim_nip' => '197901102014091002',
                'email' => 'akademik@gmail.com',
                'password' => bcrypt('1234567'),
                'role' => 'supervisor_akd',
            ],
            [
                'id' => mt_rand(1000000000000, 9999999999999),
                'nama' => 'Suryani, S.E.',
                'nim_nip' => 'H.7.198601242009082001',
                'email' => 'sumber@gmail.com',
                'password' => bcrypt('1234567'),
                'role' => 'supervisor_sd',
            ],
            [
                'id' => mt_rand(1000000000000, 9999999999999),
                'nama' => 'Mia Prameswari, S.E., M.Si',
                'nim_nip' => '197901142006042001',
                'email' => 'manajer@gmail.com',
                'password' => bcrypt('1234567'),
                'role' => 'manajer',
            ],
            [
                'id' => mt_rand(1000000000000, 9999999999999),
                'nama' => 'Saya WD 1',
                'nim_nip' => '1111',
                'email' => 'wd1@gmail.com',
                'password' => bcrypt('1234567'),
                'role' => 'wd1',
            ],
            [
                'id' => mt_rand(1000000000000, 9999999999999),
                'nama' => 'Saya WD 2',
                'nim_nip' => '2222',
                'email' => 'wd2@gmail.com',
                'password' => bcrypt('1234567'),
                'role' => 'wd2',
            ],
        ];
        foreach ($users as  $user) {
            User::create($user);
        }

        $this->call([
            UserSeeder::class,
            // SurveySeeder::class,
            SuratSeeder::class,
        ]);
    }
}
