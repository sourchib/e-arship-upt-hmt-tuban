<?php

namespace Database\Factories;

use App\Models\Dokumen;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DokumenFactory extends Factory
{
    protected $model = Dokumen::class;

    public function definition(): array
    {
        return [
            'nama' => fake()->word() . '.pdf',
            'kategori' => fake()->randomElement(['Laporan', 'SK', 'Nota Dinas', 'Lainnya']),
            'file_path' => 'uploads/dokumen/dummy.pdf',
            'ukuran' => fake()->numberBetween(1024, 1024 * 1024 * 10), // 1KB to 10MB
            'mime_type' => 'application/pdf',
            'tanggal_upload' => fake()->date(),
            'deskripsi' => fake()->sentence(),
            'download_counter' => fake()->numberBetween(0, 100),
            'uploaded_by' => User::factory(),
        ];
    }
}
