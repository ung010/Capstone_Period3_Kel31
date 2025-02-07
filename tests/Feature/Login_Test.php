<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class Login_Test extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_view_halaman_login(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Login');
    }

    public function test_sukses_login(): void
    {
        $dataLogin = [
            'email' => 'satu@gmail.com',
            'password' => '1234567'
        ];

        $response = $this->post('/', $dataLogin);
        $response->assertStatus(302);
        $response->assertRedirect('/user');
    }

    public function test_gagal_login_karna_email_dan_password_salah(): void
    {
        $dataLogin = [
            'email' => 'dua@gmail.com',
            'password' => 'river082'
        ];

        $response = $this->post('/', $dataLogin);

        $response->assertStatus(302);
        $response->assertRedirect('/');
        $response->assertSessionHas('errors');
    }

    public function test_logout(): void
    {
        $response = $this->get('/logout');

        $response->assertStatus(302);
    }
}
