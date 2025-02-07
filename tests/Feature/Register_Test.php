<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

use Tests\TestCase;

class Register_Test extends TestCase
{
    // use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_view_halaman_register(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Isi Data Diri');
    }

    public function test_buat_user_baru(): void
    {
        $this->withoutExceptionHandling();
        $faker = \Faker\Factory::create();

        $email = $faker->unique()->userName . '@students.undip.ac.id';

        $response = $this->post('/register/create', [
            'email' => $email,
            'nama' => $faker->name,
            'nim_nip' => $faker->unique()->numerify('##########'),
            'kota' => $faker->city,
            'tanggal_lahir' => $faker->date('Y-m-d'),
            'nama_ibu' => $faker->name('female'),
            'nowa' => $faker->phoneNumber,
            'almt_asl' => $faker->address,
            'jnjg_id' => 1,
            'prd_id' => 1,
            'dpt_id' => 1,
            'foto' => UploadedFile::fake()->image('foto.png'),
            'status' => 'mahasiswa',
            'password' => '12345678',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/non_user');
    }

    public function test_gagal_membuat_user_dengan_email_dan_nim_terduplikat(): void
    {

        $response = $this->post('/register/create', [
            'email' => 'satu@gmail.com',
            'nama' => 'Mahasiswa Baru',
            'nim_nip' => '21120120160155',
            'kota' => 'Kota Mahasiswa Baru',
            'tanggal_lahir' => '2024-08-06',
            'nama_ibu' => 'Ibu Mahasiswa Baru',
            'nowa' => '081234567891',
            'almt_asl' => 'Alamat Mahasiswa Baru',
            'jnjg_id' => 1,
            'prd_id' => 1,
            'dpt_id' => 1,
            'foto' => UploadedFile::fake()->image('foto.png'),
            'status' => 'mahasiswa',
            'password' => 'mountain082',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email', 'nim_nip']);
    }

    public function test_gagal_membuat_user_karena_data_kurang(): void
    {

        $response = $this->post('/register/create', [
            'nama' => 'Mahasiswa Baru',
            'kota' => 'Kota Mahasiswa Baru',
            'tanggal_lahir' => '2024-08-06',
            'nama_ibu' => 'Ibu Mahasiswa Baru',
            'nowa' => '081234567891',
            'almt_asl' => 'Alamat Mahasiswa Baru',
            'jnjg_id' => 1,
            'prd_id' => 1,
            'dpt_id' => 1,
            'foto' => UploadedFile::fake()->image('foto.png'),
            'status' => 'mahasiswa',
            'password' => 'mountain082',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email', 'nim_nip']);
    }
}
