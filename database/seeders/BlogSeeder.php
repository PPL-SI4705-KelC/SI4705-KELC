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
            'total_point' => 0,
            'avatar' => null,
            'bio' => 'Environmental Activist and Community Lead.',
        ]);

        // Seed Sarah Johnson
        $sarah = User::create([
            'name' => 'Sarah Johnson',
            'username' => 'sarah_johnson',
            'email' => 'sarah@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'xp' => 800,
            'level' => 1,
            'total_point' => 0,
            'avatar' => null,
            'bio' => 'Climate Researcher',
        ]);

        // Seed Michael Chen
        $michael = User::create([
            'name' => 'Michael Chen',
            'username' => 'michael_chen',
            'email' => 'michael@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'xp' => 600,
            'level' => 1,
            'total_point' => 0,
            'avatar' => null,
            'bio' => 'Sustainability Consultant',
        ]);

        if ($admin) {
            // Admin Blog 1 (Published)
            Blog::create([
                'user_id' => $admin->id,
                'title' => 'Climate Change Mitigation Strategies for 2024',
                'short_description' => 'Exploring innovative approaches to reduce greenhouse gas emissions and combat climate change.',
                'content' => 'Exploring innovative approaches to reduce greenhouse gas emissions and combat climate change. Transitioning to renewable energy resources, implementing sustainable agricultural practices, promoting reforestation, and shifting towards electric mobility are key actions needed to keep global warming below 1.5°C. This comprehensive guide covers the latest research findings and actionable strategies that individuals, communities, and governments can adopt to make a meaningful impact on our planet\'s future.',
                'category' => 'Energy',
                'tags' => 'climate change, mitigation, renewable energy, sustainability',
                'status' => 'published',
            ]);

            // Admin Blog 2 (Draft)
            Blog::create([
                'user_id' => $admin->id,
                'title' => 'Renewable Energy Solutions for Communities',
                'short_description' => 'How local communities can implement sustainable energy solutions.',
                'content' => 'How local communities can implement sustainable energy solutions. By utilizing shared microgrids, community solar plants, and localized battery storage systems, communities can achieve energy independence while reducing carbon emissions. This article explores real-world case studies from communities around the globe that have successfully transitioned to renewable energy sources.',
                'category' => 'Energy',
                'tags' => 'renewable energy, community, solar, microgrid',
                'status' => 'draft',
            ]);

            // Admin Blog 3 (Published)
            Blog::create([
                'user_id' => $admin->id,
                'title' => 'SDG 13: Climate Action Implementation Guide',
                'short_description' => 'A comprehensive guide to implementing UN Sustainable Development Goal 13.',
                'content' => 'A comprehensive guide to implementing UN Sustainable Development Goal 13. Highlighting key indicators, targets, and policy frameworks needed to integrate climate change measures into national policies, strategies, and planning. Learn how different countries are approaching climate action and what progress has been made toward meeting global climate targets.',
                'category' => 'Consumption',
                'tags' => 'SDG, climate action, policy, UN',
                'status' => 'published',
            ]);

            // Extra published blogs
            $extraTopics = [
                ['title' => 'The Impact of Fast Fashion on Carbon Emissions', 'cat' => 'Consumption', 'desc' => 'How the fashion industry contributes to global warming and what consumers can do.'],
                ['title' => 'Electric Vehicles: A Complete Guide for 2024', 'cat' => 'Transportation', 'desc' => 'Everything you need to know about switching to electric vehicles.'],
                ['title' => 'Home Energy Audit: Save Money and the Planet', 'cat' => 'Energy', 'desc' => 'Step-by-step guide to conducting an energy audit in your home.'],
                ['title' => 'The Role of Forests in Carbon Sequestration', 'cat' => 'Consumption', 'desc' => 'Understanding how forests act as carbon sinks.'],
                ['title' => 'Sustainable Public Transit Solutions', 'cat' => 'Transportation', 'desc' => 'How cities are revolutionizing public transportation.'],
            ];

            foreach ($extraTopics as $i => $topic) {
                Blog::create([
                    'user_id' => $admin->id,
                    'title' => $topic['title'],
                    'short_description' => $topic['desc'],
                    'content' => $topic['desc'] . ' Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam nec elementum diam. Phasellus mollis lectus leo, id interdum augue fermentum eu. Sed vestibulum tortor at nunc faucibus, vel posuere enim pharetra.',
                    'category' => $topic['cat'],
                    'tags' => strtolower($topic['cat']) . ', sustainability, climate',
                    'status' => 'published',
                ]);
            }

            // Extra drafts
            Blog::create([
                'user_id' => $admin->id,
                'title' => 'Sustainability Draft Notes',
                'short_description' => 'Private brainstorming notes for upcoming articles.',
                'content' => null,
                'category' => null,
                'tags' => null,
                'status' => 'draft',
            ]);
        }

        // ── User Submissions (Pending) ──────────────────────────
        Blog::create([
            'user_id' => $john->id,
            'title' => 'Ocean Plastic Pollution: A Community Response',
            'short_description' => 'This article explores how coastal communities are coming together to address plastic pollution through innovative cleanup initiatives and policy advocacy.',
            'content' => 'This article explores how coastal communities are coming together to address plastic pollution through innovative cleanup initiatives and policy advocacy. Communities are utilizing digital platforms to coordinate beach cleanup drives, setting up local plastic recycling hubs, and campaigning to reduce single-use plastics at the retail level. The movement has gained momentum with over 500 communities participating worldwide.',
            'category' => 'Consumption',
            'tags' => 'ocean, plastic, pollution, community',
            'status' => 'pending',
        ]);

        Blog::create([
            'user_id' => $sarah->id,
            'title' => 'Green Technology Innovations in Agriculture',
            'short_description' => 'Examining cutting-edge agricultural technologies that are helping farmers reduce their carbon footprint while maintaining productivity.',
            'content' => 'Examining cutting-edge agricultural technologies that are helping farmers reduce their carbon footprint while maintaining productivity. From precision farming using AI and IoT sensors to vertical farming in urban areas, these innovations are reshaping how we produce food sustainably. The article covers case studies from farms across three continents.',
            'category' => 'Consumption',
            'tags' => 'agriculture, green tech, farming, innovation',
            'status' => 'pending',
        ]);

        Blog::create([
            'user_id' => $michael->id,
            'title' => 'Corporate Carbon Neutrality: Best Practices',
            'short_description' => 'A detailed analysis of how corporations are achieving carbon neutrality and the strategies that work best for different industries.',
            'content' => 'A detailed analysis of how corporations are achieving carbon neutrality and the strategies that work best for different industries. This article examines the carbon offset market, internal emission reduction programs, supply chain optimization, and the role of renewable energy procurement in corporate sustainability strategies.',
            'category' => 'Energy',
            'tags' => 'corporate, carbon neutral, business, sustainability',
            'status' => 'pending',
        ]);
    }
}
