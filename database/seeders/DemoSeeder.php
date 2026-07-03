<?php

namespace Database\Seeders;

use App\Http\Controllers\ProductController;
use App\Models\Battle;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Comment;
use App\Models\Product;
use App\Models\ProductUpdate;
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
        User::updateOrCreate(
            ['email' => 'admin@launchpad.test'],
            [
                'name'     => 'Admin User',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'role'     => 'admin',
                'bio'      => 'Platform administrator.',
            ]
        );

        // Makers
        $makerData = [
            ['name' => 'Alice Chen',     'username' => 'alicechen',  'email' => 'alice@launchpad.test',  'bio' => 'Building tools for developers. Previously eng at a unicorn, now shipping solo.'],
            ['name' => 'Bob Torres',     'username' => 'btorres',    'email' => 'bob@launchpad.test',    'bio' => 'Indie hacker and product designer. 4 products launched, 2 acquired.'],
            ['name' => 'Clara Smith',    'username' => 'clarasmith', 'email' => 'clara@launchpad.test',  'bio' => 'Solopreneur building in public. Sharing revenue numbers monthly.'],
            ['name' => 'David Park',     'username' => 'dpark',      'email' => 'david@launchpad.test',  'bio' => 'Full-stack developer and maker. Laravel and Vue enthusiast.'],
            ['name' => 'Eva Müller',     'username' => 'evamuller',  'email' => 'eva@launchpad.test',    'bio' => 'AI researcher turned product maker. Making ML useful for normal people.'],
            ['name' => 'Felix Wright',   'username' => 'felixw',     'email' => 'felix@launchpad.test',  'bio' => 'Designer who codes. Obsessed with tiny details and fast load times.'],
            ['name' => 'Grace Liu',      'username' => 'graceliu',   'email' => 'grace@launchpad.test',  'bio' => 'Ex-PM building the tools I always wished existed.'],
            ['name' => 'Hassan Omar',    'username' => 'hassano',    'email' => 'hassan@launchpad.test', 'bio' => 'Bootstrapped to $10k MRR. Now helping others do the same.'],
            ['name' => 'Ines Rocha',     'username' => 'inesrocha',  'email' => 'ines@launchpad.test',   'bio' => 'Frontend engineer by day, maker by night.'],
            ['name' => 'Jonas Berg',     'username' => 'jonasberg',  'email' => 'jonas@launchpad.test',  'bio' => 'Building calm software. No VC, no growth hacks, just useful tools.'],
        ];

        $makers = [];
        foreach ($makerData as $md) {
            $makers[] = User::updateOrCreate(
                ['email' => $md['email']],
                array_merge($md, ['password' => Hash::make('password'), 'role' => 'maker'])
            );
        }

        // Hunters — the upvoting crowd
        $firstNames = ['Sam', 'Jordan', 'Riley', 'Casey', 'Morgan', 'Quinn', 'Avery', 'Reese', 'Skyler', 'Drew', 'Kai', 'Noor', 'Liam', 'Maya', 'Owen', 'Zara', 'Theo', 'Lena', 'Ravi', 'Yuki', 'Omar', 'Pia', 'Nils', 'Tara', 'Igor', 'Wren', 'Cole', 'Dana', 'Eli', 'Faye', 'Gus', 'Hana', 'Ivan', 'Jade', 'Kian', 'Lola', 'Milo', 'Nina', 'Otis', 'Page', 'Remy', 'Sage', 'Tess', 'Umar', 'Vera', 'Wade', 'Xena', 'Yara', 'Zane', 'Beau'];
        $hunters = [];
        foreach ($firstNames as $i => $fn) {
            $username  = strtolower($fn) . 'hunts';
            $hunters[] = User::updateOrCreate(
                ['email' => $username . '@launchpad.test'],
                [
                    'name'     => $fn . ' ' . chr(65 + ($i % 26)) . '.',
                    'username' => $username,
                    'password' => Hash::make('password'),
                    'role'     => 'hunter',
                ]
            );
        }

        $allVoters  = collect($makers)->merge($hunters);
        $categories = Category::all()->keyBy('slug');
        $tagIds     = Tag::pluck('id');

        // Products: [name, tagline, category, maker_idx, upvotes, days_ago]
        // days_ago 0 = launching today, fractional spread keeps "Today" tab alive
        $productData = [
            // Launching TODAY
            ['ShipFast',         'Deploy your side project in under five minutes',          'developer-tools',     0, 42, 0],
            ['MindMeld',         'AI meeting notes that actually capture decisions',        'ai-machine-learning', 4, 38, 0],
            ['InboxZeroed',      'Email triage that learns what you ignore',                'productivity',        6, 31, 0],
            ['Palettear',        'Generate accessible color palettes from a single hue',    'design',              5, 27, 0],
            ['GrowthLoop',       'Referral programs for bootstrapped SaaS',                 'marketing',           7, 22, 0],
            ['StudyBuddy AI',    'Flashcards that adapt to how you forget',                 'education',           4, 17, 0],
            ['CoinTrackr',       'Crypto portfolio tracking without the noise',             'finance',             3, 12, 0],
            ['Threadly',         'Schedule and analyze your social threads',                'social',              8, 9,  0],
            // This week
            ['DevSync',          'Real-time code collaboration for teams',                  'developer-tools',     0, 87, 1],
            ['PromptKit',        'A prompt engineering toolkit for LLM builders',           'ai-machine-learning', 4, 74, 2],
            ['FocusFlow',        'Deep work sessions with gentle accountability',           'productivity',        1, 65, 2],
            ['PixelDraft',       'Design handoffs that actually work',                      'design',              1, 58, 3],
            ['LaunchMetrics',    'Track your indie product across 12 platforms',            'marketing',           2, 51, 3],
            ['ScholarAI',        'AI-powered research assistant for students',              'education',           4, 49, 4],
            ['BudgetHero',       'Personal finance without the spreadsheets',               'finance',             3, 43, 4],
            ['ThreadStack',      'Turn your tweets into long-form articles',                'social',              0, 38, 5],
            ['APIForge',         'Generate typed API clients from OpenAPI specs',           'developer-tools',     3, 36, 5],
            ['NoteStream',       'Capture thoughts anywhere, resurface them later',         'productivity',        2, 32, 6],
            ['GradientLab',      'Beautiful CSS gradients with one click',                  'design',              5, 29, 6],
            // Older (all time)
            ['CopyAI Studio',    'Marketing copy that converts, instantly',                 'marketing',           2, 64, 9],
            ['QuizForge',        'Create adaptive quizzes from any document',               'education',           4, 58, 11],
            ['TaxPilot',         'Self-employed tax prep made simple',                      'finance',             3, 54, 13],
            ['CircleSpace',      'Build niche communities without Big Social',              'social',              0, 71, 15],
            ['CodeReview.ai',    'Async code review powered by AI',                         'developer-tools',     3, 88, 17],
            ['VoiceMemo Pro',    'Transcribe and summarize your voice notes',               'productivity',        2, 45, 19],
            ['SocialSync',       'Schedule across 8 platforms from one inbox',              'marketing',           0, 39, 21],
            ['DiffEngine',       'Spot model drift with visual diff charts',                'ai-machine-learning', 4, 47, 23],
            ['OpenLedger',       'Open-source accounting for bootstrapped startups',        'finance',             3, 52, 25],
            ['IconSmith',        'Hand-crafted icon sets, searchable by vibe',              'design',              5, 61, 27],
            ['StandupBot',       'Async standups your team will actually do',               'productivity',        6, 56, 29],
            ['MentorMatch',      'Find a senior dev mentor in your stack',                  'education',           7, 44, 31],
            ['FormForge',        'Beautiful forms with logic, no code needed',              'developer-tools',     8, 67, 33],
            ['ChurnShield',      'Predict and prevent SaaS churn before it happens',        'ai-machine-learning', 9, 59, 35],
            ['LandingLab',       'A/B test landing pages without a developer',              'marketing',           7, 48, 38],
            ['PodScript',        'Turn podcast episodes into SEO-ready articles',           'social',              8, 41, 41],
            ['SnapDocs',         'Screenshot-to-documentation in one keystroke',            'developer-tools',     9, 53, 44],
            ['HabitOS',          'A habit tracker that forgives missed days',               'productivity',        6, 49, 47],
            ['TypeScale',        'Typography systems generated from your brand font',       'design',              5, 37, 50],
            ['CrowdSense',       'Live audience polling for workshops and talks',           'education',           7, 33, 53],
            ['StockAlert',       'Price alerts with context, not just numbers',             'finance',             9, 28, 56],
        ];

        $approvedProducts = [];

        foreach ($productData as $i => $pd) {
            [$name, $tagline, $catSlug, $makerIdx, $upvoteTarget, $daysAgo] = $pd;

            $cat   = $categories->get($catSlug);
            $maker = $makers[$makerIdx];
            $slug  = Str::slug($name);

            $product = Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'user_id'          => $maker->id,
                    'category_id'      => $cat?->id,
                    'name'             => $name,
                    'tagline'          => $tagline,
                    'description'      => $this->description($name, $tagline, $maker->name),
                    'website_url'      => 'https://example.com/' . $slug,
                    'github_url'       => $i % 3 === 0 ? 'https://github.com/example/' . $slug : null,
                    'demo_url'         => $i % 4 === 0 ? 'https://demo.example.com/' . $slug : null,
                    'status'           => 'approved',
                    'is_featured'      => in_array($name, ['DevSync', 'CodeReview.ai']),
                    'is_roast_enabled' => $i % 5 === 0,
                    'launch_date'      => $daysAgo === 0
                        ? now()->subMinutes(rand(1, max(2, (int) today()->diffInMinutes(now()) - 1)))
                        : now()->subDays($daysAgo)->subHours(rand(0, 12)),
                    'views_count'      => $upvoteTarget * rand(8, 18),
                ]
            );

            // Tags: 2-4 random
            $product->tags()->syncWithoutDetaching($tagIds->random(rand(2, 4)));

            // Upvotes spread over the 30 days since launch (or since launch hour today)
            $voters = $allVoters->shuffle()->take(min($upvoteTarget, $allVoters->count()));
            foreach ($voters as $voter) {
                $maxDays = max(min($daysAgo, 29), 0);
                Upvote::firstOrCreate(
                    ['product_id' => $product->id, 'user_id' => $voter->id],
                    ['created_at' => $maxDays === 0 ? now()->subMinutes(rand(5, 500)) : now()->subDays(rand(0, $maxDays))->subMinutes(rand(0, 1200))]
                );
            }

            $approvedProducts[$slug] = $product;
        }

        // Pending products (admin review queue)
        $pendingData = [
            ['WaitlistWizard', 'Viral waitlists with built-in referral loops',   'marketing',        1],
            ['SQLTutor',       'Learn SQL by fixing real broken queries',        'education',        6],
            ['RentSplit',      'Split rent and utilities without the awkward',   'finance',          9],
        ];
        foreach ($pendingData as [$name, $tagline, $catSlug, $makerIdx]) {
            Product::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'user_id'     => $makers[$makerIdx]->id,
                    'category_id' => $categories->get($catSlug)?->id,
                    'name'        => $name,
                    'tagline'     => $tagline,
                    'description' => $this->description($name, $tagline, $makers[$makerIdx]->name),
                    'website_url' => 'https://example.com/' . Str::slug($name),
                    'status'      => 'pending',
                ]
            );
        }

        // Scheduled products (launch calendar)
        $scheduledData = [
            ['NightOwl Analytics', 'Privacy-first analytics that respect your users', 'developer-tools',     2, 2],
            ['MealPlanr',          'Weekly meal plans from what is in your fridge',   'ai-machine-learning', 5, 4],
            ['FreelanceFlow',      'Contracts, invoices, and time tracking in one',   'productivity',        8, 7],
            ['BrandBoard',         'Moodboards that export straight to design tokens','design',              1, 10],
        ];
        foreach ($scheduledData as [$name, $tagline, $catSlug, $makerIdx, $daysAhead]) {
            Product::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'user_id'     => $makers[$makerIdx]->id,
                    'category_id' => $categories->get($catSlug)?->id,
                    'name'        => $name,
                    'tagline'     => $tagline,
                    'description' => $this->description($name, $tagline, $makers[$makerIdx]->name),
                    'website_url' => 'https://example.com/' . Str::slug($name),
                    'status'      => 'scheduled',
                    'launch_date' => now()->addDays($daysAhead)->setTime(rand(8, 16), 0),
                ]
            );
        }

        // Comments with replies and roasts
        $this->seedComments($approvedProducts, $makers, $hunters);

        // Build-in-public updates
        $this->seedBuildLogs($approvedProducts);

        // Badges based on actual data
        foreach ($makers as $maker) {
            ProductController::awardBadges($maker);
        }

        // Battles: 1 active + 3 finished
        $p = array_values($approvedProducts);
        if (count($p) >= 8) {
            Battle::updateOrCreate(
                ['product_a_id' => $p[8]->id, 'product_b_id' => $p[9]->id],
                ['starts_at' => now()->subDay(), 'ends_at' => now()->addDays(2), 'votes_a' => 24, 'votes_b' => 19]
            );
            Battle::updateOrCreate(
                ['product_a_id' => $p[23]->id, 'product_b_id' => $p[16]->id],
                ['starts_at' => now()->subDays(9), 'ends_at' => now()->subDays(6), 'votes_a' => 41, 'votes_b' => 28]
            );
            Battle::updateOrCreate(
                ['product_a_id' => $p[10]->id, 'product_b_id' => $p[29]->id],
                ['starts_at' => now()->subDays(16), 'ends_at' => now()->subDays(13), 'votes_a' => 17, 'votes_b' => 33]
            );
            Battle::updateOrCreate(
                ['product_a_id' => $p[31]->id, 'product_b_id' => $p[22]->id],
                ['starts_at' => now()->subDays(23), 'ends_at' => now()->subDays(20), 'votes_a' => 26, 'votes_b' => 25]
            );
        }

        // Public collections
        $collectionData = [
            ['Must-Have Dev Tools',      0, 'The essential toolkit for modern developers.',              ['shipfast', 'devsync', 'apiforge', 'codereview-ai', 'formforge', 'snapdocs']],
            ['AI Products Worth Using',  4, 'AI tools that actually move the needle.',                   ['mindmeld', 'promptkit', 'scholarai', 'diffengine', 'churnshield']],
            ['Solo Maker Essentials',    2, 'Products built for the solo founder lifestyle.',            ['launchmetrics', 'budgethero', 'openledger', 'taxpilot', 'habitos']],
            ['Design Resources',         5, 'Tools that make your product look expensive.',              ['palettear', 'pixeldraft', 'gradientlab', 'iconsmith', 'typescale']],
            ['Marketing on a Budget',    7, 'Grow without burning cash on ads.',                         ['growthloop', 'copyai-studio', 'socialsync', 'landinglab', 'threadly']],
            ['Productivity Stack 2026',  6, 'My personal stack for getting things done.',                ['inboxzeroed', 'focusflow', 'notestream', 'standupbot', 'voicememo-pro']],
        ];

        foreach ($collectionData as [$name, $userIdx, $desc, $slugs]) {
            $collection = Collection::updateOrCreate(
                ['user_id' => $makers[$userIdx]->id, 'name' => $name],
                ['slug' => Str::slug($name), 'description' => $desc, 'is_public' => true]
            );

            $ids = collect($slugs)->map(fn ($s) => $approvedProducts[$s]->id ?? null)->filter();
            $collection->products()->syncWithoutDetaching($ids);

            // Followers
            $followers = collect($hunters)->shuffle()->take(rand(4, 14))->pluck('id');
            $collection->followers()->syncWithoutDetaching($followers);
        }
    }

    private function description(string $name, string $tagline, string $makerName): string
    {
        return "**{$name}** — {$tagline}.\n\n"
            . "We built {$name} because we kept running into the same problem ourselves and none of the existing tools quite fit. "
            . "It is fast, focused, and does one thing really well.\n\n"
            . "**What you get:**\n"
            . "- Set up in minutes, no credit card required\n"
            . "- Works with the tools you already use\n"
            . "- Honest pricing that scales with you\n\n"
            . "Built by {$makerName}. We ship updates weekly and build in public — feedback from the LaunchPad community shapes the roadmap.";
    }

    private function seedComments(array $products, array $makers, array $hunters): void
    {
        $praise = [
            'This is exactly what I was looking for. Great job!',
            'How does this compare to existing solutions in the space?',
            'I\'ve been using this for a week and it\'s already saved me hours.',
            'The onboarding is super smooth. Well done!',
            'Would love to see a dark mode added.',
            'Congrats on the launch! The landing page is gorgeous.',
            'Just signed up. The free tier is surprisingly generous.',
            'What\'s the tech stack behind this?',
            'Solid execution. Bookmarked for my next project.',
            'Any plans for a mobile app?',
            'The demo video sold me instantly. Nice work.',
            'How are you handling data privacy?',
            'Finally someone built this properly. Instant upvote.',
            'Been following your build-in-public journey. Proud to see it live!',
            'Pricing seems fair for what you get. Subscribed.',
        ];

        $replies = [
            'Thanks so much! Dark mode is on the roadmap for next month.',
            'Great question — we focus on speed and simplicity over feature count.',
            'Appreciate it! Let me know if you hit any rough edges.',
            'Laravel + Vanilla JS, keeping it boring and reliable.',
            'Mobile app is coming in Q3, web-first for now.',
        ];

        $roasts = [
            'Honest feedback: the pricing page took 4 seconds to load for me. Worth optimizing.',
            'The signup flow asks for too much info upfront. I almost bounced.',
            'Love the idea but the empty dashboard after signup is confusing. Show me a demo state!',
        ];

        $hunterPool = collect($hunters);
        $idx        = 0;

        foreach ($products as $product) {
            // More comments for higher-upvoted products
            $count = min(8, max(2, intdiv($product->views_count, 200)));

            for ($c = 0; $c < $count; $c++) {
                $commenter = $hunterPool[($idx + $c * 7) % $hunterPool->count()];
                $comment   = Comment::firstOrCreate(
                    ['product_id' => $product->id, 'user_id' => $commenter->id, 'parent_id' => null, 'is_roast' => false],
                    ['body' => $praise[($idx + $c) % count($praise)], 'created_at' => now()->subDays(rand(0, 6))->subHours(rand(0, 20))]
                );

                // Maker replies to roughly every third comment
                if ($c % 3 === 0) {
                    Comment::firstOrCreate(
                        ['product_id' => $product->id, 'user_id' => $product->user_id, 'parent_id' => $comment->id],
                        ['body' => $replies[$c % count($replies)], 'is_roast' => false, 'created_at' => now()->subDays(rand(0, 5))]
                    );
                }
            }

            // Roast comments on roast-enabled products
            if ($product->is_roast_enabled) {
                foreach (array_slice($roasts, 0, 2) as $r => $roast) {
                    Comment::firstOrCreate(
                        ['product_id' => $product->id, 'user_id' => $hunterPool[($idx + $r * 11) % $hunterPool->count()]->id, 'is_roast' => true],
                        ['body' => $roast, 'created_at' => now()->subDays(rand(0, 4))]
                    );
                }
            }

            $idx++;
        }
    }

    private function seedBuildLogs(array $products): void
    {
        $updates = [
            'Shipped v1.2 today — bulk export and a 40% faster dashboard.',
            'Crossed 500 signups this week. Thank you all for the support!',
            'Refactored the entire billing flow based on your feedback.',
            'New integration live: connect your Slack workspace in one click.',
            'Fixed the timezone bug a bunch of you reported. Sorry about that!',
            'Just published our public roadmap. Vote on what we build next.',
        ];

        $i = 0;
        foreach ($products as $product) {
            // Build logs for roughly half the products
            if ($i % 2 === 0) {
                $n = ($i % 3) + 1;
                for ($u = 0; $u < $n; $u++) {
                    ProductUpdate::firstOrCreate(
                        ['product_id' => $product->id, 'user_id' => $product->user_id, 'body' => $updates[($i + $u) % count($updates)]],
                        ['created_at' => now()->subDays($u * 4 + rand(0, 3))]
                    );
                }
            }
            $i++;
        }
    }
}
