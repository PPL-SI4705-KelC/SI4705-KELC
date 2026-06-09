<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LeaderboardTest extends DuskTestCase
{
    /**
     * Disable headless mode so the browser window is visible during testing.
     */
    protected function hasHeadlessDisabled(): bool
    {
        return true;
    }

    /**
     * Skenario: User biasa dapat membuka halaman leaderboard global.
     */
    public function test_user_can_view_global_leaderboard(): void
    {
        // 1. Precondition: Ambil data user yang sudah terdaftar di database seeder
        $user2 = User::where('email', 'user@act4climate.com')->first();
        $this->assertNotNull($user2, 'Akun user@act4climate.com tidak ditemukan. Pastikan seeder telah dijalankan.');

        // Cari user lain di DB untuk memastikan ada data pembanding di leaderboard
        $user1 = User::where('id', '!=', $user2->id)->where('role', 'user')->first();

        $this->browse(function (Browser $browser) use ($user1, $user2) {
            // 2. Akses login user biasa
            $browser->visit('/login')
                    ->type('email', 'user@act4climate.com')
                    ->type('password', 'password') // Password default seeder
                    ->press('button[type="submit"]')
                    ->pause(3000) // Jeda 3 detik melihat proses login berhasil

                    // 3. Navigasi menuju halaman Leaderboard
                    ->visit('/leaderboard')
                    ->pause(4000) // Jeda 4 detik memantau pemuatan data leaderboard
                    ->assertSee('Global Leaderboard')
                    ->assertSee('Your Standing')
                    ->assertSee($user2->name);

            if ($user1) {
                $browser->assertSee($user1->name);
            }

            $browser->pause(5000); // Jeda 5 detik memantau peringkat visual sebelum selesai
        });
    }

    /**
     * Skenario: Admin dapat memantau ringkasan peringkat leaderboard.
     */
    public function test_admin_can_view_admin_leaderboard(): void
    {
        // 1. Precondition: Ambil data admin terdaftar
        $admin = User::where('email', 'admin@act4climate.com')->first();
        $this->assertNotNull($admin, 'Akun admin@act4climate.com tidak ditemukan di database.');

        $this->browse(function (Browser $browser) use ($admin) {
            // 2. Akses login Admin
            $browser->visit('/login')
                    ->type('email', 'admin@act4climate.com')
                    ->type('password', 'password')
                    ->press('button[type="submit"]')
                    ->pause(3000) // Jeda 3 detik

                    // 3. Akses halaman admin leaderboard
                    ->visit('/admin/leaderboard')
                    ->pause(4000) // Jeda 4 detik memantau tampilan admin leaderboard
                    ->assertSee('Leaderboard')
                    ->assertSee($admin->name)
                    ->pause(3000); // Jeda 3 detik penutupan
        });
    }
}
