<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Hashids\Hashids;

class Surat_Permohonan_Kembali_Biaya_Test extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     */
    public function test_halaman_surat_permohonan_pengembalian_biaya(): void
    {
        $response = $this->get('/srt_pmhn_kmbali_biaya');

        $response->assertStatus(302);
    }

    public function test_pembuatan_surat_pmhn_kmbali_biaya(): void
    {
        $this->withoutExceptionHandling();

        $user = \App\Models\User::factory()->create([
            'email' => 'mahasiswa@gmail.com',
            'password' => bcrypt('password'),
            'prd_id' => 1,
        ]);

        $this->actingAs($user);

        $skl = UploadedFile::fake()->create('skl.pdf', 100, 'application/pdf');
        $bukti_bayar = UploadedFile::fake()->create('bukti_bayar.pdf', 100, 'application/pdf');
        $buku_tabung = UploadedFile::fake()->create('buku_tabung.pdf', 100, 'application/pdf');

        $response = $this->post('/srt_pmhn_kmbali_biaya/create', [
            'skl' => $skl,
            'buku_tabung' => $buku_tabung,
            'bukti_bayar' => $bukti_bayar,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('srt_pmhn_kmbali_biaya.index'));

        $nama_skl = 'SKL_' . str_replace(' ', '_', $user->nama) . '_' . $user->nim_nip . '.pdf';
        $nama_bukti = 'Bukti_Bayar_' . str_replace(' ', '_', $user->nama) . '_' . $user->nim_nip . '.pdf';
        $nama_buku = 'Buku_Tabungan_' . str_replace(' ', '_', $user->nama) . '_' . $user->nim_nip . '.pdf';

        $this->assertDatabaseHas('srt_pmhn_kmbali_biaya', [
            'users_id' => $user->id,
            'skl' => $nama_skl,
            'bukti_bayar' => $nama_bukti,
            'buku_tabung' => $nama_buku,
        ]);
    }

    public function test_error_pembuatan_surat_pmhn_kmbali_biaya_salah_format(): void
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'mahasiswa@gmail.com',
            'password' => bcrypt('password'),
            'prd_id' => 1,
        ]);

        $this->actingAs($user);

        $skl = UploadedFile::fake()->create('skl.png', 100, 'image/png');
        $bukti_bayar = UploadedFile::fake()->create('bukti_bayar.png', 100, 'image/png');
        $buku_tabung = UploadedFile::fake()->create('buku_tabung.png', 100, 'image/png');

        try {
            $response = $this->post('/srt_pmhn_kmbali_biaya/create', [
                'skl' => $skl,
                'buku_tabung' => $buku_tabung,
                'bukti_bayar' => $bukti_bayar,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            dd($e->errors());
        }

        $response->assertStatus(302);

        $response->assertSessionHasErrors(['skl', 'buku_tabung', 'bukti_bayar']);

        $this->assertDatabaseMissing('srt_pmhn_kmbali_biaya', [
            'users_id' => $user->id,
        ]);
    }

    public function test_view_halaman_edit_srt_pmhn_kmbali_biaya(): void
    {
        $this->withoutExceptionHandling();

        $user = \App\Models\User::factory()->create([
            'email' => 'mahasiswa@gmail.com',
            'password' => bcrypt('mountain082'),
            'role' => 'mahasiswa',
            'prd_id' => 1,
        ]);

        $this->actingAs($user);
        $hashids = new Hashids('nilai-salt-unik-anda-di-sini', 7);
        $surat = DB::table('srt_pmhn_kmbali_biaya')->insertGetId([
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'skl' => 'skl.pdf',
            'buku_tabung' => 'buku_tabung.pdf',
            'bukti_bayar' => 'bukti_bayar.pdf',
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $encodedId = $hashids->encode($surat);
        $response = $this->get("/srt_pmhn_kmbali_biaya/edit/{$encodedId}");

        $response->assertStatus(200);
    }

    public function test_update_surat_pmhn_kmbali_biaya(): void
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

        $surat = DB::table('srt_pmhn_kmbali_biaya')->insertGetId([
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'skl' => 'skl.pdf',
            'buku_tabung' => 'buku_tabung.pdf',
            'bukti_bayar' => 'bukti_bayar.pdf',
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $new_skl = UploadedFile::fake()->create('skl_2.pdf', 100, 'application/pdf');
        $new_bukti_bayar = UploadedFile::fake()->create('bukti_bayar_2.pdf', 100, 'application/pdf');
        $new_buku_tabung = UploadedFile::fake()->create('buku_tabung_2.pdf', 100, 'application/pdf');

        $response = $this->post("/srt_pmhn_kmbali_biaya/update/{$surat}", [
            'skl' => $new_skl,
            'buku_tabung' => $new_buku_tabung,
            'bukti_bayar' => $new_bukti_bayar,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/srt_pmhn_kmbali_biaya');

        $formatted_skl = 'SKL_' . str_replace(' ', '_', $user->nama) . '_' . $user->nim_nip . '.pdf';
        $formatted_bukti_bayar = 'Bukti_Bayar_' . str_replace(' ', '_', $user->nama) . '_' . $user->nim_nip . '.pdf';
        $formatted_buku_tabung = 'Buku_Tabungan_' . str_replace(' ', '_', $user->nama) . '_' . $user->nim_nip . '.pdf';

        $this->assertDatabaseHas('srt_pmhn_kmbali_biaya', [
            'id' => $surat,
            'skl' => $formatted_skl,
            'buku_tabung' => $formatted_buku_tabung,
            'bukti_bayar' => $formatted_bukti_bayar,
        ]);
    }
    public function test_download_srt_pmhn_kmbali_biaya_mahasiswa()
    {
        $id = 6;

        $response = $this->get("/srt_pmhn_kmbali_biaya/download/{$id}");

        $response->assertStatus(302);
    }

    public function test_view_halaman_surat_pmhn_kmbali_biaya_oleh_admin(): void
    {
        $response = $this->get('/srt_pmhn_kmbali_biaya/admin');

        $response->assertStatus(302);
    }

    public function test_cek_surat_srt_pmhn_kmbali_biaya_oleh_admin()
    {
        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($user);

        $suratId = DB::table('srt_pmhn_kmbali_biaya')->insertGetId([
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'skl' => 'skl.pdf',
            'buku_tabung' => 'buku_tabung.pdf',
            'bukti_bayar' => 'bukti_bayar.pdf',
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->get("/srt_pmhn_kmbali_biaya/admin/cek_surat/{$suratId}");

        $response->assertStatus(200);
    }

    public function test_setuju_surat_permohonan_kembali_biaya_oleh_admin()
    {
        $admin = \App\Models\User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        $surat = \App\Models\srt_pmhn_kmbali_biaya::factory()->create([
            'no_surat' => null,
            'role_surat' => 'admin',
        ]);

        $response = $this->post("/srt_pmhn_kmbali_biaya/admin/cek_surat/setuju/{$surat->id}", [
            'no_surat' => '123456789',
        ]);

        $response->assertRedirect(route('srt_pmhn_kmbali_biaya.admin'));
        $response->assertSessionHas('success', 'No surat berhasil ditambahkan');

        $this->assertDatabaseHas('srt_pmhn_kmbali_biaya', [
            'id' => $surat->id,
            'no_surat' => '123456789',
            'role_surat' => 'supervisor_sd',
        ]);
    }

    public function test_tolak_surat_permohonan_kembali_biaya_oleh_admin()
    {
        $admin = \App\Models\User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin);


        $surat = \App\Models\srt_pmhn_kmbali_biaya::factory()->create([
            'catatan_surat' => null,
            'role_surat' => 'admin',
        ]);

        $response = $this->post("/srt_pmhn_kmbali_biaya/admin/cek_surat/tolak/{$surat->id}", [
            'catatan_surat' => 'Dokumen tidak lengkap',
        ]);

        $response->assertRedirect(route('srt_pmhn_kmbali_biaya.admin'));
        $response->assertSessionHas('success', 'Alasan penolakan telah dikirimkan');

        $this->assertDatabaseHas('srt_pmhn_kmbali_biaya', [
            'id' => $surat->id,
            'catatan_surat' => 'Dokumen tidak lengkap',
            'role_surat' => 'tolak',
        ]);
    }

    public function test_view_halaman_surat_permohonan_kembali_biaya_oleh_supervisor(): void
    {
        $response = $this->get('/srt_pmhn_kmbali_biaya/supervisor');

        $response->assertStatus(302);
    }

    public function test_cek_surat_srt_pmhn_kmbali_biaya_oleh_supervisor()
    {
        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'akd@example.com',
            'password' => bcrypt('password'),
            'role' => 'supervisor_sd',
        ]);

        $this->actingAs($user);

        $suratId = DB::table('srt_pmhn_kmbali_biaya')->insertGetId([
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'skl' => 'skl.pdf',
            'buku_tabung' => 'buku_tabung.pdf',
            'bukti_bayar' => 'bukti_bayar.pdf',
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->get("/srt_pmhn_kmbali_biaya/supervisor/cek_surat/{$suratId}");

        $response->assertStatus(200);
    }

    public function test_tolak_surat_permohonan_kembali_biaya_oleh_supervisor()
    {
        $sv = \App\Models\User::factory()->create([
            'email' => 'akd@example.com',
            'password' => bcrypt('password'),
            'role' => 'supervisor_sd',
        ]);

        $this->actingAs($sv);


        $surat = \App\Models\srt_pmhn_kmbali_biaya::factory()->create([
            'catatan_surat' => null,
            'role_surat' => 'supervisor_sd',
        ]);

        $response = $this->post("/srt_pmhn_kmbali_biaya/supervisor/cek_surat/tolak/{$surat->id}", [
            'catatan_surat' => 'Dokumen tidak lengkap',
        ]);

        $response->assertRedirect(route('srt_pmhn_kmbali_biaya.supervisor'));
        $response->assertSessionHas('success', 'Alasan penolakan telah dikirimkan');

        $this->assertDatabaseHas('srt_pmhn_kmbali_biaya', [
            'id' => $surat->id,
            'catatan_surat' => 'Dokumen tidak lengkap',
            'role_surat' => 'tolak',
        ]);
    }

    public function test_supervisor_setuju_srt_pmhn_kmbali_biaya()
    {
        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'supervisor@example.com',
            'password' => bcrypt('password'),
            'role' => 'supervisor_sd',
        ]);

        $this->actingAs($user);

        $surat = \App\Models\srt_pmhn_kmbali_biaya::factory()->create([
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'skl' => 'skl.pdf',
            'buku_tabung' => 'buku_tabung.pdf',
            'bukti_bayar' => 'bukti_bayar.pdf',
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->post("/srt_pmhn_kmbali_biaya/supervisor/cek_surat/setuju/{$surat->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Surat berhasil disetujui');

        $this->assertDatabaseHas('srt_pmhn_kmbali_biaya', [
            'id' => $surat->id,
            'role_surat' => 'manajer',
        ]);
    }

    public function test_halaman_surat_permohonan_kembali_biaya_oleh_manajer(): void
    {
        $response = $this->get('/srt_pmhn_kmbali_biaya/manajer');

        $response->assertStatus(302);
    }

    public function test_cek_surat_srt_pmhn_kmbali_biaya_oleh_manajer()
    {
        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'manajer@example.com',
            'password' => bcrypt('password'),
            'role' => 'manajer',
        ]);

        $this->actingAs($user);

        $suratId = DB::table('srt_pmhn_kmbali_biaya')->insertGetId([
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'skl' => 'skl.pdf',
            'buku_tabung' => 'buku_tabung.pdf',
            'bukti_bayar' => 'bukti_bayar.pdf',
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->get("/srt_pmhn_kmbali_biaya/manajer/cek_surat/{$suratId}");

        $response->assertStatus(200);
    }

    public function test_tolak_surat_permohonan_kembali_biaya_oleh_manajer()
    {
        $manajer = \App\Models\User::factory()->create([
            'email' => 'manajer@example.com',
            'password' => bcrypt('password'),
            'role' => 'manajer',
        ]);

        $this->actingAs($manajer);


        $surat = \App\Models\srt_pmhn_kmbali_biaya::factory()->create([
            'catatan_surat' => null,
            'role_surat' => 'manajer',
        ]);

        $response = $this->post("/srt_pmhn_kmbali_biaya/manajer/cek_surat/tolak/{$surat->id}", [
            'catatan_surat' => 'Dokumen tidak lengkap',
        ]);

        $response->assertRedirect(route('srt_pmhn_kmbali_biaya.manajer'));
        $response->assertSessionHas('success', 'Alasan penolakan telah dikirimkan');

        $this->assertDatabaseHas('srt_pmhn_kmbali_biaya', [
            'id' => $surat->id,
            'catatan_surat' => 'Dokumen tidak lengkap',
            'role_surat' => 'tolak',
        ]);
    }

    public function test_setuju_srt_pmhn_kmbali_biaya_oleh_manajer()
    {
        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'manajer@example.com',
            'password' => bcrypt('password'),
            'role' => 'manajer',
        ]);

        $this->actingAs($user);

        $surat = \App\Models\srt_pmhn_kmbali_biaya::factory()->create([
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'skl' => 'skl.pdf',
            'buku_tabung' => 'buku_tabung.pdf',
            'bukti_bayar' => 'bukti_bayar.pdf',
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->post("/srt_pmhn_kmbali_biaya/manajer/cek_surat/setuju/{$surat->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Surat berhasil disetujui');

        $this->assertDatabaseHas('srt_pmhn_kmbali_biaya', [
            'id' => $surat->id,
            'role_surat' => 'wd2',
        ]);
    }

    public function test_halaman_surat_permohonan_kembali_biaya_oleh_wd2(): void
    {
        $response = $this->get('/srt_pmhn_kmbali_biaya/wd2');

        $response->assertStatus(302);
    }

    public function test_cek_surat_srt_pmhn_kmbali_biaya_oleh_wd2()
    {
        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'wd2@example.com',
            'password' => bcrypt('password'),
            'role' => 'wd2',
        ]);

        $this->actingAs($user);

        $suratId = DB::table('srt_pmhn_kmbali_biaya')->insertGetId([
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'skl' => 'skl.pdf',
            'buku_tabung' => 'buku_tabung.pdf',
            'bukti_bayar' => 'bukti_bayar.pdf',
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->get("/srt_pmhn_kmbali_biaya/wd2/cek_surat/{$suratId}");

        $response->assertStatus(200);
    }

    public function test_tolak_surat_permohonan_kembali_biaya_oleh_wd2()
    {
        $wd2 = \App\Models\User::factory()->create([
            'email' => 'wd2@example.com',
            'password' => bcrypt('password'),
            'role' => 'wd2',
        ]);

        $this->actingAs($wd2);


        $surat = \App\Models\srt_pmhn_kmbali_biaya::factory()->create([
            'catatan_surat' => null,
            'role_surat' => 'wd2',
        ]);

        $response = $this->post("/srt_pmhn_kmbali_biaya/wd2/cek_surat/tolak/{$surat->id}", [
            'catatan_surat' => 'Dokumen tidak lengkap',
        ]);

        $response->assertRedirect(route('srt_pmhn_kmbali_biaya.wd2'));
        $response->assertSessionHas('success', 'Alasan penolakan telah dikirimkan');

        $this->assertDatabaseHas('srt_pmhn_kmbali_biaya', [
            'id' => $surat->id,
            'catatan_surat' => 'Dokumen tidak lengkap',
            'role_surat' => 'tolak',
        ]);
    }

    public function test_setuju_srt_pmhn_kmbali_biaya_oleh_wd2()
    {
        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'manajer@example.com',
            'password' => bcrypt('password'),
            'role' => 'wd2',
        ]);

        $this->actingAs($user);

        $surat = \App\Models\srt_pmhn_kmbali_biaya::factory()->create([
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'skl' => 'skl.pdf',
            'buku_tabung' => 'buku_tabung.pdf',
            'bukti_bayar' => 'bukti_bayar.pdf',
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->post("/srt_pmhn_kmbali_biaya/wd2/cek_surat/setuju/{$surat->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Surat berhasil disetujui');

        $this->assertDatabaseHas('srt_pmhn_kmbali_biaya', [
            'id' => $surat->id,
            'role_surat' => 'mahasiswa',
        ]);
    }
}
