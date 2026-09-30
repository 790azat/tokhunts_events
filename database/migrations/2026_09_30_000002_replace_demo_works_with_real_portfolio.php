<?php

use App\Models\Work;
use Database\Seeders\ContentSeeder;
use Illuminate\Database\Migrations\Migration;

/**
 * Swaps the stock-photo demo works for the real Tokhunts Events portfolio (see PortfolioSeeder).
 * Works added from the admin panel are left untouched.
 */
return new class extends Migration
{
    public function up(): void
    {
        Work::whereHas('media', fn ($q) => $q->where('url', 'like', 'https://images.unsplash.com/%'))
            ->get()
            ->each->delete();

        (new ContentSeeder)->setContainer(app())->run();
    }

    public function down(): void
    {
        //
    }
};
