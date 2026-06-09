<?php

namespace Tests\Browser\AdminBlogTest;

use Laravel\Dusk\Browser;

class TcBlg12BulkDeleteTest extends AdminBlogTestBase
{
    // =========================================================================
    // TC-BLG-12: Delete - Bulk Delete
    // Centang checkbox pada beberapa blog, klik "Delete Selected"
    // → Notifikasi "X articles deleted successfully."
    // =========================================================================
    public function test_tc_blg_12_bulk_delete_blogs(): void
    {
        $admin = $this->createAdmin();
        $blog1 = $this->createPublishedBlog($admin, 'Bulk Delete Blog One');
        $blog2 = $this->createPublishedBlog($admin, 'Bulk Delete Blog Two');
        $blog3 = $this->createPublishedBlog($admin, 'Bulk Delete Blog Three');

        $this->browse(function (Browser $browser) use ($admin, $blog1, $blog2) {
            $browser->loginAs($admin)
                    ->visit('/admin/blogs')
                    ->assertSee('Bulk Delete Blog One')
                    ->assertSee('Bulk Delete Blog Two')
                    ->assertSee('Bulk Delete Blog Three');

            // Check two blog checkboxes using Alpine.js x-model
            // We click the checkboxes inside the blog cards
            $browser->script([
                // Select blog1 and blog2 checkboxes by clicking them
                "document.querySelector('#blog-card-{$blog1->id} .blog-checkbox').click();",
                "document.querySelector('#blog-card-{$blog2->id} .blog-checkbox').click();",
            ]);

            $browser->pause(500);

            // The bulk delete bar should be visible now
            $browser->assertSee('2 articles selected');

            // Submit the bulk delete form via the button inside #bulk-delete-form
            $browser->script([
                "document.querySelector('#bulk-delete-form').requestSubmit();"
            ]);

            $browser->pause(500);

            // Wait for the custom confirm modal and click "Yes, delete"
            $browser->waitFor('#global-confirm-modal.flex', 3)
                    ->click('#global-confirm-btn')
                    ->pause(2000);

            // Assert success message
            $browser->waitFor('#flash-success', 5)
                    ->assertSeeIn('#flash-success', '2 articles deleted successfully')
                    ->assertDontSee('Bulk Delete Blog One')
                    ->assertDontSee('Bulk Delete Blog Two')
                    // Blog Three should remain
                    ->assertSee('Bulk Delete Blog Three');
        });
    }
}
