<?php

namespace Tests\Browser\AdminBlogTest;

use Laravel\Dusk\Browser;

class TcBlg15RejectBlogTest extends AdminBlogTestBase
{
    // =========================================================================
    // TC-BLG-15: Review - Reject Blog
    // Klik "Reject", isi alasan penolakan di modal, klik "Reject Submission"
    // → Notifikasi "Blog rejected..." dan blog hilang dari daftar tunggu
    // =========================================================================
    public function test_tc_blg_15_reject_pending_blog(): void
    {
        $admin = $this->createAdmin();
        $user = $this->createRegularUser();
        $pendingBlog = $this->createPendingBlog($user, 'Blog To Reject');

        $this->browse(function (Browser $browser) use ($admin, $pendingBlog) {
            $browser->loginAs($admin)
                    ->visit('/admin/blogs')
                    ->assertSee('Blog To Reject');

            // Click the Reject button to open Alpine.js modal
            $browser->click('#btn-reject-' . $pendingBlog->id)
                    ->pause(500);

            // Wait for the reject modal to appear
            $browser->waitFor('#reject-modal', 3)
                    ->assertSee('Reject User Submission');

            // Type rejection reason
            $browser->type('#reject_reason', 'The content does not meet our quality standards. Please add more references and improve the structure.')
                    ->pause(300);

            // Click "Reject Submission" button
            $browser->click('#btn-submit-reject')
                    ->pause(2000);

            // Assert success notification
            $browser->waitFor('#flash-success', 5)
                    ->assertSeeIn('#flash-success', 'Blog rejected');

            // Verify the blog is no longer in pending section
            $browser->assertMissing('#pending-card-' . $pendingBlog->id)
                    ->assertDontSee('Blog To Reject');
        });
    }
}
