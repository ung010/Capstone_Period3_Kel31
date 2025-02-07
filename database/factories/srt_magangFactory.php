<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\srt_magang>
 */
class srt_magangFactory extends Factory
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
            'ipk' => '3.50',
            'sksk' => 150,
            'semester' => $this->faker->randomDigitNotNull,
            'almt_smg' => $this->faker->address(),
            'almt_lmbg' => $this->faker->address(),
            'nama_lmbg' => $this->faker->company(),
            'jbt_lmbg' => $this->faker->jobTitle(),
            'kota_lmbg' => $this->faker->city(),
            'tanggal_surat' => $this->faker->date('Y-m-d'),
        ];
    }
}
