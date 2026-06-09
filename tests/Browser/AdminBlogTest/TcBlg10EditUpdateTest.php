<?php

namespace Tests\Browser\AdminBlogTest;

use Laravel\Dusk\Browser;

class TcBlg10EditUpdateTest extends AdminBlogTestBase
{
    // =========================================================================
    // TC-BLG-10: Edit - Update Data
    // Klik "Edit", ubah data form, klik "Update Post"
    // → Notifikasi sukses dan data baru terlihat di list
    // =========================================================================
    public function test_tc_blg_10_edit_and_update_blog_successfully(): void
    {
        $admin = $this->createAdmin();
        $blog = $this->createPublishedBlog($admin, 'Original Title Before Edit');

        $this->browse(function (Browser $browser) use ($admin, $blog) {
            $browser->loginAs($admin)
                    ->visit('/admin/blogs')
                    ->assertSee('Original Title Before Edit');

            // Click the Edit button for this blog
            $browser->click('#btn-edit-' . $blog->id)
                    ->pause(1000)
                    ->assertPathIs('/admin/blogs/' . $blog->id . '/edit')
                    ->assertSee('Edit Post');

            // Update the title
            $browser->clear('#title')
                    ->type('#title', 'Updated Title After Edit')
                    ->pause(300);

            // Click Update/Publish Post button
            $browser->click('#btn-publish-bottom')
                    ->pause(2000);

            // Assert redirect and success message
            $browser->assertPathIs('/admin/blogs')
                    ->waitFor('#flash-success', 5)
                    ->assertSeeIn('#flash-success', 'Blog published successfully!')
                    ->assertSee('Updated Title After Edit');
        });
    }
}
