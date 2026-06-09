<?php

namespace Tests\Browser\AdminBlogTest;

use Laravel\Dusk\Browser;

class TcBlg02FilterTabsTest extends AdminBlogTestBase
{
    // =========================================================================
    // TC-BLG-02: Filter Tab (All, Published, Draft)
    // Klik tab "Published" atau "Draft" → Menampilkan data sesuai status
    // =========================================================================
    public function test_tc_blg_02_filter_tabs_show_correct_blogs(): void
    {
        $admin = $this->createAdmin();
        $publishedBlog = $this->createPublishedBlog($admin, 'My Published Article');
        $draftBlog = $this->createDraftBlog($admin, 'My Draft Article');

        $this->browse(function (Browser $browser) use ($admin) {
            // Check "All" tab shows both
            $browser->loginAs($admin)
                    ->visit('/admin/blogs')
                    ->assertSee('My Published Article')
                    ->assertSee('My Draft Article');

            // Click "Published" tab
            $browser->click('#tab-published')
                    ->pause(500)
                    ->assertQueryStringHas('tab', 'published')
                    ->assertSee('My Published Article')
                    ->assertDontSee('My Draft Article');

            // Click "Draft" tab
            $browser->click('#tab-draft')
                    ->pause(500)
                    ->assertQueryStringHas('tab', 'draft')
                    ->assertSee('My Draft Article')
                    ->assertDontSee('My Published Article');
        });
    }
}
