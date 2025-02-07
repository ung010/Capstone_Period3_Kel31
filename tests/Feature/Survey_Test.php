<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class Survey_Test extends TestCase
{
    use DatabaseTransactions;

    public function test_halaman_survey(): void
    {
        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'zahra@students.undip.ac.id',
            'role' => 'mahasiswa'
        ]);
        $this->actingAs($user);

        $response = $this->get('/user');

        $response->assertStatus(200);

        $response->assertSeeText('Survei Kepuasan Pengguna');
        $response->assertSeeText('Kritik dan Saran');
    }

    public function test_membuat_survey(): void
    {
        $this->withoutExceptionHandling();

        $faker = \Faker\Factory::create();

        $user = \App\Models\User::factory()->create([
            'email' => 'mahasiswa@gmail.com',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($user);

        $response = $this->post('/survey/create', [
            'users_id' => $user->id,
            'feedback' => $faker->sentence(),
            'rating' => $faker->randomElement(['sangat_puas' ,'puas', 'netral', 'kurang_puas', 'tidak_puas']),
            'tanggal_survey' => $faker->date('Y-m-d'),
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/user');
    }

    public function test_view_halaman_hasil_survey_via_admin(): void
    {
        $response = $this->get('/survey/admin');

        $response->assertStatus(302);
    }

    public function test_view_halaman_hasil_survey_via_sv_akd(): void
    {
        $response = $this->get('/survey/sv_akd');

        $response->assertStatus(302);
    }
    public function test_view_halaman_hasil_survey_via_sv_sd(): void
    {
        $response = $this->get('/survey/sv_sd');

        $response->assertStatus(302);
    }

    public function test_view_halaman_hasil_survey_via_manajer(): void
    {
        $response = $this->get('/survey/manajer');

        $response->assertStatus(302);
    }

    public function test_view_halaman_hasil_survey_via_wd1(): void
    {
        $response = $this->get('/survey/wd1');

        $response->assertStatus(302);
    }

    public function test_view_halaman_hasil_survey_via_wd2(): void
    {
        $response = $this->get('/survey/wd2');

        $response->assertStatus(302);
    }
}
