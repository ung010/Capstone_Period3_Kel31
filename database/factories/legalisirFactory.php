<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\legalisir>
 */
class legalisirFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'users_id' => \App\Models\User::factory(),
            'prd_id' => 1,
            'jenis_lgl' => $this->faker->randomElement(['ijazah', 'transkrip', 'ijazah_transkrip']),
            'ambil' => $this->faker->randomElement(['ditempat', 'dikirim']),
            'file_ijazah' => 'file_ijazah.pdf',
            'file_transkrip' => 'file_transkrip.pdf',
            'keperluan' => 'Untuk Lanjut Pendidikan',
            'tgl_lulus' => $this->faker->date('Y-m-d'),
            'tanggal_surat' => $this->faker->date('Y-m-d'),
            'almt_kirim' => $this->faker->address(),
            'kota_kirim' => $this->faker->city(),
            'kdps_kirim' => 2222,
            'kcmt_kirim' => $this->faker->randomElement(['Kecamatan A', 'Kecamatan B']),
            'klh_kirim' => $this->faker->randomElement(['Kelurahan A', 'Kelurahan B']),
            'role_surat' => $this->faker->randomElement(['mahasiswa', 'admin', 'tolak', 'supervisor_akd', 'dekan']),
        ];
    }
}
