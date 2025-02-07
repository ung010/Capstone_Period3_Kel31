<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class Non_Mahasiswa_Test extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     */
    public function test_view_halaman_user_role_non_mahasiswa(): void
    {
        $response = $this->get('/non_user');

        $response->assertStatus(302);
    }

    public function test_view_halaman_my_account_user_role_non_mahasiswa(): void
    {
        $response = $this->get('/non_user/my_account');

        $response->assertStatus(302);
    }

    public function test_edit_user_role_non_mahasiswa(): void
    {
        $this->withoutExceptionHandling();
        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'mahasiswa@gmail.com',
            'password' => bcrypt('mountain082'),
        ]);

        $this->actingAs($user);

        $response = $this->post('/non_user/my_account/update', [
            'email' => $faker->unique()->safeEmail,
            'nama' => $faker->name,
            'nim_nip' => $faker->unique()->numerify('##########'),
            'kota' => $faker->city,
            'tanggal_lahir' => $faker->date('Y-m-d'),
            'nama_ibu' => $faker->name('female'),
            'nowa' => $faker->phoneNumber,
            'almt_asl' => $faker->address,
            'prd_id' => 1,
            'password' => 'mountain082',
        ]);

        $response->assertStatus(302);
    }

    public function test_gagal_mengedit_akun_dengan_nim_terduplikat(): void
    {
        $faker = \Faker\Factory::create();

        \App\Models\User::factory()->create([
            'nim_nip' => 21120120180155
        ]);

        $user = \App\Models\User::factory()->create([
            'email' => 'mahasiswa@gmail.com',
            'password' => bcrypt('mountain082'),
            'nim_nip' => 21120120120101
        ]);

        $this->actingAs($user);

        $data = [
            'email' => $faker->unique()->safeEmail,
            'nama' => $faker->name,
            'nim_nip' => 21120120180155,
            'kota' => $faker->city,
            'tanggal_lahir' => $faker->date('Y-m-d'),
            'nama_ibu' => $faker->name('female'),
            'nowa' => $faker->phoneNumber,
            'almt_asl' => $faker->address,
            'prd_id' => 1,
            'password' => 'mountain082',
        ];

        $response = $this->post('/user/my_account/update', $data);
        $response->assertStatus(302);
        $response->assertInvalid('nim_nip');

        $response->assertSessionHasErrors([
            'nim_nip' => 'NIM sudah digunakan, silakan masukkan NIM yang lain'
        ]);
    }
}
