<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => $this->faker->name,
            'nim_nip' => $this->faker->unique()->numerify('######'),
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('12345678'),
            'kota' => $this->faker->city,
            'tanggal_lahir' => $this->faker->date,
            'status' => 'mahasiswa',
            'nowa' => preg_replace('/[^0-9]/', '', $this->faker->phoneNumber),
            'nama_ibu' => substr($this->faker->name, 0, 20),
            'almt_asl' => $this->faker->address,
            'prd_id' => 1,
            'role' => 'mahasiswa',
            // 'role' => $this->faker->randomElement(['mahasiswa', 'non_mahasiswa', 'del_mahasiswa']),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
