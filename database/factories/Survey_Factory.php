<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class Survey_Factory extends Factory
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
            'feedback' => $this->faker->sentence(),
            'rating' => $this->faker->randomElement(['sangat_puas' ,'puas', 'netral', 'kurang_puas', 'tidak_puas']),
            'tanggal_survey' => $this->faker->date('Y-m-d'),
        ];
    }
}
