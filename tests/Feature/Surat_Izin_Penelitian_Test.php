<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Hashids\Hashids;

class Surat_Izin_Penelitian_Test extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic feature test example.
     */
    public function test_halaman_surat_izin_penelitian(): void
    {
        $response = $this->get('/srt_izin_plt');

        $response->assertStatus(302);
    }

    public function test_pembuatan_surat_izin_penelitian(): void
    {
        $this->withoutExceptionHandling();

        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'mahasiswa@gmail.com',
            'password' => bcrypt('password'),
            'prd_id' => 1,
        ]);

        $this->actingAs($user);

        $judul = $faker->sentence();
        if (strlen($judul) > 120) {
            $judul = substr($judul, 0, 120);
            $judul = substr($judul, 0, strrpos($judul, ' ')); // Potong pada spasi terakhir
        }

        $response = $this->post('/srt_izin_plt/create', [
            'semester' => $faker->randomDigitNotNull,
            'almt_lmbg' => $faker->address(),
            'jbt_lmbg' => $faker->jobTitle(),
            'kota_lmbg' => $faker->city(),
            'nama_lmbg' => $faker->company(),
            'judul_data' => $judul,
            'jenis_surat' => $faker->randomElement(['Kerja Praktek', 'Tugas Akhir Penelitian Mahasiswa', 'Ijin Penelitian' , 'Survey' , 'Thesis', 'Disertasi']),
            'lampiran' => $faker->randomElement(['1 Eksemplar', '2 Eksemplar']),
            'tanggal_surat' => $faker->date('Y-m-d'),
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/srt_izin_plt');
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
            $response = $this->post('/srt_izin_plt/create', [
                'semester' => $faker->randomDigitNotNull,
                'almt_lmbg' => $faker->address(),
                'jbt_lmbg' => $faker->jobTitle(),
                'kota_lmbg' => $faker->city(),
                'nama_lmbg' => $faker->company(),
                'jenis_surat' => $faker->randomElement(['Kerja Praktek', 'Tugas Akhir Penelitian Mahasiswa', 'Ijin Penelitian' , 'Survey' , 'Thesis', 'Disertasi']),
                'lampiran' => $faker->randomElement(['1 Eksemplar', '2 Eksemplar']),
                'tanggal_surat' => $faker->date('Y-m-d'),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertEquals('Judul/Tema Pengambilan Data Wajib diisi', $e->validator->errors()->first('judul_data'));
            return;
        }
        $response->assertStatus(302);
    }

    public function test_view_halaman_edit_surat_izin_penelitian(): void
    {
        $this->withoutExceptionHandling();

        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'mahasiswa@gmail.com',
            'password' => bcrypt('mountain082'),
            'role' => 'mahasiswa',
            'prd_id' => 1,
        ]);

        $judul = $faker->sentence();
        if (strlen($judul) > 120) {
            $judul = substr($judul, 0, 120);
            $judul = substr($judul, 0, strrpos($judul, ' ')); // Potong pada spasi terakhir
        }

        $this->actingAs($user);
        $hashids = new Hashids('nilai-salt-unik-anda-di-sini', 7);
        $surat = DB::table('srt_izin_plt')->insertGetId([
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'semester' => $faker->randomDigitNotNull,
            'almt_lmbg' => $faker->address(),
            'jbt_lmbg' => $faker->jobTitle(),
            'kota_lmbg' => $faker->city(),
            'nama_lmbg' => $faker->company(),
            'judul_data' => $judul,
            'jenis_surat' => $faker->randomElement(['Kerja Praktek', 'Tugas Akhir Penelitian Mahasiswa', 'Ijin Penelitian' , 'Survey' , 'Thesis', 'Disertasi']),
            'lampiran' => $faker->randomElement(['1 Eksemplar', '2 Eksemplar']),
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $encodedId = $hashids->encode($surat);
        $response = $this->get("/srt_izin_plt/edit/{$encodedId}");

        $response->assertStatus(200);
    }

    public function test_update_surat_izin_penelitian(): void
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

        $judul = $faker->sentence();
        if (strlen($judul) > 120) {
            $judul = substr($judul, 0, 120);
            $judul = substr($judul, 0, strrpos($judul, ' ')); // Potong pada spasi terakhir
        }

        $surat = DB::table('srt_izin_plt')->insertGetId([
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'semester' => $faker->randomDigitNotNull,
            'almt_lmbg' => $faker->address(),
            'jbt_lmbg' => $faker->jobTitle(),
            'kota_lmbg' => $faker->city(),
            'nama_lmbg' => $faker->company(),
            'judul_data' => $judul,
            'jenis_surat' => $faker->randomElement(['Kerja Praktek', 'Tugas Akhir Penelitian Mahasiswa', 'Ijin Penelitian' , 'Survey' , 'Thesis', 'Disertasi']),
            'lampiran' => $faker->randomElement(['1 Eksemplar', '2 Eksemplar']),
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->post("/srt_izin_plt/update/{$surat}", [
            'almt_lmbg' => $faker->address(),
            'nama_lmbg' => $faker->company(),
            'jbt_lmbg' => $faker->jobTitle(),
            'semester' => $faker->randomDigitNotNull,
            'kota_lmbg' => 'Blitar',
            'lampiran' => '2 Eksemplar',
            'judul_data' => 'Kerja Praktek',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/srt_izin_plt');

        $this->assertDatabaseHas('srt_izin_plt', [
            'id' => $surat,
            'kota_lmbg' => 'Blitar',
            'lampiran' => '2 Eksemplar',
            'judul_data' => 'Kerja Praktek',
        ]);
    }

    public function test_view_halaman_surat_izin_penelitian_oleh_admin(): void
    {
        $response = $this->get('/srt_izin_plt/admin');

        $response->assertStatus(302);
    }

    public function test_cek_surat_srt_izin_plt_oleh_admin()
    {
        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($user);

        $judul = $faker->sentence();
        if (strlen($judul) > 120) {
            $judul = substr($judul, 0, 120);
            $judul = substr($judul, 0, strrpos($judul, ' ')); // Potong pada spasi terakhir
        }

        $suratId = DB::table('srt_izin_plt')->insertGetId([
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'semester' => $faker->randomDigitNotNull,
            'almt_lmbg' => $faker->address(),
            'jbt_lmbg' => $faker->jobTitle(),
            'kota_lmbg' => $faker->city(),
            'nama_lmbg' => $faker->company(),
            'judul_data' => $judul,
            'jenis_surat' => $faker->randomElement(['Kerja Praktek', 'Tugas Akhir Penelitian Mahasiswa', 'Ijin Penelitian' , 'Survey' , 'Thesis', 'Disertasi']),
            'lampiran' => $faker->randomElement(['1 Eksemplar', '2 Eksemplar']),
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->get("/srt_izin_plt/admin/cek_surat/{$suratId}");

        $response->assertStatus(200);
    }

    public function test_setuju_surat_izin_penelitian_oleh_admin()
    {
        $admin = \App\Models\User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        $surat = \App\Models\srt_izin_penelitian::factory()->create([
            'no_surat' => null,
            'role_surat' => 'admin',
        ]);

        $response = $this->post("/srt_izin_plt/admin/cek_surat/setuju/{$surat->id}", [
            'no_surat' => '123456789',
        ]);

        $response->assertRedirect(route('srt_izin_plt.admin'));
        $response->assertSessionHas('success', 'No surat berhasil ditambahkan');

        $this->assertDatabaseHas('srt_izin_plt', [
            'id' => $surat->id,
            'no_surat' => '123456789',
            'role_surat' => 'supervisor_akd',
        ]);
    }

    public function test_tolak_surat_izin_penelitian_oleh_admin()
    {
        $admin = \App\Models\User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin);


        $surat = \App\Models\srt_izin_penelitian::factory()->create([
            'catatan_surat' => null,
            'role_surat' => 'admin',
        ]);

        $response = $this->post("/srt_izin_plt/admin/cek_surat/tolak/{$surat->id}", [
            'catatan_surat' => 'Dokumen tidak lengkap',
        ]);

        $response->assertRedirect(route('srt_izin_plt.admin'));
        $response->assertSessionHas('success', 'Alasan penolakan telah dikirimkan');

        $this->assertDatabaseHas('srt_izin_plt', [
            'id' => $surat->id,
            'catatan_surat' => 'Dokumen tidak lengkap',
            'role_surat' => 'tolak',
        ]);
    }

    public function test_download_srt_izin_plt()
    {
        $id = 6;

        $response = $this->get("/srt_izin_plt/download/{$id}");

        $response->assertStatus(302);
    }

    public function test_view_halaman_surat_izin_penelitian_oleh_supervisor(): void
    {
        $response = $this->get('/srt_izin_plt/supervisor');

        $response->assertStatus(302);
    }

    public function test_cek_surat_srt_izin_plt_oleh_supervisor()
    {
        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'akd@example.com',
            'password' => bcrypt('password'),
            'role' => 'supervisor_akd',
        ]);

        $this->actingAs($user);

        $judul = $faker->sentence();
        if (strlen($judul) > 120) {
            $judul = substr($judul, 0, 120);
            $judul = substr($judul, 0, strrpos($judul, ' ')); // Potong pada spasi terakhir
        }

        $suratId = DB::table('srt_izin_plt')->insertGetId([
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'semester' => $faker->randomDigitNotNull,
            'almt_lmbg' => $faker->address(),
            'jbt_lmbg' => $faker->jobTitle(),
            'kota_lmbg' => $faker->city(),
            'nama_lmbg' => $faker->company(),
            'judul_data' => $judul,
            'jenis_surat' => $faker->randomElement(['Kerja Praktek', 'Tugas Akhir Penelitian Mahasiswa', 'Ijin Penelitian' , 'Survey' , 'Thesis', 'Disertasi']),
            'lampiran' => $faker->randomElement(['1 Eksemplar', '2 Eksemplar']),
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->get("/srt_izin_plt/supervisor/cek_surat/{$suratId}");

        $response->assertStatus(200);
    }

    public function test_tolak_surat_izin_penelitian_oleh_supervisor()
    {
        $admin = \App\Models\User::factory()->create([
            'email' => 'akd@example.com',
            'password' => bcrypt('password'),
            'role' => 'supervisor_akd',
        ]);

        $this->actingAs($admin);


        $surat = \App\Models\srt_izin_penelitian::factory()->create([
            'catatan_surat' => null,
            'role_surat' => 'supervisor_akd',
        ]);

        $response = $this->post("/srt_izin_plt/supervisor/cek_surat/tolak/{$surat->id}", [
            'catatan_surat' => 'Dokumen tidak lengkap',
        ]);

        $response->assertRedirect(route('srt_izin_plt.supervisor'));
        $response->assertSessionHas('success', 'Alasan penolakan telah dikirimkan');

        $this->assertDatabaseHas('srt_izin_plt', [
            'id' => $surat->id,
            'catatan_surat' => 'Dokumen tidak lengkap',
            'role_surat' => 'tolak',
        ]);
    }

    public function test_setuju_srt_izin_plt_oleh_supervisor()
    {
        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'supervisor@example.com',
            'password' => bcrypt('password'),
            'role' => 'supervisor_akd',
        ]);

        $this->actingAs($user);

        $judul = $faker->sentence();
        if (strlen($judul) > 120) {
            $judul = substr($judul, 0, 120);
            $judul = substr($judul, 0, strrpos($judul, ' ')); // Potong pada spasi terakhir
        }

        $surat = \App\Models\srt_izin_penelitian::factory()->create([
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'semester' => $faker->randomDigitNotNull,
            'almt_lmbg' => $faker->address(),
            'jbt_lmbg' => $faker->jobTitle(),
            'kota_lmbg' => $faker->city(),
            'nama_lmbg' => $faker->company(),
            'judul_data' => $judul,
            'jenis_surat' => $faker->randomElement(['Kerja Praktek', 'Tugas Akhir Penelitian Mahasiswa', 'Ijin Penelitian' , 'Survey' , 'Thesis', 'Disertasi']),
            'lampiran' => $faker->randomElement(['1 Eksemplar', '2 Eksemplar']),
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->post("/srt_izin_plt/supervisor/cek_surat/setuju/{$surat->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Surat berhasil disetujui');

        $this->assertDatabaseHas('srt_izin_plt', [
            'id' => $surat->id,
            'role_surat' => 'manajer',
        ]);
    }

    public function test_halaman_surat_izin_penelitian_oleh_manajer(): void
    {
        $response = $this->get('/srt_izin_plt/manajer');

        $response->assertStatus(302);
    }

    public function test_cek_surat_srt_izin_plt_oleh_manajer()
    {
        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'manajer@example.com',
            'password' => bcrypt('password'),
            'role' => 'manajer',
        ]);

        $this->actingAs($user);

        $judul = $faker->sentence();
        if (strlen($judul) > 120) {
            $judul = substr($judul, 0, 120);
            $judul = substr($judul, 0, strrpos($judul, ' ')); // Potong pada spasi terakhir
        }

        $suratId = DB::table('srt_izin_plt')->insertGetId([
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'semester' => $faker->randomDigitNotNull,
            'almt_lmbg' => $faker->address(),
            'jbt_lmbg' => $faker->jobTitle(),
            'kota_lmbg' => $faker->city(),
            'nama_lmbg' => $faker->company(),
            'judul_data' => $judul,
            'jenis_surat' => $faker->randomElement(['Kerja Praktek', 'Tugas Akhir Penelitian Mahasiswa', 'Ijin Penelitian' , 'Survey' , 'Thesis', 'Disertasi']),
            'lampiran' => $faker->randomElement(['1 Eksemplar', '2 Eksemplar']),
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->get("/srt_izin_plt/manajer/cek_surat/{$suratId}");

        $response->assertStatus(200);
    }

    public function test_tolak_surat_izin_penelitian_oleh_manajer()
    {
        $admin = \App\Models\User::factory()->create([
            'email' => 'manajer@example.com',
            'password' => bcrypt('password'),
            'role' => 'manajer',
        ]);

        $this->actingAs($admin);


        $surat = \App\Models\srt_izin_penelitian::factory()->create([
            'catatan_surat' => null,
            'role_surat' => 'manajer',
        ]);

        $response = $this->post("/srt_izin_plt/manajer/cek_surat/tolak/{$surat->id}", [
            'catatan_surat' => 'Dokumen tidak lengkap',
        ]);

        $response->assertRedirect(route('srt_izin_plt.manajer'));
        $response->assertSessionHas('success', 'Alasan penolakan telah dikirimkan');

        $this->assertDatabaseHas('srt_izin_plt', [
            'id' => $surat->id,
            'catatan_surat' => 'Dokumen tidak lengkap',
            'role_surat' => 'tolak',
        ]);
    }

    public function test_setuju_srt_izin_plt_oleh_manajer()
    {
        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'manajer@example.com',
            'password' => bcrypt('password'),
            'role' => 'manajer',
        ]);

        $this->actingAs($user);

        $judul = $faker->sentence();
        if (strlen($judul) > 120) {
            $judul = substr($judul, 0, 120);
            $judul = substr($judul, 0, strrpos($judul, ' ')); // Potong pada spasi terakhir
        }

        $surat = \App\Models\srt_izin_penelitian::factory()->create([
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'semester' => $faker->randomDigitNotNull,
            'almt_lmbg' => $faker->address(),
            'jbt_lmbg' => $faker->jobTitle(),
            'kota_lmbg' => $faker->city(),
            'nama_lmbg' => $faker->company(),
            'judul_data' => $judul,
            'jenis_surat' => $faker->randomElement(['Kerja Praktek', 'Tugas Akhir Penelitian Mahasiswa', 'Ijin Penelitian' , 'Survey' , 'Thesis', 'Disertasi']),
            'lampiran' => $faker->randomElement(['1 Eksemplar', '2 Eksemplar']),
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->post("/srt_izin_plt/manajer/cek_surat/setuju/{$surat->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Surat berhasil disetujui');

        $this->assertDatabaseHas('srt_izin_plt', [
            'id' => $surat->id,
            'role_surat' => 'wd1',
        ]);
    }

    public function test_halaman_surat_izin_penelitian_oleh_wd1(): void
    {
        $response = $this->get('/srt_izin_plt/wd1');

        $response->assertStatus(302);
    }

    public function test_cek_surat_srt_izin_plt_oleh_wd1()
    {
        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'wd1@example.com',
            'password' => bcrypt('password'),
            'role' => 'wd1',
        ]);

        $this->actingAs($user);

        $judul = $faker->sentence();
        if (strlen($judul) > 120) {
            $judul = substr($judul, 0, 120);
            $judul = substr($judul, 0, strrpos($judul, ' ')); // Potong pada spasi terakhir
        }

        $suratId = DB::table('srt_izin_plt')->insertGetId([
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'semester' => $faker->randomDigitNotNull,
            'almt_lmbg' => $faker->address(),
            'jbt_lmbg' => $faker->jobTitle(),
            'kota_lmbg' => $faker->city(),
            'nama_lmbg' => $faker->company(),
            'judul_data' => $judul,
            'jenis_surat' => $faker->randomElement(['Kerja Praktek', 'Tugas Akhir Penelitian Mahasiswa', 'Ijin Penelitian' , 'Survey' , 'Thesis', 'Disertasi']),
            'lampiran' => $faker->randomElement(['1 Eksemplar', '2 Eksemplar']),
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->get("/srt_izin_plt/wd1/cek_surat/{$suratId}");

        $response->assertStatus(200);
    }

    public function test_tolak_surat_izin_penelitian_oleh_wd1()
    {
        $admin = \App\Models\User::factory()->create([
            'email' => 'wd1@example.com',
            'password' => bcrypt('password'),
            'role' => 'wd1',
        ]);

        $this->actingAs($admin);


        $surat = \App\Models\srt_izin_penelitian::factory()->create([
            'catatan_surat' => null,
            'role_surat' => 'wd1',
        ]);

        $response = $this->post("/srt_izin_plt/wd1/cek_surat/tolak/{$surat->id}", [
            'catatan_surat' => 'Dokumen tidak lengkap',
        ]);

        $response->assertRedirect(route('srt_izin_plt.wd1'));
        $response->assertSessionHas('success', 'Alasan penolakan telah dikirimkan');

        $this->assertDatabaseHas('srt_izin_plt', [
            'id' => $surat->id,
            'catatan_surat' => 'Dokumen tidak lengkap',
            'role_surat' => 'tolak',
        ]);
    }

    public function test_setuju_srt_izin_plt_oleh_wd1()
    {
        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'wd1@example.com',
            'password' => bcrypt('password'),
            'role' => 'wd1',
        ]);

        $this->actingAs($user);

        $judul = $faker->sentence();
        if (strlen($judul) > 120) {
            $judul = substr($judul, 0, 120);
            $judul = substr($judul, 0, strrpos($judul, ' ')); // Potong pada spasi terakhir
        }

        $surat = \App\Models\srt_izin_penelitian::factory()->create([
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'semester' => $faker->randomDigitNotNull,
            'almt_lmbg' => $faker->address(),
            'jbt_lmbg' => $faker->jobTitle(),
            'kota_lmbg' => $faker->city(),
            'nama_lmbg' => $faker->company(),
            'judul_data' => $judul,
            'jenis_surat' => $faker->randomElement(['Kerja Praktek', 'Tugas Akhir Penelitian Mahasiswa', 'Ijin Penelitian' , 'Survey' , 'Thesis', 'Disertasi']),
            'lampiran' => $faker->randomElement(['1 Eksemplar', '2 Eksemplar']),
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->post("/srt_izin_plt/wd1/cek_surat/setuju/{$surat->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Surat berhasil disetujui');

        $this->assertDatabaseHas('srt_izin_plt', [
            'id' => $surat->id,
            'role_surat' => 'mahasiswa',
        ]);
    }
}
