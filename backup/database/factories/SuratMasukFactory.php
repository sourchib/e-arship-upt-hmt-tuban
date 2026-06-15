<?php

namespace Database\Factories;

use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SuratMasukFactory extends Factory
{
    protected $model = SuratMasuk::class;

    public function definition(): array
    {
        return [
            'nomor_surat' => fake()->unique()->numerify('SM/###/').fake()->year(),
            'pengirim' => fake()->company(),
            'perihal' => fake()->sentence(),
            'tanggal_surat' => fake()->date(),
            'tanggal_terima' => fake()->date(),
            'prioritas' => fake()->randomElement(['Tinggi', 'Sedang', 'Rendah']),
            'status' => fake()->randomElement(['Pending', 'Diproses', 'Terarsip']),
            'file_path' => 'uploads/surat_masuk/dummy.pdf',
            'keterangan' => fake()->paragraph(),
            'created_by' => User::factory(),
        ];
    }
}
