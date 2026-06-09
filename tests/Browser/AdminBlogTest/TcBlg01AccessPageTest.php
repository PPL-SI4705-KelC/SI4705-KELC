<?php

namespace Tests\Browser\AdminBlogTest;

use Laravel\Dusk\Browser;

class TcBlg01AccessPageTest extends AdminBlogTestBase
{
    // =========================================================================
    // TC-BLG-01: Akses Halaman Utama
    // Admin login, klik menu "Blogs" → Berhasil diarahkan ke /admin/blogs
    // =========================================================================
    public function test_tc_blg_01_admin_can_access_blog_management_page(): void
    {
        $admin = $this->createAdmin();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/admin/blogs')
                    ->assertPathIs('/admin/blogs')
                    ->assertSee('Blog Management')
                    ->assertSee('Add New Blog');
        });
    }
}
