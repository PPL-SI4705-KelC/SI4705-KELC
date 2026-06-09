<?php

namespace Tests\Browser\AdminBlogTest;

use Laravel\Dusk\Browser;

class TcBlg05ResetSearchTest extends AdminBlogTestBase
{
    // =========================================================================
    // TC-BLG-05: Reset Pencarian
    // Klik "Reset" setelah searching → Form kosong, semua blog muncul kembali
    // =========================================================================
    public function test_tc_blg_05_reset_search_restores_full_list(): void
    {
        $admin = $this->createAdmin();
        $this->createPublishedBlog($admin, 'Blog Alpha');
        $this->createPublishedBlog($admin, 'Blog Beta');

        $this->browse(function (Browser $browser) use ($admin) {
            // First, perform a search
            $browser->loginAs($admin)
                    ->visit('/admin/blogs')
                    ->type('#search-input', 'Blog Alpha')
                    ->click('#btn-search')
                    ->pause(1000)
                    ->assertSee('Blog Alpha')
                    ->assertDontSee('Blog Beta');

            // Now click Reset
            $browser->click('#btn-reset-search')
                    ->pause(1000)
                    ->assertSee('Blog Alpha')
                    ->assertSee('Blog Beta');
        });
    }
}
