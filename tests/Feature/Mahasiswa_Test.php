<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class Mahasiswa_Test extends TestCase
{

    use DatabaseTransactions;
    /**
     * A basic feature test example.
     */
    public function test_view_halaman_user_role_mahasiswa(): void
    {
        $response = $this->get('/user');

        $response->assertStatus(302);
    }

    public function test_view_halaman_my_account_user_role_mahasiswa(): void
    {
        $response = $this->get('/user/my_account');

        $response->assertStatus(302);
    }

    public function test_edit_user_role_mahasiswa(): void
    {
        $this->withoutExceptionHandling();
        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'mahasiswa@gmail.com',
            'password' => bcrypt('mountain082'),
        ]);

        $this->actingAs($user);

        $response = $this->post('/user/my_account/update', [
            'email' => $faker->unique()->safeEmail,
            'nama' => $faker->name,
            'nim_nip' => $faker->unique()->numerify('##########'),
            'kota' => $faker->city,
            'tanggal_lahir' => $faker->date('Y-m-d'),
            'nama_ibu' => $faker->name('female'),
            'nowa' => preg_replace('/[^0-9]/', '', $faker->phoneNumber),
            'almt_asl' => $faker->address,
            'prd_id' => 1,
            'password' => 'mountain082',
        ]);

        $response->assertStatus(302);
    }

    public function test_gagal_mengedit_akun_dengan_email_terduplikat(): void
    {
        $faker = \Faker\Factory::create();

        \App\Models\User::factory()->create([
            'email' => 'zahra@students.undip.ac.id'
        ]);

        $user = \App\Models\User::factory()->create([
            'email' => 'mahasiswa@gmail.com',
            'password' => bcrypt('mountain082'),
        ]);

        $this->actingAs($user);
        $response = $this->post('/user/my_account/update', [
            'email' => 'zahra@students.undip.ac.id',
            'nama' => $faker->name,
            'nim_nip' => $faker->unique()->numerify('##########'),
            'kota' => $faker->city,
            'tanggal_lahir' => $faker->date('Y-m-d'),
            'nama_ibu' => $faker->name('female'),
            'nowa' => preg_replace('/[^0-9]/', '', $faker->phoneNumber),
            'almt_asl' => $faker->address,
            'prd_id' => 1,
            'password' => 'mountain082',
        ]);

        // 4. Verifikasi hasil
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'email' => 'Email sudah digunakan, silakan masukkan Email yang lain'
        ]);
    }
}
