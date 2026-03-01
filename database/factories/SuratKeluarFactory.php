<?php

namespace Database\Factories;

use App\Models\SuratKeluar;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SuratKeluarFactory extends Factory
{
    protected $model = SuratKeluar::class;

    public function definition(): array
    {
        return [
            'nomor_surat' => fake()->unique()->numerify('SK/###/').fake()->year(),
            'tujuan' => fake()->company(),
            'perihal' => fake()->sentence(),
            'tanggal_surat' => fake()->date(),
            'tanggal_kirim' => fake()->date(),
            'prioritas' => fake()->randomElement(['Tinggi', 'Sedang', 'Rendah']),
            'status' => fake()->randomElement(['Draft', 'Terkirim', 'Selesai']),
            'file_path' => 'uploads/surat_keluar/dummy.pdf',
            'keterangan' => fake()->paragraph(),
            'created_by' => User::factory(),
        ];
    }
}
