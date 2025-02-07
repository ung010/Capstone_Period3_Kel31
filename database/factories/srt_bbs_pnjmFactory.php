<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\srt_bbs_pnjm>
 */
class srt_bbs_pnjmFactory extends Factory
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
            'dosen_wali' => $this->faker->name(),
            'almt_smg' => $this->faker->address(),
            'tanggal_surat' => $this->faker->date('Y-m-d'),
        ];
    }
}
