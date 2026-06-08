<?php

namespace Database\Seeders;

use App\Models\Community;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CommunitySeeder extends Seeder
{
    public function run(): void
    {
        $communities = [
            ['name' => 'Zero Waste Warriors', 'description' => 'Share tips and experiences on reducing waste in daily life. Join us to learn how to live more sustainably.'],
            ['name' => 'Green Energy Advocates', 'description' => 'Discuss renewable energy solutions, solar panels, EVs, and energy-efficient living.'],
            ['name' => 'Climate Action Network', 'description' => 'A community for climate activists to share news, organize events, and drive change.'],
            ['name' => 'Sustainable Food Hub', 'description' => 'Explore plant-based recipes, local farming, and sustainable food choices.'],
        ];

        foreach ($communities as $c) {
            Community::create([
                'name' => $c['name'],
                'slug' => Str::slug($c['name']),
                'description' => $c['description'],
                'created_by' => 1,
                'is_active' => true,
            ]);
        }
    }
}
