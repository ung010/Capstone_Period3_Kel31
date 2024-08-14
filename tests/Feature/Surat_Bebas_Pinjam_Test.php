<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Surat_Bebas_Pinjam_Test extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_halaman_surat_bebas_pinjam(): void
    {
        $response = $this->get('/srt_bbs_pnjm');

        $response->assertStatus(302);
    }

    public function test_pembuatan_surat_bebas_pinjam(): void
    {
        $this->withoutExceptionHandling();

        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'mahasiswa@gmail.com',
            'password' => bcrypt('password'),
            'jnjg_id' => 1,
            'prd_id' => 1,
            'dpt_id' => 1,
        ]);

        $this->actingAs($user);

        $response = $this->post('/srt_bbs_pnjm/create', [
            'nama_mhw' => $faker->name,
            'dosen_wali' => $faker->name(),
            'almt_smg' => $faker->address(),
            'tanggal_surat' => $faker->date('Y-m-d'),
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/srt_bbs_pnjm');
    }

    public function test_view_halaman_edit_surat_bebas_pinjam(): void
    {

        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'mahasiswa@gmail.com',
            'password' => bcrypt('mountain082'),
            'role' => 'mahasiswa',
            'prd_id' => 1,
            'dpt_id' => 1,
            'jnjg_id' => 1,
            'nama' => 'Raung Calon Sarjana',
        ]);

        $this->actingAs($user);

        $surat = DB::table('srt_bbs_pnjm')->insertGetId([
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'dpt_id' => $user->dpt_id,
            'jnjg_id' => $user->jnjg_id,
            'nama_mhw' => $user->nama,
            'dosen_wali' => $faker->name(),
            'almt_smg' => $faker->address(),
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->get("/srt_bbs_pnjm/edit/{$surat}");


        $response->assertStatus(200);

        $response->assertSee('Raung Calon Sarjana');
    }

    public function test_update_surat_bebas_pinjam(): void
    {
        $this->withoutExceptionHandling();

        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'mahasiswa@gmail.com',
            'password' => bcrypt('mountain082'),
            'role' => 'mahasiswa',
            'prd_id' => 1,
            'dpt_id' => 1,
            'jnjg_id' => 1,
            'nama' => 'Raung Calon Sarjana',
        ]);
        $this->actingAs($user);

        $surat = DB::table('srt_bbs_pnjm')->insertGetId([
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'dpt_id' => $user->dpt_id,
            'jnjg_id' => $user->jnjg_id,
            'nama_mhw' => $user->nama,
            'dosen_wali' => $faker->name(),
            'almt_smg' => $faker->address(),
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->post("/srt_bbs_pnjm/update/{$surat}", [
            'dosen_wali' => 'Arwiyudi',
            'almt_smg' => 'Tembalang',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/srt_bbs_pnjm');

        $this->assertDatabaseHas('srt_bbs_pnjm', [
            'id' => $surat,
            'dosen_wali' => 'Arwiyudi',
            'almt_smg' => 'Tembalang',
        ]);
    }

    public function test_view_halaman_surat_bebas_pinjam_di_admin(): void
    {
        $response = $this->get('/srt_bbs_pnjm/admin');

        $response->assertStatus(302);
    }

    public function test_cek_surat_srt_bbs_pnjm()
    {
        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($user);

        $suratId = DB::table('srt_bbs_pnjm')->insertGetId([
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'dpt_id' => $user->dpt_id,
            'jnjg_id' => $user->jnjg_id,
            'nama_mhw' => $user->nama,
            'dosen_wali' => $faker->name(),
            'almt_smg' => $faker->address(),
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->get("/srt_bbs_pnjm/admin/cek_surat/{$suratId}");

        $response->assertStatus(200);
    }

    public function test_setuju_surat_bebas_pinjam()
    {
        $admin = \App\Models\User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        $surat = \App\Models\srt_bbs_pnjm::factory()->create([
            'no_surat' => null,
            'role_surat' => 'admin',
        ]);

        $response = $this->post("/srt_bbs_pnjm/admin/cek_surat/setuju/{$surat->id}", [
            'no_surat' => '123456789',
        ]);

        $response->assertRedirect(route('srt_bbs_pnjm.admin'));
        $response->assertSessionHas('success', 'No surat berhasil ditambahkan');

        $this->assertDatabaseHas('srt_bbs_pnjm', [
            'id' => $surat->id,
            'no_surat' => '123456789',
            'role_surat' => 'supervisor_sd',
        ]);
    }

    public function test_tolak_surat_bebas_pinjam()
    {
        $admin = \App\Models\User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin);


        $surat = \App\Models\srt_bbs_pnjm::factory()->create([
            'catatan_surat' => null,
            'role_surat' => 'admin',
        ]);

        $response = $this->post("/srt_bbs_pnjm/admin/cek_surat/tolak/{$surat->id}", [
            'catatan_surat' => 'Dokumen tidak lengkap',
        ]);

        $response->assertRedirect(route('srt_bbs_pnjm.admin'));
        $response->assertSessionHas('success', 'Alasan penolakan telah dikirimkan');

        $this->assertDatabaseHas('srt_bbs_pnjm', [
            'id' => $surat->id,
            'catatan_surat' => 'Dokumen tidak lengkap',
            'role_surat' => 'tolak',
        ]);
    }

    public function test_download_surat_bebas_pinjam_mahasiswa()
    {
        $id = 6;

        $response = $this->get("/srt_bbs_pnjm/download/{$id}");

        $response->assertStatus(302);
    }

    public function test_view_halaman_supervisor_surat_bebas_pinjam(): void
    {
        $response = $this->get('/srt_bbs_pnjm/supervisor');

        $response->assertStatus(302);
    }

    public function test_supervisor_setuju_srt_bbs_pnjm()
    {
        $faker = \Faker\Factory::create();
        
        $user = \App\Models\User::factory()->create([
            'email' => 'supervisor@example.com',
            'password' => bcrypt('password'),
            'role' => 'supervisor_sd',
        ]);

        $this->actingAs($user);

        $surat = \App\Models\srt_bbs_pnjm::factory()->create([
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'dpt_id' => $user->dpt_id,
            'jnjg_id' => $user->jnjg_id,
            'nama_mhw' => $user->nama,
            'dosen_wali' => $faker->name(),
            'almt_smg' => $faker->address(),
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->post("/srt_bbs_pnjm/supervisor/setuju/{$surat->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Surat berhasil disetujui');

        $this->assertDatabaseHas('srt_bbs_pnjm', [
            'id' => $surat->id,
            'role_surat' => 'mahasiswa',
        ]);
    }
}
