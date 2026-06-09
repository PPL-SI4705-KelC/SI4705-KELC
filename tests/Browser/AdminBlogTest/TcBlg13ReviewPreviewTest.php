<?php

namespace Tests\Browser\AdminBlogTest;

use Laravel\Dusk\Browser;

class TcBlg13ReviewPreviewTest extends AdminBlogTestBase
{
    // =========================================================================
    // TC-BLG-13: Review - Akses Review
    // Klik "Review" pada list Pending blog → Diarahkan ke halaman preview blog
    // =========================================================================
    public function test_tc_blg_13_review_pending_blog_opens_preview(): void
    {
        $admin = $this->createAdmin();
        $user = $this->createRegularUser();
        $pendingBlog = $this->createPendingBlog($user, 'Pending Blog For Review');

        $this->browse(function (Browser $browser) use ($admin, $pendingBlog) {
            $browser->loginAs($admin)
                    ->visit('/admin/blogs')
                    ->assertSee('Pending Blog For Review');

            // The Review button opens in a new tab (target="_blank"),
            // so we just verify the link exists and points to the right URL
            $reviewUrl = route('blogs.show', $pendingBlog);

            $browser->assertSeeLink('Review')
                    ->assertAttribute('#btn-review-' . $pendingBlog->id, 'href', $reviewUrl);
        });
    }
}
