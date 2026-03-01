<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 10 users
        $users = \App\Models\User::factory(10)->create();

        // Create a default admin user
        \App\Models\User::factory()->create([
            'nama' => 'Administrator',
            'email' => 'admin@earsip.id',
            'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
            'role' => 'Admin',
            'status' => 'Aktif',
        ]);

        // Create dummy data for other tables
        \App\Models\SuratMasuk::factory(20)->create();
        \App\Models\SuratKeluar::factory(20)->create();
        \App\Models\ArsipPembibitan::factory(15)->create();
        \App\Models\ArsipHijauan::factory(15)->create();
        \App\Models\Dokumen::factory(25)->create();
        \App\Models\LogAktivitas::factory(50)->create();
    }
}
