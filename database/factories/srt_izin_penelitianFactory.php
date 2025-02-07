<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\srt_izin_penelitian>
 */
class srt_izin_penelitianFactory extends Factory
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
            'semester' => $this->faker->randomDigitNotNull,
            'almt_lmbg' => $this->faker->address(),
            'jbt_lmbg' => $this->faker->jobTitle(),
            'kota_lmbg' => $this->faker->city(),
            'nama_lmbg' => $this->faker->company(),
            'judul_data' => $this->faker->sentence(),
            'jenis_surat' => $this->faker->randomElement(['Kerja Praktek', 'Tugas Akhir Penelitian Mahasiswa', 'Ijin Penelitian' , 'Survey' , 'Thesis', 'Disertasi']),
            'lampiran' => $this->faker->randomElement(['1 Eksemplar', '2 Eksemplar']),
            'tanggal_surat' => $this->faker->date('Y-m-d'),
        ];
    }
}
