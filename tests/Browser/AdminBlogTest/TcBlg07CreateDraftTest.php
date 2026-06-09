<?php

namespace Tests\Browser\AdminBlogTest;

use Laravel\Dusk\Browser;

class TcBlg07CreateDraftTest extends AdminBlogTestBase
{
    // =========================================================================
    // TC-BLG-07: Create - Alur "Save Draft" Sukses
    // Klik "Add New Blog", isi form, klik "Save Draft"
    // → Notifikasi "Blog saved as draft successfully!" dan muncul di tab Draft
    // =========================================================================
    public function test_tc_blg_07_create_and_save_draft_successfully(): void
    {
        $admin = $this->createAdmin();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/admin/blogs/create')
                    ->assertSee('Create New Post');

            // Fill minimal fields for draft
            $browser->type('#title', 'Dusk Draft Blog E2E Test')
                    ->pause(300);

            // Click Save Draft button
            $browser->click('#btn-save-draft')
                    ->pause(2000);

            // Assert redirect and success message
            $browser->assertPathIs('/admin/blogs')
                    ->assertQueryStringHas('tab', 'draft')
                    ->waitFor('#flash-success', 5)
                    ->assertSeeIn('#flash-success', 'Blog saved as draft successfully!')
                    ->assertSee('Dusk Draft Blog E2E Test');
        });
    }
}
