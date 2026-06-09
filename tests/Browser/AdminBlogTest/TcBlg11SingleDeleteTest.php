<?php

namespace Tests\Browser\AdminBlogTest;

use Laravel\Dusk\Browser;

class TcBlg11SingleDeleteTest extends AdminBlogTestBase
{
    // =========================================================================
    // TC-BLG-11: Delete - Single Delete
    // Klik "Delete" pada satu blog, setujui confirm modal
    // → Notifikasi "Blog deleted successfully." dan blog hilang
    // =========================================================================
    public function test_tc_blg_11_single_delete_blog(): void
    {
        $admin = $this->createAdmin();
        $blog = $this->createPublishedBlog($admin, 'Blog To Be Deleted');

        $this->browse(function (Browser $browser) use ($admin, $blog) {
            $browser->loginAs($admin)
                    ->visit('/admin/blogs')
                    ->assertSee('Blog To Be Deleted');

            // Click Delete button for this blog
            $browser->click('#btn-delete-' . $blog->id)
                    ->pause(500);

            // Wait for the custom confirm modal and click "Yes, delete"
            $browser->waitFor('#global-confirm-modal.flex', 3)
                    ->click('#global-confirm-btn')
                    ->pause(2000);

            // Assert success message and blog removed
            $browser->waitFor('#flash-success', 5)
                    ->assertSeeIn('#flash-success', 'Blog deleted successfully')
                    ->assertDontSee('Blog To Be Deleted');
        });
    }
}
