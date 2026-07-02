<?php

namespace Database\Seeders;

use App\Models\Battle;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Comment;
use App\Models\Product;
use App\Models\Tag;
use App\Models\Upvote;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@launchpad.test'],
            [
                'name'     => 'Admin User',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'role'     => 'admin',
                'bio'      => 'Platform administrator.',
            ]
        );

        // 5 maker users
        $makers = [];
        $makerData = [
            ['name' => 'Alice Chen',    'username' => 'alicechen',   'email' => 'alice@launchpad.test',   'bio' => 'Building tools for developers.'],
            ['name' => 'Bob Torres',    'username' => 'btorres',     'email' => 'bob@launchpad.test',     'bio' => 'Indie hacker, product designer.'],
            ['name' => 'Clara Smith',   'username' => 'clarasmith',  'email' => 'clara@launchpad.test',   'bio' => 'Solopreneur building in public.'],
            ['name' => 'David Park',    'username' => 'dpark',       'email' => 'david@launchpad.test',   'bio' => 'Full-stack developer and maker.'],
            ['name' => 'Eva Müller',    'username' => 'evamuller',   'email' => 'eva@launchpad.test',     'bio' => 'AI researcher turned product maker.'],
        ];

        foreach ($makerData as $md) {
            $makers[] = User::updateOrCreate(
                ['email' => $md['email']],
                array_merge($md, ['password' => Hash::make('password'), 'role' => 'maker'])
            );
        }

        $categories = Category::all()->keyBy('slug');
        $tags       = Tag::all()->keyBy('name');

        // 20 approved products
        $productData = [
            ['name' => 'DevSync',          'tagline' => 'Real-time code collaboration for teams',         'category' => 'developer-tools',    'maker_idx' => 0, 'upvotes' => 87],
            ['name' => 'PromptKit',        'tagline' => 'A prompt engineering toolkit for LLM builders',  'category' => 'ai-machine-learning', 'maker_idx' => 4, 'upvotes' => 74],
            ['name' => 'FocusFlow',        'tagline' => 'Deep work sessions with gentle accountability',   'category' => 'productivity',        'maker_idx' => 1, 'upvotes' => 65],
            ['name' => 'PixelDraft',       'tagline' => 'Design handoffs that actually work',              'category' => 'design',              'maker_idx' => 1, 'upvotes' => 58],
            ['name' => 'LaunchMetrics',    'tagline' => 'Track your indie product across 12 platforms',    'category' => 'marketing',           'maker_idx' => 2, 'upvotes' => 51],
            ['name' => 'ScholarAI',        'tagline' => 'AI-powered research assistant for students',      'category' => 'education',           'maker_idx' => 4, 'upvotes' => 49],
            ['name' => 'BudgetHero',       'tagline' => 'Personal finance without the spreadsheets',       'category' => 'finance',             'maker_idx' => 3, 'upvotes' => 43],
            ['name' => 'ThreadStack',      'tagline' => 'Turn your tweets into long-form articles',        'category' => 'social',              'maker_idx' => 0, 'upvotes' => 38],
            ['name' => 'APIForge',         'tagline' => 'Generate typed API clients from OpenAPI specs',   'category' => 'developer-tools',    'maker_idx' => 3, 'upvotes' => 36],
            ['name' => 'NoteStream',       'tagline' => 'Capture thoughts anywhere, resurface them later', 'category' => 'productivity',        'maker_idx' => 2, 'upvotes' => 32],
            ['name' => 'GradientLab',      'tagline' => 'Beautiful CSS gradients with one click',          'category' => 'design',              'maker_idx' => 1, 'upvotes' => 29],
            ['name' => 'CopyAI',           'tagline' => 'Marketing copy that converts, instantly',         'category' => 'marketing',           'maker_idx' => 2, 'upvotes' => 27],
            ['name' => 'QuizForge',        'tagline' => 'Create adaptive quizzes from any document',       'category' => 'education',           'maker_idx' => 4, 'upvotes' => 24],
            ['name' => 'TaxPilot',         'tagline' => 'Self-employed tax prep made simple',              'category' => 'finance',             'maker_idx' => 3, 'upvotes' => 21],
            ['name' => 'CircleSpace',      'tagline' => 'Build niche communities without Big Social',      'category' => 'social',              'maker_idx' => 0, 'upvotes' => 19],
            ['name' => 'CodeReview.ai',    'tagline' => 'Async code review powered by GPT-4',              'category' => 'developer-tools',    'maker_idx' => 3, 'upvotes' => 18],
            ['name' => 'VoiceMemo Pro',    'tagline' => 'Transcribe and summarize your voice notes',       'category' => 'productivity',        'maker_idx' => 2, 'upvotes' => 16],
            ['name' => 'SocialSync',       'tagline' => 'Schedule across 8 platforms from one inbox',      'category' => 'marketing',           'maker_idx' => 0, 'upvotes' => 14],
            ['name' => 'DiffEngine',       'tagline' => 'Spot model drift with visual diff charts',        'category' => 'ai-machine-learning', 'maker_idx' => 4, 'upvotes' => 11],
            ['name' => 'OpenLedger',       'tagline' => 'Open-source accounting for bootstrapped startups','category' => 'finance',             'maker_idx' => 3, 'upvotes' => 8],
        ];

        $approvedProducts = [];

        foreach ($productData as $i => $pd) {
            $cat    = $categories->get($pd['category']);
            $maker  = $makers[$pd['maker_idx']];
            $slug   = Str::slug($pd['name']);
            $unique = Product::where('slug', $slug)->exists() ? $slug . '-' . $i : $slug;

            $product = Product::updateOrCreate(
                ['slug' => $unique],
                [
                    'user_id'     => $maker->id,
                    'category_id' => $cat?->id,
                    'name'        => $pd['name'],
                    'slug'        => $unique,
                    'tagline'     => $pd['tagline'],
                    'description' => "**{$pd['name']}** helps makers and teams {$pd['tagline']}.\n\nBuilt with love by {$maker->name}. Open to feedback from the LaunchPad community.",
                    'website_url' => 'https://example.com/' . $unique,
                    'status'      => 'approved',
                    'launch_date' => now()->subDays(rand(1, 30)),
                ]
            );

            // Create upvotes
            $upvoterIds = User::inRandomOrder()->limit($pd['upvotes'])->pluck('id');
            foreach ($upvoterIds as $uid) {
                Upvote::firstOrCreate(['product_id' => $product->id, 'user_id' => $uid]);
            }

            $approvedProducts[] = $product;
        }

        // Sample comments on first 5 products
        $commentBodies = [
            'This is exactly what I was looking for. Great job!',
            'How does this compare to existing solutions?',
            'I\'ve been using this for a week and it\'s already saved me hours.',
            'The onboarding is super smooth. Well done!',
            'Would love to see a dark mode added.',
        ];

        foreach (array_slice($approvedProducts, 0, 5) as $idx => $product) {
            Comment::firstOrCreate(
                ['product_id' => $product->id, 'user_id' => $makers[($idx + 1) % 5]->id],
                ['body' => $commentBodies[$idx], 'is_roast' => false]
            );
        }

        // 1 active battle
        if (count($approvedProducts) >= 2) {
            Battle::updateOrCreate(
                ['product_a_id' => $approvedProducts[0]->id, 'product_b_id' => $approvedProducts[1]->id],
                [
                    'starts_at' => now()->subHours(2),
                    'ends_at'   => now()->addDays(3),
                    'votes_a'   => 12,
                    'votes_b'   => 9,
                ]
            );
        }

        // 3 public collections
        $collectionData = [
            ['name' => 'Must-Have Dev Tools',    'user_idx' => 0, 'desc' => 'The essential toolkit for modern developers.'],
            ['name' => 'AI Products Worth Using','user_idx' => 4, 'desc' => 'AI tools that actually move the needle.'],
            ['name' => 'Solo Maker Essentials',  'user_idx' => 2, 'desc' => 'Products built for the solo founder lifestyle.'],
        ];

        foreach ($collectionData as $cd) {
            $maker = $makers[$cd['user_idx']];
            $slug  = Str::slug($cd['name']);

            $collection = Collection::updateOrCreate(
                ['user_id' => $maker->id, 'name' => $cd['name']],
                [
                    'slug'        => $slug,
                    'description' => $cd['desc'],
                    'is_public'   => true,
                ]
            );

            $collection->products()->syncWithoutDetaching(
                collect(array_slice($approvedProducts, 0, 4))->pluck('id')
            );
        }
    }
}
