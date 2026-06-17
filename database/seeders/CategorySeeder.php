<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Developer Tools',       'slug' => 'developer-tools',    'icon' => 'terminal'],
            ['name' => 'Productivity',           'slug' => 'productivity',        'icon' => 'zap'],
            ['name' => 'Design',                 'slug' => 'design',              'icon' => 'pen-tool'],
            ['name' => 'Marketing',              'slug' => 'marketing',           'icon' => 'megaphone'],
            ['name' => 'AI & Machine Learning',  'slug' => 'ai-machine-learning', 'icon' => 'cpu'],
            ['name' => 'Finance',                'slug' => 'finance',             'icon' => 'dollar-sign'],
            ['name' => 'Education',              'slug' => 'education',           'icon' => 'book-open'],
            ['name' => 'Social',                 'slug' => 'social',              'icon' => 'message-circle'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
