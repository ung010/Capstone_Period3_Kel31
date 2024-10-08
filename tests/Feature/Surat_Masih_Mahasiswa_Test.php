<?php

namespace Tests\Feature;

use Carbon\Carbon;
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
    /**
     * A basic feature test example.
     */
    public function test_halaman_surat_masih_kuliah(): void
    {
        $response = $this->get('/srt_masih_mhw');

        $response->assertStatus(302);
    }

    public function test_buat_surat_untuk_manajer(): void
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

    public function test_buat_surat_untuk_wakil_dekan(): void
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

        try {
            $this->post('/srt_masih_mhw/create', [
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
            'nama_mhw' => $user->nama,
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

    public function test_update_surat(): void
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
            'nama_mhw' => $user->nama,
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

    public function test_halaman_surat_masih_mahasiswa_manajer(): void
    {
        $response = $this->get('/srt_masih_mhw/admin');

        $response->assertStatus(302);
    }

    public function test_cek_surat_srt_masih_mhw_manajer()
    {
        $admin = \App\Models\User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        $suratId = DB::table('srt_masih_mhw')->insertGetId([
            'users_id' => $admin->id,
            'nama_mhw' => 'Raung Calon Sarjana',
            'thn_awl' => 2020,
            'thn_akh' => 2024,
            'semester' => 6,
            'tujuan_buat_srt' => 'Berkeluarga',
            'almt_smg' => 'Semarang',
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
            'prd_id' => 1,
        ]);

        $response = $this->get("/srt_masih_mhw/admin/cek_surat/{$suratId}");

        $response->assertStatus(200);
    }

    public function test_setuju_surat_masih_mhw_manajer()
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

        $response = $this->post("/srt_masih_mhw/admin/cek_surat/setuju/{$srtMhwAsn->id}", [
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

    public function test_tolak_surat_masih_mhw_manajer()
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

        $response = $this->post("/srt_masih_mhw/admin/cek_surat/tolak/{$srtMhwAsn->id}", [
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

    public function test_halaman_surat_masih_mahasiswa_wd(): void
    {
        $response = $this->get('/srt_masih_mhw/manajer_wd');

        $response->assertStatus(302);
    }

    public function test_cek_surat_srt_masih_mhw_wd()
    {
        $admin = \App\Models\User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        $suratId = DB::table('srt_masih_mhw')->insertGetId([
            'users_id' => $admin->id,
            'nama_mhw' => 'Raung Calon Sarjana',
            'thn_awl' => 2020,
            'thn_akh' => 2024,
            'semester' => 6,
            'tujuan_buat_srt' => 'Berkeluarga',
            'almt_smg' => 'Semarang',
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
            'prd_id' => 1,
        ]);

        $response = $this->get("/srt_masih_mhw/manajer_wd/cek_surat/{$suratId}");

        $response->assertStatus(200);
    }

    public function test_setuju_surat_masih_mhw_wd()
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

        $response = $this->post("/srt_masih_mhw/manajer_wd/cek_surat/setuju/{$srtMhwAsn->id}", [
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

    public function test_tolak_surat_masih_mhw_wd()
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

        $response = $this->post("/srt_masih_mhw/manajer_wd/cek_surat/tolak/{$srtMhwAsn->id}", [
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

        $response = $this->get("/srt_masih_mhw/manajer_wd/download/{$id}");

        $response->assertStatus(302);
    }

    public function test_unggah_surat()
    {
        $admin = \App\Models\User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $surat = \App\Models\srt_masih_mhw::factory()->create([
            'users_id' => $admin->id,
            'prd_id' => 1,
            'nama_mhw' => $admin->nama,
            'thn_awl' => 2020,
            'thn_akh' => 2024,
            'semester' => 6,
            'almt_smg' => 'Alamat Semarang',
            'tujuan_buat_srt' => 'Untuk keperluan studi lanjut',
            'role_surat' => 'admin',
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $file = UploadedFile::fake()->create('test.pdf', 100, 'application/pdf');

        $this->actingAs($admin);

        $response = $this->post(route('srt_masih_mhw.wd_unggah', $surat->id), [
            'srt_masih_mhw' => $file,
        ]);

        $response->assertRedirect()->with('success', 'Berhasil menggunggah pdf ke mahasiswa');
        $response->assertStatus(302);
        $tanggal_surat = Carbon::parse($surat->tanggal_surat)->format('d-m-Y');
        $nama_mahasiswa = Str::slug($admin->nama);
        $fileName = "Surat_Masih_Mahasiswa_{$tanggal_surat}_{$nama_mahasiswa}.pdf";

        $this->assertDatabaseHas('srt_masih_mhw', [
            'id' => $surat->id,
            'file_pdf' => $fileName,
            'role_surat' => 'mahasiswa',
        ]);
    }

    public function test_view_halaman_supervisor_surat_masih_mahasiswa(): void
    {
        $response = $this->get('/srt_masih_mhw/supervisor');

        $response->assertStatus(302);
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
            'nama_mhw' => $supervisor->nama,
            'thn_awl' => 2020,
            'thn_akh' => 2024,
            'semester' => 6,
            'almt_smg' => 'Alamat Semarang',
            'tujuan_buat_srt' => 'Untuk keperluan studi lanjut',
            'role_surat' => 'supervisor_akd',
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->post("/srt_masih_mhw/supervisor/setuju/{$surat->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Surat berhasil disetujui');

        $this->assertDatabaseHas('srt_masih_mhw', [
            'id' => $surat->id,
            'role_surat' => 'manajer',
        ]);
    }

    public function test_halaman_manajer_surat_mahasiswa_(): void
    {
        $response = $this->get('/srt_masih_mhw/manajer');

        $response->assertStatus(302);
    }

    public function test_manajer_setuju_surat_masih_mahasiswa_wd()
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
            'nama_mhw' => $manajer->nama,
            'thn_awl' => 2020,
            'thn_akh' => 2024,
            'semester' => 6,
            'almt_smg' => 'Alamat Semarang',
            'tujuan_buat_srt' => 'Untuk keperluan studi lanjut',
            'tujuan_akhir' => 'manajer',
            'role_surat' => 'manajer',
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->post("/srt_masih_mhw/manajer/setuju/manajer/{$surat->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Surat berhasil disetujui');

        $this->assertDatabaseHas('srt_masih_mhw', [
            'id' => $surat->id,
            'role_surat' => 'manajer_sukses',
        ]);
    }

    public function test_manajer_setuju_surat_masih_mahasiswa_manajer()
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
            'nama_mhw' => $manajer->nama,
            'thn_awl' => 2020,
            'thn_akh' => 2024,
            'semester' => 6,
            'almt_smg' => 'Alamat Semarang',
            'tujuan_buat_srt' => 'Untuk keperluan studi lanjut',
            'tujuan_akhir' => 'wd',
            'role_surat' => 'manajer',
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->post("/srt_masih_mhw/manajer/setuju/wd/{$surat->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Surat berhasil disetujui');

        $this->assertDatabaseHas('srt_masih_mhw', [
            'id' => $surat->id,
            'role_surat' => 'mahasiswa',
        ]);
    }
}
