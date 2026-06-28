<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class PublishScheduledProducts extends Command
{
    protected $signature   = 'products:publish';
    protected $description = 'Publish scheduled products whose launch_date has passed';

    public function handle(): int
    {
        $count = Product::where('status', 'scheduled')
            ->where('launch_date', '<=', now())
            ->update(['status' => 'approved']);

        $this->info("Published {$count} product(s).");

        return Command::SUCCESS;
    }
}
