<?php

namespace Database\Factories;

use App\Models\srt_masih_mhw;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\srt_masih_mhw>
 */
class srt_masih_mhwFactory extends Factory
{

    protected $model = srt_masih_mhw::class;

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
            'thn_awl' => $this->faker->year,
            'thn_akh' => $this->faker->year,
            'semester' => $this->faker->randomDigitNotNull,
            'almt_smg' => $this->faker->address(),
            'tujuan_buat_srt' => $this->faker->sentence(),
            'tujuan_akhir' => $this->faker->randomElement(['manajer', 'wd']),
            'tanggal_surat' => $this->faker->date('Y-m-d'),
        ];
    }
}
