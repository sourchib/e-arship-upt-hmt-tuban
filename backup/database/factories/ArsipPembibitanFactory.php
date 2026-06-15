<?php

namespace Database\Factories;

use App\Models\ArsipPembibitan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArsipPembibitanFactory extends Factory
{
    protected $model = ArsipPembibitan::class;

    public function definition(): array
    {
        return [
            'kode' => fake()->unique()->numerify('AP-###'),
            'jenis_ternak' => fake()->randomElement(['Sapi', 'Kambing', 'Domba']),
            'jumlah' => fake()->numberBetween(1, 100),
            'umur' => fake()->numberBetween(1, 60) . ' Bulan',
            'tujuan' => fake()->city(),
            'tanggal' => fake()->date(),
            'status' => fake()->randomElement(['Terdistribusi', 'Proses']),
            'created_by' => User::factory(),
        ];
    }
}
