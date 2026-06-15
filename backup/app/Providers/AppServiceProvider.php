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
        // Penyesuaian untuk struktur folder webapp di dalam htdocs
        $this->app->bind('path.public', function() {
            return base_path('../');
        });
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
