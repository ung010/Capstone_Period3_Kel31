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
    DB::table('survey')->truncate();

    $faker_survey = Faker::create('id_ID');
    $excluded_roles = ['non_mahasiswa', 'del_mahasiswa', 'admin', 'supervisor_akd', 'supervisor_sd', 'manajer', 'wd1', 'wd2'];

    // Ambil semua pengguna dengan role mahasiswa (sesuai dengan role yang tidak ada dalam $excluded_roles)
    $user_ids = DB::table('users')
        ->whereNotIn('role', $excluded_roles)
        ->pluck('id')
        ->toArray();

    $ratings = ['sangat_puas', 'puas', 'netral', 'kurang_puas', 'tidak_puas'];

    $feedback = [
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

    // Iterasi untuk setiap user mahasiswa yang ada
    foreach ($user_ids as $user_id) {
        $user = DB::table('users')->where('id', $user_id)->first();

        if (!$user) {
            continue; // Jika user tidak ditemukan, lewati
        }

        // Pastikan hanya mahasiswa yang diberikan survey
        if (in_array($user->role, ['mahasiswa'])) {
            $id = mt_rand(1000000000000, 9999999999999);
            $rating = $faker_survey->randomElement($ratings);
            $tanggal_survey = Carbon::create(
                rand(2023, 2024),
                rand(1, 12),
                rand(1, 28)
            )->toDateString();

            // Insert ke tabel survey hanya jika user ini belum ada survey sebelumnya
            $existingSurvey = DB::table('survey')
                ->where('users_id', $user->id)
                ->exists(); // Cek apakah user ini sudah memiliki survey

            if (!$existingSurvey) {
                DB::table('survey')->insert([
                    'id' => $id,
                    'users_id' => $user->id,
                    // 'nama_mhw' => $user->nama,
                    'rating' => $rating,
                    'feedback' => $faker_survey->randomElement($feedback),
                    'tanggal_survey' => $tanggal_survey,
                    'prd_id' => $user->prd_id,
                ]);
            }
        }
    }
}

}
