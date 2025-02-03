<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class SurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $excludedRoles = ['non_mahasiswa', 'del_mahasiswa', 'admin', 'supervisor_akd', 'supervisor_sd', 'manajer', 'wd1', 'wd2'];
        $userIds = DB::table('users')
            ->whereNotIn('role', $excludedRoles)
            ->pluck('id');
        $ratings = ['sangat_puas', 'puas', 'netral', 'kurang_puas', 'tidak_puas'];
        $feedbacks = [
            'Tampilan website sangat menarik, tetapi loading halamannya masih terlalu lama.',
            'Navigasi menu di website agak membingungkan, mungkin bisa diperjelas lagi.',
            'Website ini sudah cukup bagus, namun fitur pencarian terkadang tidak memberikan hasil yang relevan.',
            'Desain responsifnya bagus, tetapi ada beberapa elemen yang tidak ditampilkan dengan baik di perangkat mobile.',
            'Saran saya, tambahkan fitur filter untuk mempermudah pencarian produk di website.',
            'Website sangat informatif, tetapi mungkin bisa diperbaiki dari segi kecepatan akses.',
            'Ada beberapa bug yang muncul ketika mengakses halaman login.',
            'Sebaiknya ditambahkan lebih banyak panduan penggunaan agar pengguna baru lebih mudah memahami.',
            'Website ini sangat fungsional, namun tampilannya bisa dibuat lebih modern dan user-friendly.',
            'Konten sudah cukup lengkap, tetapi ada beberapa link yang tidak berfungsi dengan baik.'
        ];
        foreach ($userIds as $userId) {
            $existingSurvey = DB::table('survey')
                ->where('users_id', $userId)
                ->exists();
                
            if (!$existingSurvey) {
                DB::table('survey')->insert([
                    'id' => mt_rand(1000000000000, 9999999999999),
                    'users_id' => $userId,
                    'rating' => $faker->randomElement($ratings),
                    'feedback' => $faker->randomElement($feedbacks),
                    'tanggal_survey' => Carbon::create(rand(2023, 2024), rand(1, 12), rand(1, 28))->toDateString(),
                    'prd_id' => DB::table('users')->where('id', $userId)->value('prd_id'),
                ]);
            }
        }
    }
}
