<?php

namespace Tests\Browser\AdminBlogTest;

use Illuminate\Support\Facades\Storage;
use Laravel\Dusk\Browser;

class TcBlg06CreatePublishTest extends AdminBlogTestBase
{
    // =========================================================================
    // TC-BLG-06: Create - Alur "Publish" Sukses
    // Klik "Add New Blog", isi semua form, klik "Publish"
    // → Notifikasi "Blog published successfully!" dan muncul di tab Published
    // =========================================================================
    public function test_tc_blg_06_create_and_publish_blog_successfully(): void
    {
        $admin = $this->createAdmin();

        // Create a test image in storage for upload
        Storage::fake('public');
        $imagePath = storage_path('app/public/test-image.jpg');
        if (!file_exists(dirname($imagePath))) {
            mkdir(dirname($imagePath), 0755, true);
        }

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/admin/blogs/create')
                    ->assertSee('Create New Post');

            // Fill in the form fields
            $browser->select('#category', 'Energy')
                    ->type('#title', 'Dusk Published Blog E2E Test')
                    ->type('#short_description', 'This is a short description for the E2E test blog post.')
                    // Set content via hidden input (Trix editor)
                    ->script([
                        "document.getElementById('content').value = '" . str_repeat('This is automated test content for blog publishing via Laravel Dusk. ', 8) . "';"
                    ]);

            $browser->type('#tags', 'dusk, automation, test')
                    // Attach a test image
                    ->attach('#featured-image-input', __DIR__ . '/../../../public/images/logo.png')
                    ->pause(500);

            // Click Publish Post button (bottom)
            $browser->click('#btn-publish-top')
                    ->pause(2000);

            // Assert redirect and success message
            $browser->assertPathIs('/admin/blogs')
                    ->assertQueryStringHas('tab', 'published')
                    ->waitFor('#flash-success', 5)
                    ->assertSeeIn('#flash-success', 'Blog published successfully!')
                    ->assertSee('Dusk Published Blog E2E Test');
        });
    }
}
