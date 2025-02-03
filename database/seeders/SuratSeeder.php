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

class SuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker_srt_mhw_asn = Faker::create('id_ID');
        $excluded_roles = ['non_mahasiswa', 'del_mahasiswa', 'admin', 'supervisor_akd', 'supervisor_sd', 'manajer', 'wd1', 'wd2'];
        $user_ids = DB::table('users')
            ->whereNotIn('role', $excluded_roles)
            ->pluck('id')
            ->toArray();

        $list_role = ['mahasiswa', 'admin', 'supervisor_akd', 'manajer'];

        foreach (range(1, 50) as $index) {

            $random_user_id = $faker_srt_mhw_asn->randomElement($user_ids);
            $user = DB::table('users')->where('id', $random_user_id)->first();
            // $nama_mhw = $user->nama;
            $thn_awl = $faker_srt_mhw_asn->numberBetween(2023, 2024);
            $thn_akh = $thn_awl + 1;
            $semester = $faker_srt_mhw_asn->numberBetween(3, 13);
            $role_surat = $faker_srt_mhw_asn->randomElement($list_role);
            $tanggal_surat = Carbon::create(
                rand(2023, 2024),
                rand(1, 12),
                rand(1, 28)
            )->toDateString();

            $prodi = DB::table('prodi')->where('id', $user->prd_id)->value('nama_prd');
            $id = mt_rand(1000000000000, 9999999999999);

            DB::table('srt_mhw_asn')->insert([
                'id' => $id,
                 'users_id' => $random_user_id,
                // 'nama_mhw' => $nama_mhw,
                'prd_id' => $user->prd_id,
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
        $excluded_roles = ['non_mahasiswa', 'del_mahasiswa', 'admin', 'supervisor_akd', 'supervisor_sd', 'manajer', 'wd1', 'wd2'];
        $user_ids = DB::table('users')
            ->whereNotIn('role', $excluded_roles)
            ->pluck('id')
            ->toArray();

        $alasan_acak = ['sakit', 'berpegian', 'menjenguk', 'acara keluarga', 'urusan pribadi'];
        $list_role = ['mahasiswa', 'admin', 'supervisor_akd', 'manajer', 'wd1'];

        foreach (range(1, 95) as $index) {

            $random_user_id = $faker_srt_masih_mhw->randomElement($user_ids);
            $user = DB::table('users')->where('id', $random_user_id)->first();
            // $nama_mhw = $user->nama;
            $thn_awl = $faker_srt_masih_mhw->numberBetween(2023, 2024);
            $thn_akh = $thn_awl + 1;
            $semester = $faker_srt_masih_mhw->numberBetween(3, 13);
            $tujuan_buat_srt = $faker_srt_masih_mhw->randomElement($alasan_acak);
            $role_surat = $faker_srt_masih_mhw->randomElement($list_role);
            $tanggal_surat = Carbon::create(
                rand(2023, 2024),
                rand(1, 12),
                rand(1, 28)
            )->toDateString();

            $id = mt_rand(1000000000000, 9999999999999);
            DB::table('srt_masih_mhw')->insert([
                'id' => $id,
                'users_id' => $random_user_id,
                // 'nama_mhw' => $nama_mhw,
                'prd_id' => $user->prd_id,
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
        $excluded_roles = ['non_mahasiswa', 'del_mahasiswa', 'admin', 'supervisor_akd', 'supervisor_sd', 'manajer', 'wd1', 'wd2'];
        $user_ids = DB::table('users')
            ->whereNotIn('role', $excluded_roles)
            ->pluck('id')
            ->toArray();

        foreach (range(1, 50) as $index) {
            $id = mt_rand(1000000000000, 9999999999999);
            $random_user_id = $faker_srt_magang->randomElement($user_ids);
            $user = DB::table('users')->where('id', $random_user_id)->first();
            // $nama_mhw = $user->nama;
            $tanggal_surat = Carbon::create(
                rand(2023, 2024),
                rand(1, 12),
                rand(1, 28)
            )->toDateString();
            $ipk = round($faker_srt_magang->randomFloat(2, 3.00, 3.99), 2);
            $sksk = $faker_srt_magang->numberBetween(120, 140);
            $semester = $faker_srt_magang->numberBetween(3, 13);
            $jbt_lmbg = $faker_srt_magang->jobTitle();

            DB::table('srt_magang')->insert([
                'id' => $id,
                'users_id' => $random_user_id,
                // 'nama_mhw' => $nama_mhw,
                'prd_id' => $user->prd_id,
                'ipk' => $ipk,
                'sksk' => $sksk,
                'jbt_lmbg' => $jbt_lmbg,
                'role_surat' => $faker_srt_magang->randomElement(['mahasiswa', 'admin', 'supervisor_akd', 'manajer', 'wd1']),
                'nama_lmbg' => $faker_srt_magang->company(),
                'kota_lmbg' => $faker_srt_magang->city(),
                'almt_lmbg' => $faker_srt_magang->address(),
                'tanggal_surat' => $tanggal_surat,
                'semester' => $semester,
                'almt_smg' => $faker_srt_magang->address(),
            ]);
        }

        $faker_srt_plt = Faker::create('id_ID');
        $excluded_roles = ['non_mahasiswa', 'del_mahasiswa', 'admin', 'supervisor_akd', 'supervisor_sd', 'manajer', 'wd1', 'wd2'];
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

        foreach (range(1, 50) as $index) {
            $id = mt_rand(1000000000000, 9999999999999);
            $random_user_id = $faker_srt_plt->randomElement($user_ids);
            $user = DB::table('users')->where('id', $random_user_id)->first();
            // $nama_mhw = $user->nama;
            $tanggal_surat = Carbon::create(
                rand(2023, 2024),
                rand(1, 12),
                rand(1, 28)
            )->toDateString();
            $judul_data = $faker_srt_plt->randomElement($judul);
            $jenis_surat = $faker_srt_plt->randomElement($jenis);
            $lampiran = $faker_srt_plt->randomElement(['1 Eksemplar', '2 Eksemplar']);
            $semester = $faker_srt_plt->numberBetween(3, 13);
            $jbt_lmbg = $faker_srt_plt->jobTitle();

            DB::table('srt_izin_plt')->insert([
                'id' => $id,
                'users_id' => $random_user_id,
                // 'nama_mhw' => $nama_mhw,
                'prd_id' => $user->prd_id,
                'judul_data' => $judul_data,
                'lampiran' => $lampiran,
                'jbt_lmbg' => $jbt_lmbg,
                'jenis_surat' => $jenis_surat,
                'role_surat' => $faker_srt_plt->randomElement(['mahasiswa', 'admin', 'supervisor_akd', 'manajer', 'wd1']),
                'nama_lmbg' => $faker_srt_plt->company(),
                'kota_lmbg' => $faker_srt_plt->city(),
                'almt_lmbg' => $faker_srt_plt->address(),
                'tanggal_surat' => $tanggal_surat,
                'semester' => $semester,
            ]);
        }

        $faker_srt_pmhn_kmbali_biaya = Faker::create('id_ID');
        $excluded_roles = ['non_mahasiswa', 'del_mahasiswa', 'admin', 'supervisor_akd', 'supervisor_sd', 'manajer', 'wd1', 'wd2'];
        $user_ids = DB::table('users')
            ->whereNotIn('role', $excluded_roles)
            ->pluck('id')
            ->toArray();

        foreach (range(5, 25) as $index) {
            $id = mt_rand(1000000000000, 9999999999999);
            $random_user_id = $faker_srt_pmhn_kmbali_biaya->randomElement($user_ids);
            $user = DB::table('users')->where('id', $random_user_id)->first();
            // $nama_mhw = $user->nama;
            $tanggal_surat = Carbon::create(
                rand(2023, 2024),
                rand(1, 12),
                rand(1, 28)
            )->toDateString();

            DB::table('srt_pmhn_kmbali_biaya')->insert([
                'id' => $id,
                'users_id' => $random_user_id,
                // 'nama_mhw' => $nama_mhw,
                'prd_id' => $user->prd_id,
                'role_surat' => $faker_srt_pmhn_kmbali_biaya->randomElement(['mahasiswa', 'admin', 'supervisor_sd', 'manajer', 'wd2']),
                'tanggal_surat' => $tanggal_surat,
            ]);
        }

        $faker_srt_bbs_pnjm = Faker::create('id_ID');
        $excluded_roles = ['non_mahasiswa', 'del_mahasiswa', 'admin', 'supervisor_akd', 'supervisor_sd', 'manajer', 'wd1', 'wd2'];
        $user_ids = DB::table('users')
            ->whereNotIn('role', $excluded_roles)
            ->pluck('id')
            ->toArray();

        foreach (range(1, 50) as $index) {
            $id = mt_rand(1000000000000, 9999999999999);
            $random_user_id = $faker_srt_bbs_pnjm->randomElement($user_ids);
            $user = DB::table('users')->where('id', $random_user_id)->first();
            // $nama_mhw = $user->nama;
            $tanggal_surat = Carbon::create(
                rand(2023, 2024),
                rand(1, 12),
                rand(1, 28)
            )->toDateString();
            $almt_smg = $faker_srt_bbs_pnjm->address();
            $dosen_wali = $faker_srt_bbs_pnjm->name();

            DB::table('srt_bbs_pnjm')->insert([
                'id' => $id,
                'users_id' => $random_user_id,
                // 'nama_mhw' => $nama_mhw,
                'almt_smg' => $almt_smg,
                'dosen_wali' => $dosen_wali,
                'prd_id' => $user->prd_id,
                'role_surat' => $faker_srt_bbs_pnjm->randomElement(['mahasiswa', 'admin', 'supervisor_sd']),
                'tanggal_surat' => $tanggal_surat,
            ]);
        }

        $faker_lgl = Faker::create('id_ID');
        $excluded_roles = ['non_mahasiswa', 'del_mahasiswa', 'admin', 'supervisor_akd', 'supervisor_sd', 'manajer', 'wd1', 'wd2'];
        $user_ids = DB::table('users')
            ->whereNotIn('role', $excluded_roles)
            ->pluck('id')
            ->toArray();

        $urusan = ['Bekerja di luar negeri', 'Melamar Kerja', 'Melamar Istri', 'Ambil pendidikan tinggi di LN'];
        $jenis_legalisir = ['ijazah', 'transkrip', 'ijazah_transkrip'];


        foreach (range(1, 95) as $index) {
            $id = mt_rand(1000000000000, 9999999999999);
            $random_user_id = $faker_lgl->randomElement($user_ids);
            $user = DB::table('users')->where('id', $random_user_id)->first();
            // $nama_mhw = $user->nama;
            $tanggal_surat = Carbon::now('Asia/Jakarta')->toDateString();
            $keperluan = $faker_lgl->randomElement($urusan);
            $tanggal_lulus = $faker_lgl->date($format = 'Y-m-d', $max = '2024-08-01', $min = '2019-01-01',);

            DB::table('legalisir')->insert([
                'id' => $id,
                'users_id' => $random_user_id,
                // 'nama_mhw' => $nama_mhw,
                'tgl_lulus' => $tanggal_lulus,
                'keperluan' => $keperluan,
                'jenis_lgl' => $faker_lgl->randomElement($jenis_legalisir),
                'ambil' => $faker_lgl->randomElement(['ditempat', 'dikirim']),
                'role_surat' => $faker_lgl->randomElement(['admin', 'supervisor_akd', 'dekan']),
                'prd_id' => $user->prd_id,
                'tanggal_surat' => $tanggal_surat,
            ]);
        }
    }
}
