<?php

namespace Tests\Browser\AdminBlogTest;

use Laravel\Dusk\Browser;

class TcBlg03SearchFoundTest extends AdminBlogTestBase
{
    // =========================================================================
    // TC-BLG-03: Search Data Ditemukan
    // Ketik keyword valid di form search → Data blog yang dicari muncul
    // =========================================================================
    public function test_tc_blg_03_search_finds_matching_blog(): void
    {
        $admin = $this->createAdmin();
        $this->createPublishedBlog($admin, 'Unique Climate Action Article');
        $this->createPublishedBlog($admin, 'Another Random Blog Post');

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/admin/blogs')
                    ->type('#search-input', 'Unique Climate Action')
                    ->click('#btn-search')
                    ->pause(1000)
                    ->assertSee('Unique Climate Action Article')
                    ->assertDontSee('Another Random Blog Post');
        });
    }
}
