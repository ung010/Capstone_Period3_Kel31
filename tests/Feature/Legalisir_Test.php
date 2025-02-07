<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Hashids\Hashids;

class Legalisir_Test extends TestCase
{
    use DatabaseTransactions;

    public function test_halaman_legalisir(): void
    {
        $response = $this->get('/legalisir');

        $response->assertStatus(302);
    }

    public function test_pembuatan_legalisir_ijazah_kirim(): void
    {
        $this->withoutExceptionHandling();

        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'mahasiswa@gmail.com',
            'password' => bcrypt('password'),
            'prd_id' => 1,
            'nama' => 'Deborah Jacobi',
        ]);

        $this->actingAs($user);

        $file_ijazah = UploadedFile::fake()->create('file_ijazah.pdf', 100, 'application/pdf');

        $tanggal_file = Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d_His');
        $nama_ijazah = 'Ijazah_' . str_replace(' ', '_', $user->nama) . '_' . $user->nim_nip . '_' . $tanggal_file . '.pdf';

        $data = [
            'ambil' => 'dikirim',
            'jenis_lgl' => 'ijazah',
            'file_ijazah' => $file_ijazah,
            'keperluan' => 'Ada slametan',
            'tgl_lulus' => $faker->date('Y-m-d'),
            'almt_kirim' => $faker->address(),
            'kcmt_kirim' => $faker->city(),
            'klh_kirim' => $faker->city(),
            'kdps_kirim' => $faker->randomNumber(4, true),
            'kota_kirim' => $faker->city(),
        ];

        $response = $this->post('/legalisir/create', $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('legalisir.index'));

        $this->assertDatabaseHas('legalisir', [
            'users_id' => $user->id,
            'file_ijazah' => $nama_ijazah,
            'ambil' => 'dikirim',
            'jenis_lgl' => 'ijazah',
            'keperluan' => $data['keperluan'],
            'tgl_lulus' => $data['tgl_lulus'],
            'almt_kirim' => $data['almt_kirim'],
            'kcmt_kirim' => $data['kcmt_kirim'],
            'klh_kirim' => $data['klh_kirim'],
            'kdps_kirim' => $data['kdps_kirim'],
            'kota_kirim' => $data['kota_kirim'],
        ]);
    }

    public function test_pembuatan_legalisir_traskrip_kirim(): void
    {
        $this->withoutExceptionHandling();

        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'mahasiswa@gmail.com',
            'password' => bcrypt('password'),
            'prd_id' => 1,
            'nama' => 'Deborah Jacobi',
        ]);

        $this->actingAs($user);

        $file_transkrip = UploadedFile::fake()->create('file_transkrip.pdf', 100, 'application/pdf');

        $tanggal_file = Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d_His');
        $nama_transkrip = 'Transkrip_' . str_replace(' ', '_', $user->nama) . '_' . $user->nim_nip . '_' . $tanggal_file . '.pdf';

        $data = [
            'ambil' => 'ditempat',
            'jenis_lgl' => 'transkrip',
            'file_transkrip' => $file_transkrip,
            'keperluan' => 'Ada slametan',
            'tgl_lulus' => $faker->date('Y-m-d'),
            'almt_kirim' => $faker->address(),
            'kcmt_kirim' => $faker->city(),
            'klh_kirim' => $faker->city(),
            'kdps_kirim' => $faker->randomNumber(4, true),
            'kota_kirim' => $faker->city(),
        ];

        $response = $this->post('/legalisir/create', $data);

        $response->assertStatus(302);
        $response->assertRedirect(route('legalisir.index'));

        $this->assertDatabaseHas('legalisir', [
            'users_id' => $user->id,
            'file_transkrip' => $nama_transkrip,
            'ambil' => 'ditempat',
            'jenis_lgl' => 'transkrip',
        ]);
    }

    public function test_gagal_pembuatan_legalisir_salah_format(): void
    {
        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'mahasiswa@gmail.com',
            'password' => bcrypt('password'),
            'prd_id' => 1,
        ]);

        $this->actingAs($user);

        $file_ijazah = UploadedFile::fake()->create('file_ijazah.png', 100, 'image/png');
        $file_transkrip = UploadedFile::fake()->create('file_transkrip.png', 100, 'image/png');

        try {
            $response = $this->post('/legalisir/create', [
                'file_ijazah' => $file_ijazah,
                'file_transkrip' => $file_transkrip,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            dd($e->errors());
        }

        $response->assertStatus(302);

        $response->assertSessionHasErrors(['file_ijazah', 'file_transkrip']);

        $this->assertDatabaseMissing('legalisir', [
            'users_id' => $user->id,
            'jenis_lgl' => $faker->randomElement(['ijazah' ,'transkrip', 'ijazah_transkrip']),
            'ambil' => $faker->randomElement(['ditempat' ,'dikirim']),
        ]);
    }

    public function test_view_halaman_edit_ijazah_kirim(): void
    {
        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'mahasiswa@gmail.com',
            'password' => bcrypt('mountain082'),
            'role' => 'mahasiswa',
            'prd_id' => 1,
        ]);

        $this->actingAs($user);
        $hashids = new Hashids('nilai-salt-unik-anda-di-sini', 7);
        $surat = DB::table('legalisir')->insertGetId([
            'ambil' => 'dikirim',
            'jenis_lgl' => 'ijazah',
            'file_ijazah' => 'file_ijazah.pdf',
            'keperluan' => 'Ada slametan',
            'tgl_lulus' => $faker->date('Y-m-d'),
            'almt_kirim' => $faker->address(),
            'kcmt_kirim' => $faker->city(),
            'klh_kirim' => $faker->city(),
            'kdps_kirim' => $faker->randomNumber(4, true),
            'kota_kirim' => $faker->city(),
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $encodedId = $hashids->encode($surat);
        $response = $this->get("/legalisir/edit/{$encodedId}");


        $response->assertStatus(200);

        $response->assertSee(['dikirim', 'ijazah']);
    }

    public function test_view_halaman_edit_transkrip_ditempat(): void
    {
        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'mahasiswa@gmail.com',
            'password' => bcrypt('mountain082'),
            'role' => 'mahasiswa',
            'prd_id' => 1,
        ]);

        $this->actingAs($user);
        $hashids = new Hashids('nilai-salt-unik-anda-di-sini', 7);
        $surat = DB::table('legalisir')->insertGetId([
            'keperluan' => 'Ada slametan',
            'tgl_lulus' => $faker->date('Y-m-d'),
            'ambil' => 'ditempat',
            'jenis_lgl' => 'transkrip',
            'file_ijazah' => 'file_ijazah.pdf',
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $encodedId = $hashids->encode($surat);
        $response = $this->get("/legalisir/edit/{$encodedId}");

        $response->assertStatus(200);

        $response->assertSee(['ditempat', 'transkrip']);
    }

    public function test_update_ijazah_kirim(): void
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

        $surat = DB::table('legalisir')->insertGetId([
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'ambil' => 'dikirim',
            'jenis_lgl' => 'ijazah',
            'file_ijazah' => 'file_ijazah.pdf',
            'keperluan' => 'Ada slametan',
            'tgl_lulus' => $faker->date('Y-m-d'),
            'almt_kirim' => $faker->address(),
            'kcmt_kirim' => $faker->city(),
            'klh_kirim' => $faker->city(),
            'kdps_kirim' => $faker->randomNumber(4, true),
            'kota_kirim' => $faker->city(),
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $file_ijazah = UploadedFile::fake()->create('file_ijazah.pdf', 100, 'application/pdf');

        $response = $this->post("/legalisir/update/{$surat}", [
            'file_ijazah' => $file_ijazah,
            'ambil' => 'dikirim',
            'jenis_lgl' => 'ijazah',
            'keperluan' => 'Berpegian',
            'kcmt_kirim' => 'Wlingi',
            'tgl_lulus' => '2024-11-02',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/legalisir');
        $tanggal_file = Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d_His');

        $formatted_ijazah = 'Ijazah_' . str_replace(' ', '_', $user->nama) . '_' . $user->nim_nip . '_' . $tanggal_file . '.pdf';

        $this->assertDatabaseHas('legalisir', [
            'id' => $surat,
            'tgl_lulus' => '2024-11-02',
            'kcmt_kirim' => 'Wlingi',
            'keperluan' => 'Berpegian',
            'file_ijazah' => $formatted_ijazah,
        ]);
    }

    public function test_gagal_update_ijazah_karena_data_kurang(): void
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

        $surat = DB::table('legalisir')->insertGetId([
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'ambil' => 'dikirim',
            'jenis_lgl' => 'ijazah',
            'file_ijazah' => 'file_ijazah.pdf',
            'keperluan' => 'Ada slametan',
            'tgl_lulus' => $faker->date('Y-m-d'),
            'almt_kirim' => $faker->address(),
            'kcmt_kirim' => $faker->city(),
            'klh_kirim' => $faker->city(),
            'kdps_kirim' => $faker->randomNumber(4, true),
            'kota_kirim' => $faker->city(),
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $file_ijazah = UploadedFile::fake()->create('file_ijazah.pdf', 100, 'application/pdf');

        try {
            $response = $this->post("/legalisir/update/{$surat}", [
                'file_ijazah' => $file_ijazah,
                'ambil' => 'dikirim',
                'jenis_lgl' => 'ijazah',
                'kcmt_kirim' => 'Wlingi',
                'tgl_lulus' => '2024-11-02',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertEquals('Kolom keperluan wajib diisi', $e->validator->errors()->first('keperluan'));
            return;
        }
        $response->assertStatus(302);
    }

    public function test_update_transkrip_ditempat(): void
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

        $surat = DB::table('legalisir')->insertGetId([
            'users_id' => $user->id,
            'prd_id' => $user->prd_id,
            'ambil' => 'ditempat',
            'jenis_lgl' => 'transkrip',
            'file_transkrip' => 'file_transkrip.pdf',
            'keperluan' => 'Ada slametan',
            'tgl_lulus' => $faker->date('Y-m-d'),
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $file_transkrip = UploadedFile::fake()->create('file_transkrip.pdf', 100, 'application/pdf');

        $response = $this->post("/legalisir/update/{$surat}", [
            'file_transkrip' => $file_transkrip,
            'ambil' => 'ditempat',
            'jenis_lgl' => 'transkrip',
            'keperluan' => 'Belajar S2',
            'tgl_lulus' => '2022-10-03',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/legalisir');
        $tanggal_file = Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d_His');

        $formatted_transkirp = 'Transkrip_' . str_replace(' ', '_', $user->nama) . '_' . $user->nim_nip . '_' . $tanggal_file . '.pdf';

        $this->assertDatabaseHas('legalisir', [
            'id' => $surat,
            'keperluan' => 'Belajar S2',
            'tgl_lulus' => '2022-10-03',
            'file_transkrip' => $formatted_transkirp,
        ]);
    }

    public function test_download_legalisir_ijazah_kirim()
    {
        $id = 6;

        $response = $this->get("/legalisir/admin/dikirim/ijazah/download/{$id}");

        $response->assertStatus(302);
    }

    public function test_download_srt_transkrip_ditempat()
    {
        $id = 4;

        $response = $this->get("/legalisir/admin/ditempat/transkrip/download/$id}");

        $response->assertStatus(302);
    }

    public function test_pemberian_resi_ijazah_kirim()
    {
        $admin = \App\Models\User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        $surat = \App\Models\legalisir::factory()->create([
            'no_resi' => '-',
            'role_surat' => 'admin',
        ]);

        $response = $this->post("/legalisir/admin/dikirim/ijazah/no_resi/{$surat->id}", [
            'no_resi' => 'Resi No 213127841',
        ]);

        $response->assertRedirect(route('legalisir_admin.admin_dikirim_ijazah'));
        $response->assertSessionHas('success', 'No resi telah dikirimkan');

        $this->assertDatabaseHas('legalisir', [
            'id' => $surat->id,
            'no_resi' => 'Resi No 213127841',
            'role_surat' => 'mahasiswa',
        ]);
    }

    public function test_pemberian_info_diambil_transkrip()
    {
        $admin = \App\Models\User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        $surat = \App\Models\legalisir::factory()->create([
            'no_resi' => '-',
            'role_surat' => 'admin',
        ]);

        $response = $this->post("/legalisir/admin/ditempat/transkrip/no_resi/{$surat->id}", [
            'no_resi' => 'Legalisir dapat diambil',
        ]);

        $response->assertRedirect(route('legalisir_admin.admin_ditempat_transkrip'));
        $response->assertSessionHas('success', 'Informasi mahasiswa dapat mengambil legalisir telah dikirimkan');

        $this->assertDatabaseHas('legalisir', [
            'id' => $surat->id,
            'no_resi' => 'Legalisir dapat diambil',
            'role_surat' => 'mahasiswa',
        ]);
    }

    public function test_view_halaman_ijazah_kirim(): void
    {
        $response = $this->get('/legalisir/admin/dikirim/ijazah');

        $response->assertStatus(302);
    }

    public function test_view_halaman_transkrip_ditempat(): void
    {
        $response = $this->get('/legalisir/admin/ditempat/transkrip');

        $response->assertStatus(302);
    }

    public function test_cek_kirim_ijazah()
    {
        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($user);

        $suratId = DB::table('legalisir')->insertGetId([
            'users_id' => $user->id,
            'ambil' => 'dikirim',
            'jenis_lgl' => 'ijazah',
            'file_ijazah' => $faker->name(),
            'keperluan' => 'Ada slametan',
            'tgl_lulus' => $faker->date('Y-m-d'),
            'tanggal_surat' => now()->format('Y-m-d'),
            'role_surat' => 'admin',
        ]);

        $response = $this->get("/legalisir/admin/dikirim/ijazah/cek_legal/{$suratId}");

        $response->assertStatus(500);
    }

    public function test_setuju_ijazah_kirim()
    {
        $admin = \App\Models\User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        $surat = \App\Models\legalisir::factory()->create([
            'role_surat' => 'admin',
        ]);

        $response = $this->post("/legalisir/admin/dikirim/ijazah/cek_legal/setuju/{$surat->id}", [
            'no_resi' => '-',
        ]);

        $response->assertRedirect(route('legalisir_admin.admin_dikirim_ijazah'));
        $response->assertSessionHas('success', 'Legalisir berhasil disetujui dan dilanjutkan ke supervisor akademik');

        $this->assertDatabaseHas('legalisir', [
            'id' => $surat->id,
            'role_surat' => 'supervisor_akd',
        ]);
    }

    public function test_tolak_ijazah_kirim()
    {
        $admin = \App\Models\User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin);


        $surat = \App\Models\legalisir::factory()->create([
            'role_surat' => 'admin',
        ]);

        $response = $this->post("/legalisir/admin/dikirim/ijazah/cek_legal/tolak/{$surat->id}", [
            'catatan_surat' => 'Dokumen tidak lengkap',
        ]);

        $response->assertRedirect(route('legalisir_admin.admin_dikirim_ijazah'));
        $response->assertSessionHas('success', 'Alasan penolakan telah dikirimkan');

        $this->assertDatabaseHas('legalisir', [
            'id' => $surat->id,
            'catatan_surat' => 'Dokumen tidak lengkap',
            'role_surat' => 'tolak',
        ]);
    }

    public function test_cek_transkrip_ditempat()
    {
        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($user);

        $suratId = \App\Models\Legalisir::create([
            'users_id' => $user->id,
            'ambil' => 'ditempat',
            'jenis_lgl' => 'ijazah',
            'file_transkrip' => 'file_transkrip.pdf',
            'keperluan' => 'Ada slametan',
            'tgl_lulus' => $faker->date('Y-m-d'),
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->get("/legalisir/admin/ditempat/transkrip/cek_legal/{$suratId}");

        $response->assertStatus(500);
    }

    public function test_setuju_transkrip_ditempat()
    {
        $admin = \App\Models\User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        $surat = \App\Models\legalisir::factory()->create([
            'role_surat' => 'admin',
        ]);

        $response = $this->post("/legalisir/admin/ditempat/transkrip/cek_legal/setuju/{$surat->id}", [
            'no_resi' => '-',
        ]);

        $response->assertRedirect(route('legalisir_admin.admin_ditempat_transkrip'));
        $response->assertSessionHas('success', 'Legalisir berhasil disetujui dan dilanjutkan ke supervisor akademik');

        $this->assertDatabaseHas('legalisir', [
            'id' => $surat->id,
            'role_surat' => 'supervisor_akd',
        ]);
    }

    public function test_tolak_transkrip_ditempat()
    {
        $admin = \App\Models\User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin);


        $surat = \App\Models\legalisir::factory()->create([
            'role_surat' => 'admin',
        ]);

        $response = $this->post("/legalisir/admin/ditempat/transkrip/cek_legal/tolak/{$surat->id}", [
            'catatan_surat' => 'Dokumen tidak lengkap',
        ]);

        $response->assertRedirect(route('legalisir_admin.admin_ditempat_transkrip'));
        $response->assertSessionHas('success', 'Alasan penolakan telah dikirimkan');

        $this->assertDatabaseHas('legalisir', [
            'id' => $surat->id,
            'catatan_surat' => 'Dokumen tidak lengkap',
            'role_surat' => 'tolak',
        ]);
    }

    public function test_cek_kirim_ijazah_oleh_sv()
    {
        $faker = \Faker\Factory::create();

        $sv = \App\Models\User::factory()->create([
            'email' => 'sv@example.com',
            'password' => bcrypt('password'),
            'role' => 'supervisor_akd',
        ]);

        $this->actingAs($sv);

        $suratId = DB::table('legalisir')->insertGetId([
            'users_id' => $sv->id,
            'ambil' => 'dikirim',
            'jenis_lgl' => 'ijazah',
            'file_ijazah' => $faker->name(),
            'keperluan' => 'Ada slametan',
            'tgl_lulus' => $faker->date('Y-m-d'),
            'tanggal_surat' => now()->format('Y-m-d'),
        ]);

        $response = $this->get("/legalisir/sv/dikirim/ijazah/cek_legal/{$suratId}");

        $response->assertStatus(500);
    }

    public function test_setuju_ijazah_kirim_oleh_sv()
    {
        $sv = \App\Models\User::factory()->create([
            'email' => 'sv@example.com',
            'password' => bcrypt('password'),
            'role' => 'supervisor_akd',
        ]);

        $this->actingAs($sv);

        $surat = \App\Models\legalisir::factory()->create([
            'role_surat' => 'supervisor_akd',
        ]);

        $response = $this->post("/legalisir/sv/dikirim/ijazah/cek_legal/setuju/{$surat->id}", [
            'no_resi' => '-',
        ]);

        $response->assertRedirect(route('legalisir_sv.sv_dikirim_ijazah'));
        $response->assertSessionHas('success', 'Legalisir berhasil disetujui');

        $this->assertDatabaseHas('legalisir', [
            'id' => $surat->id,
            'role_surat' => 'dekan',
        ]);
    }

    public function test_tolak_ijazah_kirim_oleh_sv()
    {
        $sv = \App\Models\User::factory()->create([
            'email' => 'akd@example.com',
            'password' => bcrypt('password'),
            'role' => 'supervisor_akd',
        ]);

        $this->actingAs($sv);


        $surat = \App\Models\legalisir::factory()->create([
            'role_surat' => 'supervisor_akd',
        ]);

        $response = $this->post("/legalisir/sv/dikirim/ijazah/cek_legal/tolak/{$surat->id}", [
            'catatan_surat' => 'Dokumen tidak lengkap',
        ]);

        $response->assertRedirect(route('legalisir_sv.sv_dikirim_ijazah'));
        $response->assertSessionHas('success', 'Alasan penolakan telah dikirimkan');

        $this->assertDatabaseHas('legalisir', [
            'id' => $surat->id,
            'catatan_surat' => 'Dokumen tidak lengkap',
            'role_surat' => 'tolak',
        ]);
    }

    public function test_cek_transkrip_ditempat_oleh_sv()
    {
        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'sv@example.com',
            'password' => bcrypt('password'),
            'role' => 'supervisor_akd',
        ]);

        $this->actingAs($user);

        $suratId = \App\Models\Legalisir::create([
            'users_id' => $user->id,
            'ambil' => 'ditempat',
            'jenis_lgl' => 'ijazah',
            'file_transkrip' => 'file_transkrip.pdf',
            'keperluan' => 'Ada slametan',
            'tgl_lulus' => $faker->date('Y-m-d'),
            'tanggal_surat' => Carbon::now()->format('Y-m-d'),
        ]);

        $response = $this->get("/legalisir/sv/ditempat/transkrip/cek_legal/{$suratId}");

        $response->assertStatus(500);
    }

    public function test_setuju_transkrip_ditempat_oleh_sv()
    {
        $sv = \App\Models\User::factory()->create([
            'email' => 'akd@example.com',
            'password' => bcrypt('password'),
            'role' => 'supervisor_akd',
        ]);

        $this->actingAs($sv);

        $surat = \App\Models\legalisir::factory()->create([
            'role_surat' => 'supervisor_akd',
        ]);

        $response = $this->post("/legalisir/sv/ditempat/transkrip/cek_legal/setuju/{$surat->id}", [
            'no_resi' => '-',
        ]);

        $response->assertRedirect(route('legalisir_sv.sv_ditempat_transkrip'));
        $response->assertSessionHas('success', 'Legalisir berhasil disetujui');

        $this->assertDatabaseHas('legalisir', [
            'id' => $surat->id,
            'role_surat' => 'dekan',
        ]);
    }

    public function test_tolak_transkrip_ditempat_oleh_sv()
    {
        $sv = \App\Models\User::factory()->create([
            'email' => 'akd@example.com',
            'password' => bcrypt('password'),
            'role' => 'supervisor_akd',
        ]);

        $this->actingAs($sv);


        $surat = \App\Models\legalisir::factory()->create([
            'role_surat' => 'supervisor_akd',
        ]);

        $response = $this->post("/legalisir/sv/ditempat/transkrip/cek_legal/tolak/{$surat->id}", [
            'catatan_surat' => 'Dokumen tidak lengkap',
        ]);

        $response->assertRedirect(route('legalisir_sv.sv_ditempat_transkrip'));
        $response->assertSessionHas('success', 'Alasan penolakan telah dikirimkan');

        $this->assertDatabaseHas('legalisir', [
            'id' => $surat->id,
            'catatan_surat' => 'Dokumen tidak lengkap',
            'role_surat' => 'tolak',
        ]);
    }

}
