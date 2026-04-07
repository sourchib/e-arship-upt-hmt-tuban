<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\LogAktivitas;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (auth()->check()) {
                $activities = LogAktivitas::with('user')->latest()->take(6)->get();
                
                if ($activities->isEmpty()) {
                    $activities = collect([
                        (object)[
                            'modul' => 'Sistem',
                            'deskripsi' => 'Selamat datang di E-Arsip!',
                            'created_at' => now(),
                            'user' => (object)['nama' => 'System']
                        ]
                    ]);
                }
                
                $view->with('globalActivities', $activities);
            }
        });
    }
}
