<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        
        // Seed John Smith as a user for submissions
        $john = User::create([
            'name' => 'John Smith',
            'username' => 'john_smith',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'xp' => 1250,
            'level' => 2,
            'avatar' => null,
            'bio' => 'Environmental Activist and Community Lead.',
        ]);

        if ($admin) {
            // Admin Blog 1
            Blog::create([
                'user_id' => $admin->id,
                'title' => 'Climate Change Mitigation Strategies for 2024',
                'slug' => Str::slug('Climate Change Mitigation Strategies for 2024') . '-' . Str::random(6),
                'excerpt' => 'Exploring innovative approaches to reduce greenhouse gas emissions and combat climate change...',
                'content' => 'Exploring innovative approaches to reduce greenhouse gas emissions and combat climate change. Transitioning to renewable energy resources, implementing sustainable agricultural practices, promoting reforestation, and shifting towards electric mobility are key actions needed to keep global warming below 1.5°C.',
                'status' => 'approved',
                'published_at' => now(),
            ]);

            // Admin Blog 2 (Draft)
            Blog::create([
                'user_id' => $admin->id,
                'title' => 'Renewable Energy Solutions for Communities',
                'slug' => Str::slug('Renewable Energy Solutions for Communities') . '-' . Str::random(6),
                'excerpt' => 'How local communities can implement sustainable energy solutions...',
                'content' => 'How local communities can implement sustainable energy solutions. By utilizing shared microgrids, community solar plants, and localized battery storage systems, communities can achieve energy independence while reducing carbon emissions.',
                'status' => 'draft',
            ]);

            // Admin Blog 3
            Blog::create([
                'user_id' => $admin->id,
                'title' => 'SDG 13: Climate Action Implementation Guide',
                'slug' => Str::slug('SDG 13: Climate Action Implementation Guide') . '-' . Str::random(6),
                'excerpt' => 'A comprehensive guide to implementing UN Sustainable Development Goal 13...',
                'content' => 'A comprehensive guide to implementing UN Sustainable Development Goal 13. Highlighting key indicators, targets, and policy frameworks needed to integrate climate change measures into national policies, strategies, and planning.',
                'status' => 'approved',
                'published_at' => now()->subDays(2),
            ]);
        }

        // Seed some extra blogs to match mockup numbers if desired
        for ($i = 0; $i < 16; $i++) {
            Blog::create([
                'user_id' => $admin->id,
                'title' => 'Climate Education Series - Part ' . ($i + 1),
                'slug' => Str::slug('Climate Education Series - Part ' . ($i + 1)) . '-' . Str::random(6),
                'excerpt' => 'Educational resources for school curriculum on sustainability.',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam nec elementum diam. Phasellus mollis lectus leo, id interdum augue fermentum eu.',
                'status' => 'approved',
                'published_at' => now()->subDays($i + 3),
            ]);
        }

        for ($i = 0; $i < 2; $i++) {
            Blog::create([
                'user_id' => $admin->id,
                'title' => 'Sustainability Draft Notes ' . ($i + 1),
                'slug' => Str::slug('Sustainability Draft Notes ' . ($i + 1)) . '-' . Str::random(6),
                'excerpt' => 'Private brainstorming notes.',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam nec elementum diam. Phasellus mollis lectus leo, id interdum augue fermentum eu.',
                'status' => 'draft',
            ]);
        }

        // User submission 1 (Pending)
        Blog::create([
            'user_id' => $john->id,
            'title' => 'Ocean Plastic Pollution: A Community Response',
            'slug' => Str::slug('Ocean Plastic Pollution: A Community Response') . '-' . Str::random(6),
            'excerpt' => 'This article explores how coastal communities are coming together to address plastic pollution through innovative cleanup initiatives and policy advocacy...',
            'content' => 'This article explores how coastal communities are coming together to address plastic pollution through innovative cleanup initiatives and policy advocacy. Communities are utilizing digital platforms to coordinate beach cleanup drives, setting up local plastic recycling hubs, and campaigning to reduce single-use plastics at the retail level.',
            'status' => 'pending',
        ]);

        // User submission 2 (Pending)
        Blog::create([
            'user_id' => $john->id,
            'title' => 'Reforestation as a Carbon Sink Strategy',
            'slug' => Str::slug('Reforestation as a Carbon Sink Strategy') . '-' . Str::random(6),
            'excerpt' => 'Understanding how tree planting scales up carbon absorption...',
            'content' => 'Understanding how tree planting scales up carbon absorption. Forest preservation, planting native species, and agroforestry can act as major sinks to balance remaining emissions.',
            'status' => 'pending',
        ]);

        // User submission 3 (Pending)
        Blog::create([
            'user_id' => $john->id,
            'title' => 'Urban Commuting: Shifting to Clean Vehicles',
            'slug' => Str::slug('Urban Commuting: Shifting to Clean Vehicles') . '-' . Str::random(6),
            'excerpt' => 'How urban planning can incentivize cycling and public transit.',
            'content' => 'How urban planning can incentivize cycling and public transit. Highlighting cycling lanes, pedestrian friendly streets, and electric bus fleets.',
            'status' => 'pending',
        ]);
    }
}
