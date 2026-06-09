<?php

namespace Tests\Browser\AdminBlogTest;

use Laravel\Dusk\Browser;

class TcBlg08CreateValidationTest extends AdminBlogTestBase
{
    // =========================================================================
    // TC-BLG-08: Create - Validasi Form (Error)
    // Kosongkan semua inputan dan klik "Publish"
    // → Muncul teks error validasi (contoh: Blog title is required)
    // =========================================================================
    public function test_tc_blg_08_create_publish_shows_validation_errors(): void
    {
        $admin = $this->createAdmin();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/admin/blogs/create')
                    ->assertSee('Create New Post');

            // Clear the title field (in case browser auto-fills) and submit
            $browser->clear('#title')
                    ->script([
                        "document.getElementById('content').value = '';",
                        "document.getElementById('title').removeAttribute('required');"
                    ]);

            // Click Publish without filling anything
            $browser->click('#btn-publish-top')
                    ->pause(2000);

            // Assert validation errors are displayed
            $browser->assertSee('Blog title is required');
        });
    }
}
