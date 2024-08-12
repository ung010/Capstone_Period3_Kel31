<?php

namespace Database\Factories;

use App\Models\srt_mhw_asn;
use Illuminate\Database\Eloquent\Factories\Factory;

class srt_mhw_asnFactory extends Factory
{
    protected $model = srt_mhw_asn::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'users_id' => \App\Models\User::factory(),
            'prd_id' => 1,
            'dpt_id' => 1,
            'jnjg_id' => 1,
            'nama_mhw' => $this->faker->name,
            'nim_mhw' => $this->faker->numerify('##########'),
            'nowa_mhw' => $this->faker->phoneNumber,
            'thn_awl' => $this->faker->year,
            'thn_akh' => $this->faker->year,
            'semester' => $this->faker->randomDigitNotNull,
            'nama_ortu' => $this->faker->name,
            'nip_ortu' => $this->faker->numerify('##########'),
            'ins_ortu' => $this->faker->company,
            'jenjang_prodi' => $this->faker->word,
            'tanggal_surat' => $this->faker->date('Y-m-d'),
        ];
    }
}
