<?php

namespace Tests\Browser\AdminBlogTest;

use Laravel\Dusk\Browser;

class TcBlg04SearchNotFoundTest extends AdminBlogTestBase
{
    // =========================================================================
    // TC-BLG-04: Search Data Tidak Ditemukan
    // Ketik keyword tidak ada → Menampilkan pesan empty state
    // =========================================================================
    public function test_tc_blg_04_search_shows_empty_state_for_no_results(): void
    {
        $admin = $this->createAdmin();
        $this->createPublishedBlog($admin);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/admin/blogs')
                    ->type('#search-input', 'xyznonexistentkeyword12345')
                    ->click('#btn-search')
                    ->pause(1000)
                    ->assertSee('No')
                    ->assertSee('blogs found');
        });
    }
}
