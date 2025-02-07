<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class Supervisor_Test extends TestCase
{
    use DatabaseTransactions;

    public function test_view_halaman_salah_satu_sv(): void
    {
        $response = $this->get('/supervisor_akd');

        $response->assertStatus(302);
    }

    public function test_view_halaman_manajemen_admin(): void
    {
        $response = $this->get('/supervisor_akd/manage_admin');

        $response->assertStatus(302);
    }

    public function test_membuat_akun_admin(): void
    {
        $this->withoutExceptionHandling();
        $faker = \Faker\Factory::create();

        $sv = \App\Models\User::factory()->create([
            'email' => 'akd@example.com',
            'password' => bcrypt('password'),
            'role' => 'supervisor_akd',
        ]);

        $this->actingAs($sv);

        $response = $this->post('/supervisor_akd/manage_admin/create', [
            'email' => $faker->unique()->safeEmail,
            'nama' => $faker->name,
            'nim_nip' => $faker->unique()->numerify('##########'),
            'role' => 'admin',
            'password' => 'mountain082',
        ]);

        $response->assertStatus(302);
    }

    public function test_gagal_membuat_admin_karena_tidak_memasukkan_password(): void
    {
        $this->withoutExceptionHandling();

        $faker = \Faker\Factory::create();

        $sv = \App\Models\User::factory()->create([
            'email' => 'akd@example.com',
            'password' => bcrypt('password'),
            'role' => 'supervisor_akd',
        ]);

        $this->actingAs($sv);

        try {
            $this->post('/supervisor_akd/manage_admin/create', [
                'email' => $faker->unique()->safeEmail,
                'nama' => $faker->name,
                'nim_nip' => $faker->unique()->numerify('##########'),
                'role' => 'admin',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertEquals('Password wajib diisi', $e->validator->errors()->first('password'));
            return;
        }

        $this->fail('ValidationException for password was not thrown.');
    }

    public function test_update_akun_admin(): void
    {
        $this->withoutExceptionHandling();

        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'akd@gmail.com',
            'password' => bcrypt('mountain082'),
            'role' => 'supervisor_akd',
        ]);
        $this->actingAs($user);

        $sv = DB::table('users')->insertGetId([
            'nama' => $faker->name,
            'nim_nip' => $faker->unique()->numerify('######'),
            'email' => $faker->unique()->safeEmail,
            'password' => Hash::make('mountain082'),
            'role' => 'admin',
        ]);

        $newEmail = $faker->unique()->safeEmail;

        $response = $this->post("/supervisor_akd/manage_admin/edit/{$sv}", [
            'nama' => $faker->unique()->name,
            'nim_nip' => $faker->unique()->numerify('######'),
            'email' => $newEmail,
            'password' => Hash::make('12345678'),
        ]);

        $response->assertStatus(302);

        $this->assertDatabaseHas('users', [
            'id' => $sv,
            'email' => $newEmail,
        ]);
    }

    public function test_hapus_permanent_akun_admin()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'akd@gmail.com',
            'password' => bcrypt('mountain082'),
            'role' => 'supervisor_akd',
        ]);
        $this->actingAs($user);

        $admin = \App\Models\User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->post("/supervisor_akd/manage_admin/delete/{$admin->id}");

        $response->assertRedirect('/');
        $response->assertSessionHas('success', 'Data admin berhasil dihapuskan');

        $this->assertDatabaseMissing('users', [
            'id' => $admin->id,
        ]);
    }
}
