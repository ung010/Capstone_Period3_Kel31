<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;
use Hashids\Hashids;

class Surat_Masih_Mahasiswa_Test extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     */
    public function test_halaman_surat_masih_mhw(): void
    {
        $response = $this->get('/srt_masih_mhw');

        $response->assertStatus(302);
    }

    public function test_buat_surat_masih_mhw_untuk_manajer(): void
    {
        $this->withoutExceptionHandling();

        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'mahasiswa@gmail.com',
            'password' => bcrypt('password'),
            'prd_id' => 1,
        ]);

        $this->actingAs($user);

        $response = $this->post('/srt_masih_mhw/create', [
            'thn_awl' => 2020,
            'thn_akh' => 2024,
            'semester' => 6,
            'almt_smg' => $faker->address(),
            'tujuan_buat_srt' => $faker->sentence(),
            'tujuan_akhir' => 'manajer',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/srt_masih_mhw');
    }

    public function test_buat_surat_masih_mhw_untuk_wd(): void
    {
        $this->withoutExceptionHandling();

        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'mahasiswa@gmail.com',
            'password' => bcrypt('password'),
            'prd_id' => 1,
        ]);

        $this->actingAs($user);
        $response = $this->post('/srt_masih_mhw/create', [
            'thn_awl' => 2020,
            'thn_akh' => 2024,
            'semester' => 6,
            'almt_smg' => $faker->address(),
            'tujuan_buat_srt' => $faker->sentence(),
            'tujuan_akhir' => 'wd',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/srt_masih_mhw');
    }

    public function test_gagal_buat_surat_masih_mhw_baru_karena_data_kurang(): void
    {
        $this->withoutExceptionHandling();

        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'mahasiswa@gmail.com',
            'password' => bcrypt('password'),
            'prd_id' => 1,
        ]);

        $this->actingAs($user);

        try {
            $response = $this->post('/srt_masih_mhw/create', [
                'thn_awl' => 2020,
                'thn_akh' => 2024,
                'semester' => 6,
                'almt_smg' => $faker->address(),
                'tujuan_akhir' => 'wd',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertEquals('Tujuan pembuatan surat wajib diisi', $e->validator->errors()->first('tujuan_buat_srt'));
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

        $this->actingAs($user);
        $hashids = new Hashids('nilai-salt-unik-anda-di-sini', 7);
        $surat = DB::table('srt_masih_mhw')->insertGetId([
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'thn_awl' => 2020,
            'thn_akh' => 2024,
            'semester' => 6,
            'almt_smg' => $faker->address(),
            'tujuan_buat_srt' => $faker->sentence(),
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $encodedId = $hashids->encode($surat);
        $response = $this->get("/srt_masih_mhw/edit/{$encodedId}");

        $response->assertStatus(200);
    }

    public function test_update_surat_masih_mhw(): void
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

        $surat = DB::table('srt_masih_mhw')->insertGetId([
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'thn_awl' => 2020,
            'thn_akh' => 2024,
            'semester' => 6,
            'almt_smg' => $faker->address(),
            'tujuan_buat_srt' => $faker->sentence(),
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->post("/srt_masih_mhw/update/{$surat}", [
            'thn_awl' => 2020,
            'thn_akh' => 2021,
            'semester' => 3,
            'almt_smg' => $faker->address(),
            'tujuan_buat_srt' => $faker->sentence(),
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/srt_masih_mhw');

        $this->assertDatabaseHas('srt_masih_mhw', [
            'id' => $surat,
            'thn_awl' => 2020,
            'thn_akh' => 2021,
            'semester' => 3,
        ]);
    }

    public function test_halaman_surat_masih_mhw_manajer_oleh_admin(): void
    {
        $response = $this->get('/srt_masih_mhw/admin/manajer');

        $response->assertStatus(302);
    }

    public function test_setuju_surat_masih_mhw_manajer_oleh_admin()
    {
        $admin = \App\Models\User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        $srtMhwAsn = \App\Models\srt_masih_mhw::factory()->create([
            'no_surat' => null,
            'role_surat' => 'admin',
        ]);

        $response = $this->post("/srt_masih_mhw/admin/manajer/cek_surat/setuju/{$srtMhwAsn->id}", [
            'no_surat' => '123456789',
        ]);

        $response->assertRedirect(route('srt_masih_mhw.admin'));
        $response->assertSessionHas('success', 'No surat berhasil ditambahkan');

        $this->assertDatabaseHas('srt_masih_mhw', [
            'id' => $srtMhwAsn->id,
            'no_surat' => '123456789',
            'role_surat' => 'supervisor_akd',
        ]);
    }

    public function test_cek_surat_masih_mhw_manajer_oleh_admin()
    {
        $admin = \App\Models\User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        $suratId = DB::table('srt_masih_mhw')->insertGetId([
            'users_id' => $admin->id,
            'thn_awl' => 2020,
            'thn_akh' => 2024,
            'semester' => 6,
            'tujuan_buat_srt' => 'Berkeluarga',
            'almt_smg' => 'Semarang',
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
            'prd_id' => 1,
        ]);

        $response = $this->get("/srt_masih_mhw/admin/manajer/cek_surat/{$suratId}");

        $response->assertStatus(200);
    }

    public function test_tolak_surat_masih_mhw_manajer_oleh_admin()
    {
        $admin = \App\Models\User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin);


        $srtMhwAsn = \App\Models\srt_masih_mhw::factory()->create([
            'catatan_surat' => null,
            'role_surat' => 'admin',
        ]);

        $response = $this->post("/srt_masih_mhw/admin/manajer/cek_surat/tolak/{$srtMhwAsn->id}", [
            'catatan_surat' => 'Dokumen tidak lengkap',
        ]);

        $response->assertRedirect(route('srt_masih_mhw.admin'));
        $response->assertSessionHas('success', 'Alasan penolakan telah dikirimkan');

        $this->assertDatabaseHas('srt_masih_mhw', [
            'id' => $srtMhwAsn->id,
            'catatan_surat' => 'Dokumen tidak lengkap',
            'role_surat' => 'tolak',
        ]);
    }

    public function test_halaman_surat_masih_mhw_wd_oleh_admin(): void
    {
        $response = $this->get('/srt_masih_mhw/admin/wd');

        $response->assertStatus(302);
    }

    public function test_cek_srt_masih_mhw_wd_oleh_admin()
    {
        $admin = \App\Models\User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        $suratId = DB::table('srt_masih_mhw')->insertGetId([
            'users_id' => $admin->id,
            'thn_awl' => 2020,
            'thn_akh' => 2024,
            'semester' => 6,
            'tujuan_buat_srt' => 'Berkeluarga',
            'almt_smg' => 'Semarang',
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
            'prd_id' => 1,
        ]);

        $response = $this->get("/srt_masih_mhw/admin/wd/cek_surat/{$suratId}");

        $response->assertStatus(200);
    }

    public function test_setuju_surat_masih_mhw_wd_oleh_admin()
    {
        $admin = \App\Models\User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        $srtMhwAsn = \App\Models\srt_masih_mhw::factory()->create([
            'no_surat' => null,
            'role_surat' => 'admin',
        ]);

        $response = $this->post("/srt_masih_mhw/admin/wd/cek_surat/setuju/{$srtMhwAsn->id}", [
            'no_surat' => '123456789',
        ]);

        $response->assertRedirect(route('srt_masih_mhw.wd'));
        $response->assertSessionHas('success', 'No surat berhasil ditambahkan');

        $this->assertDatabaseHas('srt_masih_mhw', [
            'id' => $srtMhwAsn->id,
            'no_surat' => '123456789',
            'role_surat' => 'supervisor_akd',
        ]);
    }

    public function test_tolak_surat_masih_mhw_wd_oleh_admin()
    {
        $admin = \App\Models\User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin);


        $srtMhwAsn = \App\Models\srt_masih_mhw::factory()->create([
            'catatan_surat' => null,
            'role_surat' => 'admin',
        ]);

        $response = $this->post("/srt_masih_mhw/admin/wd/cek_surat/tolak/{$srtMhwAsn->id}", [
            'catatan_surat' => 'Dokumen tidak lengkap',
        ]);

        $response->assertRedirect(route('srt_masih_mhw.wd'));
        $response->assertSessionHas('success', 'Alasan penolakan telah dikirimkan');

        $this->assertDatabaseHas('srt_masih_mhw', [
            'id' => $srtMhwAsn->id,
            'catatan_surat' => 'Dokumen tidak lengkap',
            'role_surat' => 'tolak',
        ]);
    }

    public function test_download_surat_untuk_manajer()
    {
        $id = 4;

        $response = $this->get("/srt_masih_mhw/manajer/download/{$id}");

        $response->assertStatus(302);
    }

    public function test_download_surat_untuk_wd()
    {
        $id = 6;

        $response = $this->get("/srt_masih_mhw/wd/download/{$id}");

        $response->assertStatus(302);
    }

    public function test_view_halaman_surat_masih_mhw_oleh_supervisor(): void
    {
        $response = $this->get('/srt_masih_mhw/supervisor');

        $response->assertStatus(302);
    }

    public function test_cek_surat_masih_mhw_oleh_supervisor()
    {
        $sv = \App\Models\User::factory()->create([
            'email' => 'akd@example.com',
            'password' => bcrypt('password'),
            'role' => 'supervisor_akd',
        ]);

        $this->actingAs($sv);

        $suratId = DB::table('srt_masih_mhw')->insertGetId([
            'users_id' => $sv->id,
            'thn_awl' => 2020,
            'thn_akh' => 2024,
            'semester' => 6,
            'tujuan_buat_srt' => 'Berkeluarga',
            'almt_smg' => 'Semarang',
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
            'prd_id' => 1,
        ]);

        $response = $this->get("/srt_masih_mhw/supervisor/cek_surat/{$suratId}");

        $response->assertStatus(200);
    }

    public function test_tolak_surat_masih_mhw_manajer_oleh_supervisor()
    {
        $sv = \App\Models\User::factory()->create([
            'email' => 'sv@example.com',
            'password' => bcrypt('password'),
            'role' => 'supervisor_akd',
        ]);

        $this->actingAs($sv);


        $srtMhwAsn = \App\Models\srt_masih_mhw::factory()->create([
            'catatan_surat' => null,
            'role_surat' => 'supervisor_akd',
        ]);

        $response = $this->post("/srt_masih_mhw/supervisor/cek_surat/tolak/{$srtMhwAsn->id}", [
            'catatan_surat' => 'Dokumen tidak lengkap',
        ]);

        $response->assertRedirect(route('srt_masih_mhw.supervisor'));
        $response->assertSessionHas('success', 'Alasan penolakan telah dikirimkan');

        $this->assertDatabaseHas('srt_masih_mhw', [
            'id' => $srtMhwAsn->id,
            'catatan_surat' => 'Dokumen tidak lengkap',
            'role_surat' => 'tolak',
        ]);
    }

    public function test_supervisor_setuju_srt_masih_mhw()
    {
        $supervisor = \App\Models\User::factory()->create([
            'email' => 'supervisor@example.com',
            'password' => bcrypt('password'),
            'role' => 'supervisor_akd',
        ]);

        $this->actingAs($supervisor);

        $surat = \App\Models\srt_masih_mhw::factory()->create([
            'users_id' => $supervisor->id,
            'prd_id' => 1,
            'thn_awl' => 2020,
            'thn_akh' => 2024,
            'semester' => 6,
            'almt_smg' => 'Alamat Semarang',
            'tujuan_buat_srt' => 'Untuk keperluan studi lanjut',
            'role_surat' => 'supervisor_akd',
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->post("/srt_masih_mhw/supervisor/cek_surat/setuju/{$surat->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Surat berhasil disetujui');

        $this->assertDatabaseHas('srt_masih_mhw', [
            'id' => $surat->id,
            'role_surat' => 'manajer',
        ]);
    }

    public function test_halaman_surat_masih_mhw_oleh_manajer(): void
    {
        $response = $this->get('/srt_masih_mhw/manajer');

        $response->assertStatus(302);
    }

    public function test_cek_surat_masih_mhw_untuk_manajer_oleh_manajer()
    {
        $manajer = \App\Models\User::factory()->create([
            'email' => 'manajer@example.com',
            'password' => bcrypt('password'),
            'role' => 'manajer',
        ]);

        $this->actingAs($manajer);

        $suratId = DB::table('srt_masih_mhw')->insertGetId([
            'users_id' => $manajer->id,
            'thn_awl' => 2020,
            'thn_akh' => 2024,
            'semester' => 6,
            'tujuan_akhir' => 'manajer',
            'tujuan_buat_srt' => 'Berkeluarga',
            'almt_smg' => 'Semarang',
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
            'prd_id' => 1,
        ]);

        $response = $this->get("/srt_masih_mhw/manajer/cek_surat/{$suratId}");

        $response->assertStatus(200);
    }

    public function test_tolak_surat_masih_mhw_untuk_manajer_oleh_manajer()
    {
        $manajer = \App\Models\User::factory()->create([
            'email' => 'sv@example.com',
            'password' => bcrypt('password'),
            'role' => 'manajer',
        ]);

        $this->actingAs($manajer);


        $srtMhwAsn = \App\Models\srt_masih_mhw::factory()->create([
            'catatan_surat' => null,
            'role_surat' => 'manajer',
        ]);

        $response = $this->post("/srt_masih_mhw/manajer/cek_surat/tolak/{$srtMhwAsn->id}", [
            'catatan_surat' => 'Dokumen tidak lengkap',
        ]);

        $response->assertRedirect(route('srt_masih_mhw.manajer'));
        $response->assertSessionHas('success', 'Alasan penolakan telah dikirimkan');

        $this->assertDatabaseHas('srt_masih_mhw', [
            'id' => $srtMhwAsn->id,
            'catatan_surat' => 'Dokumen tidak lengkap',
            'role_surat' => 'tolak',
        ]);
    }

    public function test_setuju_surat_masih_mhw_untuk_manajer_oleh_manajer()
    {
        $manajer = \App\Models\User::factory()->create([
            'email' => 'manajer@example.com',
            'password' => bcrypt('password'),
            'role' => 'manajer',
        ]);

        $this->actingAs($manajer);

        $surat = \App\Models\srt_masih_mhw::factory()->create([
            'users_id' => $manajer->id,
            'prd_id' => 1,
            'thn_awl' => 2020,
            'thn_akh' => 2024,
            'semester' => 6,
            'almt_smg' => 'Alamat Semarang',
            'tujuan_buat_srt' => 'Untuk keperluan studi lanjut',
            'tujuan_akhir' => 'manajer',
            'role_surat' => 'manajer',
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->post("/srt_masih_mhw/manajer/cek_surat/setuju/{$surat->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Surat berhasil disetujui');

        $this->assertDatabaseHas('srt_masih_mhw', [
            'id' => $surat->id,
            'role_surat' => 'mahasiswa',
        ]);
    }

    public function test_cek_surat_masih_mhw_untuk_wd_oleh_manajer()
    {
        $manajer = \App\Models\User::factory()->create([
            'email' => 'manajer@example.com',
            'password' => bcrypt('password'),
            'role' => 'manajer',
        ]);

        $this->actingAs($manajer);

        $suratId = DB::table('srt_masih_mhw')->insertGetId([
            'users_id' => $manajer->id,
            'thn_awl' => 2020,
            'thn_akh' => 2024,
            'semester' => 6,
            'tujuan_akhir' => 'wd',
            'tujuan_buat_srt' => 'Berkeluarga',
            'almt_smg' => 'Semarang',
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
            'prd_id' => 1,
        ]);

        $response = $this->get("/srt_masih_mhw/manajer/wd/cek_surat/{$suratId}");

        $response->assertStatus(200);
    }

    public function test_tolak_surat_masih_mhw_untuk_wd_oleh_manajer()
    {
        $manajer = \App\Models\User::factory()->create([
            'email' => 'sv@example.com',
            'password' => bcrypt('password'),
            'role' => 'manajer',
        ]);

        $this->actingAs($manajer);


        $srtMhwAsn = \App\Models\srt_masih_mhw::factory()->create([
            'catatan_surat' => null,
            'role_surat' => 'manajer',
        ]);

        $response = $this->post("/srt_masih_mhw/manajer/wd/cek_surat/tolak/{$srtMhwAsn->id}", [
            'catatan_surat' => 'Dokumen tidak lengkap',
        ]);

        $response->assertRedirect(route('srt_masih_mhw.manajer'));
        $response->assertSessionHas('success', 'Alasan penolakan telah dikirimkan');

        $this->assertDatabaseHas('srt_masih_mhw', [
            'id' => $srtMhwAsn->id,
            'catatan_surat' => 'Dokumen tidak lengkap',
            'role_surat' => 'tolak',
        ]);
    }

    public function test_setuju_surat_masih_mhw_untuk_wd_oleh_manajer()
    {
        $manajer = \App\Models\User::factory()->create([
            'email' => 'manajer@example.com',
            'password' => bcrypt('password'),
            'role' => 'manajer',
        ]);

        $this->actingAs($manajer);

        $surat = \App\Models\srt_masih_mhw::factory()->create([
            'users_id' => $manajer->id,
            'prd_id' => 1,
            'thn_awl' => 2020,
            'thn_akh' => 2024,
            'semester' => 6,
            'almt_smg' => 'Alamat Semarang',
            'tujuan_buat_srt' => 'Untuk keperluan studi lanjut',
            'tujuan_akhir' => 'wd',
            'role_surat' => 'manajer',
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->post("/srt_masih_mhw/manajer/wd/cek_surat/setuju/{$surat->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Surat berhasil disetujui');

        $this->assertDatabaseHas('srt_masih_mhw', [
            'id' => $surat->id,
            'role_surat' => 'wd1',
        ]);
    }

    public function test_halaman_surat_masih_mhw_oleh_wd(): void
    {
        $response = $this->get('/srt_masih_mhw/wd1');

        $response->assertStatus(302);
    }

    public function test_cek_surat_masih_mhw_oleh_wd()
    {
        $wd1 = \App\Models\User::factory()->create([
            'email' => 'wd1@example.com',
            'password' => bcrypt('password'),
            'role' => 'wd1',
        ]);

        $this->actingAs($wd1);

        $suratId = DB::table('srt_masih_mhw')->insertGetId([
            'users_id' => $wd1->id,
            'thn_awl' => 2020,
            'thn_akh' => 2024,
            'semester' => 6,
            'tujuan_akhir' => 'wd',
            'tujuan_buat_srt' => 'Berkeluarga',
            'almt_smg' => 'Semarang',
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
            'prd_id' => 1,
        ]);

        $response = $this->get("/srt_masih_mhw/wd1/cek_surat/{$suratId}");

        $response->assertStatus(200);
    }

    public function test_tolak_surat_masih_mhw_oleh_wd()
    {
        $wd1 = \App\Models\User::factory()->create([
            'email' => 'wd1@example.com',
            'password' => bcrypt('password'),
            'role' => 'wd1',
        ]);

        $this->actingAs($wd1);


        $srtMhwAsn = \App\Models\srt_masih_mhw::factory()->create([
            'catatan_surat' => null,
            'role_surat' => 'wd1',
        ]);

        $response = $this->post("/srt_masih_mhw/wd1/cek_surat/tolak/{$srtMhwAsn->id}", [
            'catatan_surat' => 'Dokumen tidak lengkap',
        ]);

        $response->assertRedirect(route('srt_masih_mhw.wd1'));
        $response->assertSessionHas('success', 'Alasan penolakan telah dikirimkan');

        $this->assertDatabaseHas('srt_masih_mhw', [
            'id' => $srtMhwAsn->id,
            'catatan_surat' => 'Dokumen tidak lengkap',
            'role_surat' => 'tolak',
        ]);
    }

    public function test_setuju_surat_masih_mhw_oleh_wd()
    {
        $wd1 = \App\Models\User::factory()->create([
            'email' => 'wd1@example.com',
            'password' => bcrypt('password'),
            'role' => 'wd1',
        ]);

        $this->actingAs($wd1);

        $surat = \App\Models\srt_masih_mhw::factory()->create([
            'users_id' => $wd1->id,
            'prd_id' => 1,
            'thn_awl' => 2020,
            'thn_akh' => 2024,
            'semester' => 6,
            'almt_smg' => 'Alamat Semarang',
            'tujuan_buat_srt' => 'Untuk keperluan studi lanjut',
            'tujuan_akhir' => 'wd',
            'role_surat' => 'wd1',
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->post("/srt_masih_mhw/wd1/cek_surat/setuju/{$surat->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Surat berhasil disetujui');

        $this->assertDatabaseHas('srt_masih_mhw', [
            'id' => $surat->id,
            'role_surat' => 'mahasiswa',
        ]);
    }
}
