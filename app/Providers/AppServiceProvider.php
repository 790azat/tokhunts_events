<?php

namespace App\Providers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // <x-layouts::app> / <x-layouts::admin>
        Blade::anonymousComponentPath(resource_path('views/layouts'), 'layouts');

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        $this->bootDemoDatabase();
    }

    /**
     * Demo mode for Vercel before a real database is configured: the SQLite file lives in /tmp,
     * so it is created, migrated and seeded on each cold start and its data is not permanent.
     */
    private function bootDemoDatabase(): void
    {
        $path = config('database.connections.sqlite.database');

        if (! env('VERCEL') || config('database.default') !== 'sqlite' || ! str_starts_with($path, '/tmp/') || file_exists($path)) {
            return;
        }

        touch($path);
        Artisan::call('migrate', ['--force' => true, '--seed' => true]);
    }
}
