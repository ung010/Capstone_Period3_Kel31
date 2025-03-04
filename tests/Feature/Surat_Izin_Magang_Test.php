<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;
use Hashids\Hashids;

class Surat_Izin_Magang_Test extends TestCase
{
    // Belum WD, Belum pengecekan seluruh aktor

    use DatabaseTransactions;
    public function test_halaman_surat(): void
    {
        $response = $this->get('/srt_magang');

        $response->assertStatus(302);
    }

    public function test_buat_surat(): void
    {
        $this->withoutExceptionHandling();

        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'mahasiswa@gmail.com',
            'password' => bcrypt('password'),
            'prd_id' => 1,
        ]);

        $this->actingAs($user);

        $jobTitle = $faker->jobTitle();

        $shortJobTitle = explode(' ', $jobTitle)[0];

        $response = $this->post('/srt_magang/create', [
            'ipk' => '3.50',
            'sksk' => 150,
            'semester' => $faker->randomDigitNotNull,
            'almt_smg' => $faker->address(),
            'almt_lmbg' => $faker->address(),
            'nama_lmbg' => $faker->company(),
            'jbt_lmbg' => $shortJobTitle,
            'kota_lmbg' => $faker->city(),
            'tanggal_surat' => $faker->date('Y-m-d'),
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/srt_magang');
    }

    public function test_gagal_buat_surat_magang_baru_karena_data_kurang(): void
    {
        $this->withoutExceptionHandling();

        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'mahasiswa@gmail.com',
            'password' => bcrypt('password'),
            'prd_id' => 1,
        ]);

        $this->actingAs($user);

        $jobTitle = $faker->jobTitle();

        $shortJobTitle = explode(' ', $jobTitle)[0];

        try {
            $response = $this->post('/srt_magang/create', [
                'ipk' => '3.50',
                'sksk' => 150,
                'semester' => $faker->randomDigitNotNull,
                'almt_smg' => $faker->address(),
                'almt_lmbg' => $faker->address(),
                'nama_lmbg' => $faker->company(),
                'jbt_lmbg' => $shortJobTitle,
                'tanggal_surat' => $faker->date('Y-m-d'),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertEquals('Kota perusahaan / lembaga wajib diisi', $e->validator->errors()->first('kota_lmbg'));
            return;
        }
        $response->assertStatus(302);
    }

    public function test_view_halaman_edit_surat(): void
    {
        $this->withoutExceptionHandling();

        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'mahasiswa@gmail.com',
            'password' => bcrypt('mountain082'),
            'role' => 'mahasiswa',
            'prd_id' => 1,
        ]);

        $jobTitle = $faker->jobTitle();

        $shortJobTitle = explode(' ', $jobTitle)[0];

        $this->actingAs($user);
        $hashids = new Hashids('nilai-salt-unik-anda-di-sini', 7);
        $surat = DB::table('srt_magang')->insertGetId([
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'ipk' => '3.50',
            'sksk' => 150,
            'semester' => $faker->randomDigitNotNull,
            'almt_smg' => $faker->address(),
            'almt_lmbg' => $faker->address(),
            'nama_lmbg' => $faker->company(),
            'jbt_lmbg' => $shortJobTitle,
            'kota_lmbg' => $faker->city(),
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);


        $encodedId = $hashids->encode($surat);
        $response = $this->get("/srt_magang/edit/{$encodedId}");

        $response->assertStatus(200);
    }

    public function test_update_surat_magang(): void
    {
        $this->withoutExceptionHandling();

        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'mahasiswa@gmail.com',
            'password' => bcrypt('mountain082'),
            'role' => 'mahasiswa',
            'prd_id' => 1,
            'nama' => 'Raung Calon Sarjana',
        ]);
        $this->actingAs($user);

        $jobTitle = $faker->jobTitle();

        $shortJobTitle = explode(' ', $jobTitle)[0];

        $surat = DB::table('srt_magang')->insertGetId([
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'ipk' => '3.50',
            'sksk' => 150,
            'semester' => $faker->randomDigitNotNull,
            'almt_smg' => $faker->address(),
            'almt_lmbg' => $faker->address(),
            'nama_lmbg' => $faker->company(),
            'jbt_lmbg' => $shortJobTitle,
            'kota_lmbg' => $faker->city(),
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->post("/srt_magang/update/{$surat}", [
            'ipk' => '3.30',
            'sksk' => 130,
            'semester' => $faker->randomDigitNotNull,
            'almt_smg' => $faker->address(),
            'almt_lmbg' => $faker->address(),
            'nama_lmbg' => $faker->company(),
            'jbt_lmbg' => $shortJobTitle,
            'kota_lmbg' => $faker->city(),
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/srt_magang');

        $this->assertDatabaseHas('srt_magang', [
            'id' => $surat,
            'ipk' => '3.30',
            'sksk' => 130,
        ]);
    }

    public function test_view_halaman_surat_admin(): void
    {
        $response = $this->get('/srt_magang/admin');

        $response->assertStatus(302);
    }
    public function test_download_surat_mahasiswa()
    {
        $id = 6;

        $response = $this->get("/srt_magang/download/{$id}");

        $response->assertStatus(302);
    }

    public function test_cek_surat_oleh_admin()
    {
        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($user);

        $jobTitle = $faker->jobTitle();

        $shortJobTitle = explode(' ', $jobTitle)[0];

        $suratId = DB::table('srt_magang')->insertGetId([
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'ipk' => '3.50',
            'sksk' => 150,
            'semester' => $faker->randomDigitNotNull,
            'almt_smg' => $faker->address(),
            'almt_lmbg' => $faker->address(),
            'nama_lmbg' => $faker->company(),
            'jbt_lmbg' => $shortJobTitle,
            'kota_lmbg' => $faker->city(),
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->get("/srt_magang/admin/cek_surat/{$suratId}");

        $response->assertStatus(200);
    }

    public function test_setuju_surat_oleh_admin()
    {
        $admin = \App\Models\User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        $srtMhwAsn = \App\Models\srt_magang::factory()->create([
            'no_surat' => null,
            'role_surat' => 'admin',
        ]);

        $response = $this->post("/srt_magang/admin/cek_surat/setuju/{$srtMhwAsn->id}", [
            'no_surat' => '123456789',
        ]);

        $response->assertRedirect(route('srt_magang.admin'));
        $response->assertSessionHas('success', 'No surat berhasil ditambahkan');

        $this->assertDatabaseHas('srt_magang', [
            'id' => $srtMhwAsn->id,
            'no_surat' => '123456789',
            'role_surat' => 'supervisor_akd',
        ]);
    }

    public function test_tolak_surat_oleh_admin()
    {
        $admin = \App\Models\User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin);


        $srtMhwAsn = \App\Models\srt_magang::factory()->create([
            'catatan_surat' => null,
            'role_surat' => 'admin',
        ]);

        $response = $this->post("/srt_magang/admin/cek_surat/tolak/{$srtMhwAsn->id}", [
            'catatan_surat' => 'Dokumen tidak lengkap',
        ]);

        $response->assertRedirect(route('srt_magang.admin'));
        $response->assertSessionHas('success', 'Alasan penolakan telah dikirimkan');

        $this->assertDatabaseHas('srt_magang', [
            'id' => $srtMhwAsn->id,
            'catatan_surat' => 'Dokumen tidak lengkap',
            'role_surat' => 'tolak',
        ]);
    }

    public function test_view_halaman_surat_supervisor(): void
    {
        $response = $this->get('/srt_magang/supervisor');

        $response->assertStatus(302);
    }

    public function test_tolak_surat_oleh_supervisor()
    {
        $admin = \App\Models\User::factory()->create([
            'email' => 'akd@example.com',
            'password' => bcrypt('password'),
            'role' => 'supervisor_akd',
        ]);

        $this->actingAs($admin);


        $srtMhwAsn = \App\Models\srt_magang::factory()->create([
            'catatan_surat' => null,
            'role_surat' => 'supervisor_akd',
        ]);

        $response = $this->post("/srt_magang/supervisor/cek_surat/tolak/{$srtMhwAsn->id}", [
            'catatan_surat' => 'Dokumen tidak lengkap',
        ]);

        $response->assertRedirect(route('srt_magang.supervisor'));
        $response->assertSessionHas('success', 'Alasan penolakan telah dikirimkan');

        $this->assertDatabaseHas('srt_magang', [
            'id' => $srtMhwAsn->id,
            'catatan_surat' => 'Dokumen tidak lengkap',
            'role_surat' => 'tolak',
        ]);
    }

    public function test_cek_surat_oleh_supervisor()
    {
        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'akd@example.com',
            'password' => bcrypt('password'),
            'role' => 'supervisor_akd',
        ]);

        $this->actingAs($user);

        $jobTitle = $faker->jobTitle();

        $shortJobTitle = explode(' ', $jobTitle)[0];

        $suratId = DB::table('srt_magang')->insertGetId([
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'ipk' => '3.50',
            'sksk' => 150,
            'semester' => $faker->randomDigitNotNull,
            'almt_smg' => $faker->address(),
            'almt_lmbg' => $faker->address(),
            'nama_lmbg' => $faker->company(),
            'jbt_lmbg' => $shortJobTitle,
            'kota_lmbg' => $faker->city(),
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->get("/srt_magang/supervisor/cek_surat/{$suratId}");

        $response->assertStatus(200);
    }

    public function test_setuju_surat_oleh_supervisor()
    {
        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'supervisor@example.com',
            'password' => bcrypt('password'),
            'role' => 'supervisor_akd',
        ]);

        $this->actingAs($user);

        $jobTitle = $faker->jobTitle();

        $shortJobTitle = explode(' ', $jobTitle)[0];

        $surat = \App\Models\srt_magang::factory()->create([
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'ipk' => '3.50',
            'sksk' => 150,
            'semester' => $faker->randomDigitNotNull,
            'almt_smg' => $faker->address(),
            'almt_lmbg' => $faker->address(),
            'nama_lmbg' => $faker->company(),
            'jbt_lmbg' => $shortJobTitle,
            'kota_lmbg' => $faker->city(),
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->post("/srt_magang/supervisor/cek_surat/setuju/{$surat->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Surat berhasil disetujui');

        $this->assertDatabaseHas('srt_magang', [
            'id' => $surat->id,
            'role_surat' => 'manajer',
        ]);
    }

    public function test_halaman_surat_oleh_manajer(): void
    {
        $response = $this->get('/srt_magang/manajer');

        $response->assertStatus(302);
    }

    public function test_tolak_surat_oleh_manajer()
    {
        $admin = \App\Models\User::factory()->create([
            'email' => 'akd@example.com',
            'password' => bcrypt('password'),
            'role' => 'manajer',
        ]);

        $this->actingAs($admin);


        $srtMhwAsn = \App\Models\srt_magang::factory()->create([
            'catatan_surat' => null,
            'role_surat' => 'manajer',
        ]);

        $response = $this->post("/srt_magang/manajer/cek_surat/tolak/{$srtMhwAsn->id}", [
            'catatan_surat' => 'Dokumen tidak lengkap',
        ]);

        $response->assertRedirect(route('srt_magang.manajer'));
        $response->assertSessionHas('success', 'Alasan penolakan telah dikirimkan');

        $this->assertDatabaseHas('srt_magang', [
            'id' => $srtMhwAsn->id,
            'catatan_surat' => 'Dokumen tidak lengkap - Manajer',
            'role_surat' => 'tolak',
        ]);
    }

    public function test_cek_surat_oleh_manajer()
    {
        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'manajer@example.com',
            'password' => bcrypt('password'),
            'role' => 'manajer',
        ]);

        $this->actingAs($user);

        $jobTitle = $faker->jobTitle();

        $shortJobTitle = explode(' ', $jobTitle)[0];

        $suratId = DB::table('srt_magang')->insertGetId([
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'ipk' => '3.50',
            'sksk' => 150,
            'semester' => $faker->randomDigitNotNull,
            'almt_smg' => $faker->address(),
            'almt_lmbg' => $faker->address(),
            'nama_lmbg' => $faker->company(),
            'jbt_lmbg' => $shortJobTitle,
            'kota_lmbg' => $faker->city(),
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->get("/srt_magang/manajer/cek_surat/{$suratId}");

        $response->assertStatus(200);
    }

    public function test_setuju_surat_oleh_manajer()
    {
        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'manajer@example.com',
            'password' => bcrypt('password'),
            'role' => 'manajer',
        ]);

        $this->actingAs($user);

        $jobTitle = $faker->jobTitle();

        $shortJobTitle = explode(' ', $jobTitle)[0];

        $surat = \App\Models\srt_magang::factory()->create([
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'ipk' => '3.50',
            'sksk' => 150,
            'semester' => $faker->randomDigitNotNull,
            'almt_smg' => $faker->address(),
            'almt_lmbg' => $faker->address(),
            'nama_lmbg' => $faker->company(),
            'jbt_lmbg' => $shortJobTitle,
            'kota_lmbg' => $faker->city(),
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->post("/srt_magang/manajer/cek_surat/setuju/{$surat->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Surat berhasil disetujui');

        $this->assertDatabaseHas('srt_magang', [
            'id' => $surat->id,
            'role_surat' => 'wd1',
        ]);
    }

    public function test_halaman_surat_oleh_wd1(): void
    {
        $response = $this->get('/srt_magang/wd1');

        $response->assertStatus(302);
    }

    public function test_tolak_surat_oleh_wd1()
    {
        $admin = \App\Models\User::factory()->create([
            'email' => 'wd1@example.com',
            'password' => bcrypt('password'),
            'role' => 'wd1',
        ]);

        $this->actingAs($admin);


        $srtMhwAsn = \App\Models\srt_magang::factory()->create([
            'catatan_surat' => null,
            'role_surat' => 'wd1',
        ]);

        $response = $this->post("/srt_magang/wd1/cek_surat/tolak/{$srtMhwAsn->id}", [
            'catatan_surat' => 'Dokumen tidak lengkap',
        ]);

        $response->assertRedirect(route('srt_magang.wd1'));
        $response->assertSessionHas('success', 'Alasan penolakan telah dikirimkan');

        $this->assertDatabaseHas('srt_magang', [
            'id' => $srtMhwAsn->id,
            'catatan_surat' => 'Dokumen tidak lengkap',
            'role_surat' => 'tolak',
        ]);
    }

    public function test_cek_surat_oleh_wd1()
    {
        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'wd1@example.com',
            'password' => bcrypt('password'),
            'role' => 'wd1',
        ]);

        $this->actingAs($user);

        $jobTitle = $faker->jobTitle();

        $shortJobTitle = explode(' ', $jobTitle)[0];

        $suratId = DB::table('srt_magang')->insertGetId([
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'ipk' => '3.50',
            'sksk' => 150,
            'semester' => $faker->randomDigitNotNull,
            'almt_smg' => $faker->address(),
            'almt_lmbg' => $faker->address(),
            'nama_lmbg' => $faker->company(),
            'jbt_lmbg' => $shortJobTitle,
            'kota_lmbg' => $faker->city(),
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->get("/srt_magang/wd1/cek_surat/{$suratId}");

        $response->assertStatus(200);
    }

    public function test_setuju_surat_oleh_wd1()
    {
        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'manajer@example.com',
            'password' => bcrypt('password'),
            'role' => 'wd1',
        ]);

        $this->actingAs($user);

        $jobTitle = $faker->jobTitle();

        $shortJobTitle = explode(' ', $jobTitle)[0];

        $surat = \App\Models\srt_magang::factory()->create([
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'ipk' => '3.50',
            'sksk' => 150,
            'semester' => $faker->randomDigitNotNull,
            'almt_smg' => $faker->address(),
            'almt_lmbg' => $faker->address(),
            'nama_lmbg' => $faker->company(),
            'jbt_lmbg' => $shortJobTitle,
            'kota_lmbg' => $faker->city(),
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->post("/srt_magang/wd1/cek_surat/setuju/{$surat->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Surat berhasil disetujui');

        $this->assertDatabaseHas('srt_magang', [
            'id' => $surat->id,
            'role_surat' => 'mahasiswa',
        ]);
    }

    // Belum WD, Belum pengecekan seluruh aktor
}
