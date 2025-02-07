<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WD_Test extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_view_halaman_wakil_dekan1(): void
    {
        $response = $this->get('/wd1');

        $response->assertStatus(302);
    }

    public function test_view_halaman_wakil_dekan2(): void
    {
        $response = $this->get('/wd2');

        $response->assertStatus(302);
    }

    public function test_edit_akun_wakil_dekan1(): void
    {
        $this->withoutExceptionHandling();
        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'wd1@gmail.com',
            'password' => bcrypt('mountain082'),
        ]);

        $this->actingAs($user);

        $response = $this->post('/wd1/account/update/'.$user->id, [
            'email' => $faker->unique()->safeEmail,
            'nama' => $faker->name,
            'nim_nip' => $faker->unique()->numerify('##########'),
            'password' => 'mountain081',
        ]);

        $response->assertStatus(302);
    }

    public function test_gagal_mengedit_akun_wakil_dekan_dengan_email_terduplikat(): void
    {
        $faker = \Faker\Factory::create();

        \App\Models\User::factory()->create([
            'email' => 'wd1@gmail.com',
            'password' => bcrypt('mountain082'),
            'role' => 'wd1'
        ]);

        $user = \App\Models\User::factory()->create([
            'email' => '2@gmail.com',
            'password' => bcrypt('mountain082'),
            'role' => 'wd1'
        ]);

        $this->actingAs($user);

        $data = [
            'email' => 'wd1@gmail.com',
            'nama' => $faker->name,
            'password' => 'mountain082',
            'role' => 'wd1'
        ];

        $response = $this->post('/wd1/account/update/'.$user->id, $data);

        $response->assertStatus(302);
        $response->assertInvalid('email');

        $response->assertSessionHasErrors([
            'email' => 'Email sudah digunakan, silakan masukkan Email yang lain'
        ]);
    }
}
