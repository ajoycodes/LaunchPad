<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            'open-source',
            'free',
            'mobile',
            'web',
            'api',
            'saas',
            'no-code',
            'chrome-extension',
            'cli',
            'analytics',
            'automation',
            'security',
            'e-commerce',
            'community',
            'developer',
        ];

        foreach ($tags as $slug) {
            $name = ucwords(str_replace(['-'], [' '], $slug));
            Tag::firstOrCreate(['slug' => $slug], ['name' => $name, 'slug' => $slug]);
        }
    }
}
