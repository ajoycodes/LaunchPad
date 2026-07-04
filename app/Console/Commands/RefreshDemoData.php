<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class RefreshDemoData extends Command
{
    protected $signature   = 'demo:refresh';
    protected $description = 'Re-date the most recent demo launches to today so the Today feed never looks empty';

    public function handle(): int
    {
        // The 8 most recently launched approved products become "today's launches",
        // spread over the past few hours so the feed looks organic.
        $products = Product::where('status', 'approved')
            ->whereNotNull('launch_date')
            ->orderByDesc('launch_date')
            ->limit(8)
            ->get();

        foreach ($products as $product) {
            $product->update([
                'launch_date' => now()->subMinutes(rand(30, 600)),
            ]);
        }

        $this->info("Re-dated {$products->count()} products to today.");

        return self::SUCCESS;
    }
}
