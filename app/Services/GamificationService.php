<?php

namespace App\Services;

use App\Models\User;
use App\Models\XpLog;

class GamificationService
{
    private const XP_PER_LEVEL = 1000;

    /**
     * Award XP to a user and check for level up.
     */
    public function awardXp(User $user, int $amount, string $source, ?string $description = null): void
    {
        // Log the XP transaction
        XpLog::create([
            'user_id' => $user->id,
            'xp_amount' => $amount,
            'source' => $source,
            'description' => $description,
        ]);

        // Update user XP
        $user->increment('xp', $amount);
        $user->refresh();

        // Check and apply level ups
        $this->checkLevelUp($user);
    }

    /**
     * Check if user should level up and apply.
     */
    public function checkLevelUp(User $user): bool
    {
        $newLevel = $this->calculateLevel($user->xp);

        if ($newLevel > $user->level) {
            $user->update(['level' => $newLevel]);
            return true;
        }

        return false;
    }

    /**
     * Calculate level from XP.
     * Level 1: 0–999 XP, Level 2: 1000–1999, etc.
     */
    public function calculateLevel(int $xp): int
    {
        return max(1, intdiv($xp, self::XP_PER_LEVEL) + 1);
    }

    /**
     * Get XP needed for next level.
     */
    public function xpToNextLevel(User $user): int
    {
        $nextLevelXp = $user->level * self::XP_PER_LEVEL;
        return max(0, $nextLevelXp - $user->xp);
    }

    /**
     * Get progress percentage to next level.
     */
    public function levelProgress(User $user): float
    {
        $currentLevelStart = ($user->level - 1) * self::XP_PER_LEVEL;
        $progress = $user->xp - $currentLevelStart;
        return round(($progress / self::XP_PER_LEVEL) * 100, 1);
    }

    /**
     * Get the journey title for a level.
     */
    public static function getJourneyTitle(int $level): string
    {
        return match (true) {
            $level >= 6 => 'Planet Guardian',
            $level >= 5 => 'Climate Champion',
            $level >= 4 => 'Climate Advocate',
            $level >= 3 => 'Eco Warrior',
            $level >= 2 => 'Green Starter',
            default => 'Eco Beginner',
        };
    }

    /**
     * Get all journey levels with requirements.
     */
    public static function getJourneyMap(): array
    {
        return [
            [
                'level' => 1,
                'title' => 'Eco Beginner',
                'xp_required' => 0,
                'icon' => '🌱',
                'color' => '#9E9E9E',
                'description' => 'Started your journey towards sustainable living. Learned the basics of climate action.',
                'tags' => ['Energy Awareness', 'Waste Reduction', 'Water Conservation'],
            ],
            [
                'level' => 2,
                'title' => 'Green Starter',
                'xp_required' => 1000,
                'icon' => '🌿',
                'color' => '#4CAF50',
                'description' => 'Implemented sustainable habits in daily life. Started tracking carbon footprint.',
                'tags' => ['Green Transport', 'Sustainable Shopping', 'Plant-Based Meals'],
            ],
            [
                'level' => 3,
                'title' => 'Eco Warrior',
                'xp_required' => 2000,
                'icon' => '🌍',
                'color' => '#2196F3',
                'description' => 'Became an advocate for climate action. Influenced others to join the movement.',
                'tags' => ['Community Leader', 'Renewable Energy', 'Tree Planting'],
            ],
            [
                'level' => 4,
                'title' => 'Climate Advocate',
                'xp_required' => 3000,
                'icon' => '⚡',
                'color' => '#FF9800',
                'description' => 'Leading climate initiatives and inspiring large-scale change in your community.',
                'tags' => ['Climate Campaigns', 'Partnerships', 'Recognition'],
            ],
            [
                'level' => 5,
                'title' => 'Climate Champion',
                'xp_required' => 4000,
                'icon' => '🏆',
                'color' => '#FBC02D',
                'description' => 'Achieve mastery in sustainable living and become a role model for climate action.',
                'tags' => ['Local Action', 'Impact Report', 'SDG Badge'],
            ],
            [
                'level' => 6,
                'title' => 'Planet Guardian',
                'xp_required' => 5000,
                'icon' => '🌟',
                'color' => '#2D5A4C',
                'description' => 'Ultimate level of environmental stewardship. Lead transformative climate initiatives.',
                'tags' => ['Leadership Role', 'World Leader', 'Climate Badge'],
            ],
        ];
    }
}
