<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Hashids\Hashids;
use Illuminate\Support\Facades\File;
use Mockery;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

class Surat_ASN_Test extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     */

    public function test_view_halaman_surat_asn(): void
    {
        $response = $this->get('/srt_mhw_asn');

        $response->assertStatus(302);
    }

    public function test_buat_surat_asn(): void
    {
        $this->withoutExceptionHandling();

        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'mahasiswa@gmail.com',
            'password' => bcrypt('password'),
            'prd_id' => 1,
        ]);

        $this->actingAs($user);

        $response = $this->post('/srt_mhw_asn/create', [
            'thn_awl' => 2020,
            'thn_akh' => 2024,
            'semester' => 6,
            'nama_ortu' => $faker->name,
            'nip_ortu' => $faker->numerify('#######'),
            'ins_ortu' => $faker->company,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/srt_mhw_asn');
    }

    public function test_gagal_buat_surat_asn_baru_karena_data_kurang(): void
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
            $response = $this->post('/srt_mhw_asn/create', [
                'thn_awl' => 2020,
                'thn_akh' => 2024,
                'semester' => 6,
                'nama_ortu' => $faker->name,
                'nip_ortu' => $faker->numerify('#######'),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertEquals('Instansi orang tua wajib diisi', $e->validator->errors()->first('ins_ortu'));
            return;
        }

        $response->assertStatus(302);
    }

    public function test_view_halaman_edit_surat_mahasiswa_asn(): void
    {
        $this->withoutExceptionHandling();

        $user = \App\Models\User::factory()->create([
            'email' => 'mahasiswa@gmail.com',
            'password' => bcrypt('mountain082'),
            'role' => 'mahasiswa',
        ]);

        $this->actingAs($user);

        $hashids = new Hashids('nilai-salt-unik-anda-di-sini', 7);
        $surat = DB::table('srt_mhw_asn')->insertGetId([
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'thn_awl' => 2020,
            'thn_akh' => 2024,
            'semester' => 6,
            'nama_ortu' => 'Nama Ortu Lama',
            'nip_ortu' => '1234567890',
            'ins_ortu' => 'Instansi Lama',
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $encodedId = $hashids->encode($surat);
        $response = $this->get("/srt_mhw_asn/edit/{$encodedId}");

        $response->assertStatus(200);
    }


    public function test_update_surat_mahasiswa_asn(): void
    {
        $this->withoutExceptionHandling();

        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'mahasiswa@gmail.com',
            'password' => bcrypt('password'),
            'prd_id' => 1,
        ]);
        $this->actingAs($user);

        $surat = DB::table('srt_mhw_asn')->insertGetId([
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'thn_awl' => 2020,
            'thn_akh' => 2024,
            'semester' => 6,
            'nama_ortu' => 'Nama Ortu Lama',
            'nip_ortu' => '1234567890',
            'ins_ortu' => 'Instansi Lama',
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->post("/srt_mhw_asn/update/{$surat}", [
            'thn_awl' => 2021,
            'thn_akh' => 2025,
            'semester' => 7,
            'nama_ortu' => $faker->name,
            'nip_ortu' => $faker->numerify('##########'),
            'ins_ortu' => $faker->company,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/srt_mhw_asn');

        $this->assertDatabaseHas('srt_mhw_asn', [
            'id' => $surat,
            'thn_awl' => 2021,
            'thn_akh' => 2025,
            'semester' => 7,
        ]);
    }

    public function test_download_surat_asn()
    {
        $id = 1;

        $response = $this->get("/srt_mhw_asn/download/{$id}");

        $response->assertStatus(302);
    }

    public function test_view_halaman_surat_asn_admin(): void
    {
        $response = $this->get('/srt_mhw_asn/admin');

        $response->assertStatus(302);
    }

    public function test_cek_surat_asn_oleh_admin()
    {
        $admin = \App\Models\User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        $surat = DB::table('srt_mhw_asn')->insertGetId([
            'users_id' => $admin->id,
            'thn_awl' => 2020,
            'thn_akh' => 2024,
            'semester' => 6,
            'nama_ortu' => 'Jane Doe',
            'nip_ortu' => '987654321',
            'ins_ortu' => 'Some Institution',
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
            'prd_id' => 1,
        ]);

        $response = $this->get("/srt_mhw_asn/admin/cek_surat/{$surat}");

        $response->assertStatus(200);
    }

    public function test_setuju_surat_asn_oleh_admin()
    {
        $admin = \App\Models\User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        $surat = \App\Models\Srt_Mhw_Asn::factory()->create([
            'no_surat' => null,
            'role_surat' => 'admin',
        ]);

        $response = $this->post("/srt_mhw_asn/admin/cek_surat/setuju/{$surat->id}", [
            'no_surat' => '123456789',
        ]);

        $response->assertRedirect(route('srt_mhw_asn.admin'));
        $response->assertSessionHas('success', 'No surat berhasil ditambahkan');
        $response->assertStatus(302);
        $this->assertDatabaseHas('srt_mhw_asn', [
            'id' => $surat->id,
            'no_surat' => '123456789',
            'role_surat' => 'supervisor_akd',
        ]);
    }

    public function test_tolak_surat_asn_oleh_admin()
    {
        $admin = \App\Models\User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin);


        $surat = \App\Models\Srt_Mhw_Asn::factory()->create([
            'catatan_surat' => null,
            'role_surat' => 'admin',
        ]);

        $response = $this->post("/srt_mhw_asn/admin/cek_surat/tolak/{$surat->id}", [
            'catatan_surat' => 'Dokumen tidak lengkap',
        ]);

        $response->assertRedirect(route('srt_mhw_asn.admin'));
        $response->assertSessionHas('success', 'Alasan penolakan telah dikirimkan');
        $response->assertStatus(302);
        $this->assertDatabaseHas('srt_mhw_asn', [
            'id' => $surat->id,
            'catatan_surat' => 'Dokumen tidak lengkap',
            'role_surat' => 'tolak',
        ]);
    }

    public function test_halaman_surat_asn_supervisor(): void
    {
        $response = $this->get('/srt_mhw_asn/supervisor');

        $response->assertStatus(302);
    }

    public function test_cek_surat_asn_oleh_supervisor()
    {
        $admin = \App\Models\User::factory()->create([
            'email' => 'akd@example.com',
            'password' => bcrypt('password'),
            'role' => 'supervisor_akd',
        ]);

        $this->actingAs($admin);

        $surat = DB::table('srt_mhw_asn')->insertGetId([
            'users_id' => $admin->id,
            'thn_awl' => 2020,
            'thn_akh' => 2024,
            'semester' => 6,
            'nama_ortu' => 'Jane Doe',
            'nip_ortu' => '987654321',
            'ins_ortu' => 'Some Institution',
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
            'prd_id' => 1,
        ]);

        $response = $this->get("/srt_mhw_asn/supervisor/cek_surat/{$surat}");

        $response->assertStatus(200);
    }

    public function test_tolak_surat_asn_oleh_supervisor()
    {
        $admin = \App\Models\User::factory()->create([
            'email' => 'akd@example.com',
            'password' => bcrypt('password'),
            'role' => 'supervisor_akd',
        ]);

        $this->actingAs($admin);


        $surat = \App\Models\Srt_Mhw_Asn::factory()->create([
            'catatan_surat' => null,
            'role_surat' => 'supervisor_akd',
        ]);

        $response = $this->post("/srt_mhw_asn/supervisor/cek_surat/tolak/{$surat->id}", [
            'catatan_surat' => 'Dokumen tidak lengkap',
        ]);

        $response->assertRedirect(route('srt_mhw_asn.supervisor'));
        $response->assertSessionHas('success', 'Alasan penolakan telah dikirimkan');
        $response->assertStatus(302);
        $this->assertDatabaseHas('srt_mhw_asn', [
            'id' => $surat->id,
            'catatan_surat' => 'Dokumen tidak lengkap',
            'role_surat' => 'tolak',
        ]);
    }

    public function test_persetujuan_surat_asn_oleh_supervisor()
    {
        $supervisor = \App\Models\User::factory()->create([
            'email' => 'supervisor@example.com',
            'password' => bcrypt('password'),
            'role' => 'supervisor_akd',
        ]);

        $this->actingAs($supervisor);

        $surat = \App\Models\Srt_Mhw_Asn::factory()->create([
            'role_surat' => 'supervisor_akd',
        ]);

        $response = $this->post("/srt_mhw_asn/supervisor/cek_surat/setuju/{$surat->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Surat berhasil disetujui');
        $response->assertStatus(302);
        $this->assertDatabaseHas('srt_mhw_asn', [
            'id' => $surat->id,
            'role_surat' => 'manajer',
        ]);
    }

    public function test_view_halaman_surat_asn_oleh_manajer(): void
    {
        $response = $this->get('/srt_mhw_asn/manajer');

        $response->assertStatus(302);
    }

    public function test_cek_surat_asn_oleh_manajer()
    {
        $admin = \App\Models\User::factory()->create([
            'email' => 'manajer@example.com',
            'password' => bcrypt('password'),
            'role' => 'manajer',
        ]);

        $this->actingAs($admin);

        $surat = DB::table('srt_mhw_asn')->insertGetId([
            'users_id' => $admin->id,
            'thn_awl' => 2020,
            'thn_akh' => 2024,
            'semester' => 6,
            'nama_ortu' => 'Jane Doe',
            'nip_ortu' => '987654321',
            'ins_ortu' => 'Some Institution',
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
            'prd_id' => 1,
        ]);

        $response = $this->get("/srt_mhw_asn/manajer/cek_surat/{$surat}");

        $response->assertStatus(200);
    }

    public function test_tolak_surat_asn_oleh_manajer()
    {
        $admin = \App\Models\User::factory()->create([
            'email' => 'manajer@example.com',
            'password' => bcrypt('password'),
            'role' => 'manajer',
        ]);

        $this->actingAs($admin);


        $surat = \App\Models\Srt_Mhw_Asn::factory()->create([
            'catatan_surat' => null,
            'role_surat' => 'manajer',
        ]);

        $response = $this->post("/srt_mhw_asn/manajer/cek_surat/tolak/{$surat->id}", [
            'catatan_surat' => 'Dokumen tidak lengkap',
        ]);

        $response->assertRedirect(route('srt_mhw_asn.manajer'));
        $response->assertSessionHas('success', 'Alasan penolakan telah dikirimkan');
        $response->assertStatus(302);
        $this->assertDatabaseHas('srt_mhw_asn', [
            'id' => $surat->id,
            'catatan_surat' => 'Dokumen tidak lengkap',
            'role_surat' => 'tolak',
        ]);
    }

    public function test_setuju_surat_asn_oleh_manajer()
    {
        $supervisor = \App\Models\User::factory()->create([
            'email' => 'manajer@example.com',
            'password' => bcrypt('password'),
            'role' => 'manajer',
        ]);

        $this->actingAs($supervisor);

        $srtMhwAsn = \App\Models\Srt_Mhw_Asn::factory()->create([
            'role_surat' => 'supervisor_akd',
        ]);

        $response = $this->post("/srt_mhw_asn/manajer/cek_surat/setuju/{$srtMhwAsn->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Surat berhasil disetujui');
        $response->assertStatus(302);
        $this->assertDatabaseHas('srt_mhw_asn', [
            'id' => $srtMhwAsn->id,
            'role_surat' => 'mahasiswa',
        ]);
    }
}
