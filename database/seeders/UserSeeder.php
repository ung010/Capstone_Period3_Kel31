<?php

namespace Database\Seeders;

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

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $gender = 'female';
        $imageDirectory = public_path('storage/foto/mahasiswa');
        $imageFiles = File::files($imageDirectory);
        $randomImage = $faker->randomElement($imageFiles);
        $imageName = basename($randomImage);

        foreach (range(1, 50) as $index) {
            $id = mt_rand(1000000000000, 9999999999999);
            $randomImage = $faker->randomElement($imageFiles);
            $imageName = basename($randomImage);
            DB::table('users')->insert([
                'id' => $id,
                'nama' => $faker->firstName . ' ' . $faker->lastName,
                'nim_nip' => '211201' . $faker->unique()->numerify('########'),
                'email' => Str::lower($faker->unique()->firstName) . '@students.undip.ac.id',
                'password' => Hash::make('1234567'),
                'role' => $faker->randomElement(['non_mahasiswa', 'mahasiswa']),
                'status' => $faker->randomElement(['mahasiswa', 'alumni']),
                'nowa' => preg_replace('/[^0-9]/', '', $faker->phoneNumber),
                'kota' => $faker->city,
                'nama_ibu' => $faker->firstName($gender) . ' ' . $faker->lastName,
                'tanggal_lahir' => $faker->date($format = 'Y-m-d', $max = '2008-01-01', $min = '1999-01-01',),
                'almt_asl' => $faker->address,
                'catatan_user' => '-',
                'foto' => $imageName,
                'prd_id' => $faker->numberBetween(1, 10),
            ]);
        }
    }
}
