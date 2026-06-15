<?php

namespace Database\Factories;

use App\Models\ArsipHijauan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArsipHijauanFactory extends Factory
{
    protected $model = ArsipHijauan::class;

    public function definition(): array
    {
        return [
            'kode_lahan' => fake()->unique()->numerify('LH-###'),
            'jenis_hijauan' => fake()->randomElement(['Rumput Gajah', 'Odot', 'Indigofera']),
            'luas' => fake()->randomFloat(2, 0.1, 5.0),
            'produksi' => fake()->randomFloat(2, 100, 5000),
            'tanggal_panen' => fake()->date(),
            'lokasi' => 'Blok ' . fake()->randomLetter() . fake()->numberBetween(1, 10),
            'status' => fake()->randomElement(['Tersedia', 'Terdistribusi']),
            'created_by' => User::factory(),
        ];
    }
}
