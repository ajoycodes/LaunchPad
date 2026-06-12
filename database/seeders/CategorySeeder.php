<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Developer Tools',       'slug' => 'developer-tools',       'icon' => '🛠️'],
            ['name' => 'Productivity',           'slug' => 'productivity',           'icon' => '⚡'],
            ['name' => 'Design',                 'slug' => 'design',                 'icon' => '🎨'],
            ['name' => 'Marketing',              'slug' => 'marketing',              'icon' => '📣'],
            ['name' => 'AI & Machine Learning',  'slug' => 'ai-machine-learning',    'icon' => '🤖'],
            ['name' => 'Finance',                'slug' => 'finance',                'icon' => '💰'],
            ['name' => 'Education',              'slug' => 'education',              'icon' => '📚'],
            ['name' => 'Social',                 'slug' => 'social',                 'icon' => '💬'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
