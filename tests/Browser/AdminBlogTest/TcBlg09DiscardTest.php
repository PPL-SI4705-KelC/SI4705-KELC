<?php

namespace Tests\Browser\AdminBlogTest;

use Laravel\Dusk\Browser;

class TcBlg09DiscardTest extends AdminBlogTestBase
{
    // =========================================================================
    // TC-BLG-09: Create - Discard / Cancel
    // Klik "Discard" di form → Kembali ke /admin/blogs tanpa data tersimpan
    // =========================================================================
    public function test_tc_blg_09_discard_returns_to_index_without_saving(): void
    {
        $admin = $this->createAdmin();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/admin/blogs/create')
                    ->assertSee('Create New Post');

            // Type something (but we will discard)
            $browser->type('#title', 'This Should Not Be Saved')
                    ->pause(300);

            // Click Discard
            $browser->click('#btn-discard')
                    ->pause(1000);

            // Assert we're back to index
            $browser->assertPathIs('/admin/blogs')
                    // Assert the discarded blog does NOT appear
                    ->assertDontSee('This Should Not Be Saved');
        });
    }
}
