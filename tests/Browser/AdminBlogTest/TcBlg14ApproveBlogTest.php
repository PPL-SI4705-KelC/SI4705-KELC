<?php

namespace Tests\Browser\AdminBlogTest;

use Laravel\Dusk\Browser;

class TcBlg14ApproveBlogTest extends AdminBlogTestBase
{
    // =========================================================================
    // TC-BLG-14: Review - Approve Blog
    // Klik "Approve" → Notifikasi "Blog approved!..." dan pindah ke Published
    // =========================================================================
    public function test_tc_blg_14_approve_pending_blog(): void
    {
        $admin = $this->createAdmin();
        $user = $this->createRegularUser();
        $pendingBlog = $this->createPendingBlog($user, 'Blog To Approve');

        $this->browse(function (Browser $browser) use ($admin, $pendingBlog) {
            $browser->loginAs($admin)
                    ->visit('/admin/blogs')
                    ->assertSee('Blog To Approve');

            // Click the Approve button
            $browser->click('#btn-approve-' . $pendingBlog->id)
                    ->pause(2000);

            // Assert success notification
            $browser->waitFor('#flash-success', 5)
                    ->assertSeeIn('#flash-success', 'Blog approved');

            // Verify the blog is no longer in pending section
            $browser->assertMissing('#pending-card-' . $pendingBlog->id);

            // Verify the blog is now visible in published tab
            $browser->click('#tab-published')
                    ->pause(1000)
                    ->assertSee('Blog To Approve');
        });
    }
}
