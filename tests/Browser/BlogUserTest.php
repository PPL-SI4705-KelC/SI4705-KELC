<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class BlogUserTest extends DuskTestCase
{
    /**
     * Skenario 1: Test user dapat mengakses index blog dan melakukan pencarian.
     *
     * @return void
     */
    public function test_user_can_access_blog_index_and_search()
    {
        $this->browse(function (Browser $browser) {
            // Bypass login menggunakan user dengan ID 1
            $browser->loginAs(User::find(7))
                    // Buka halaman utama blogs
                    ->visit('/blogs')
                    // Memastikan URL saat ini benar (response sukses)
                    ->assertPathIs('/blogs')
                    // Mengetik kata kunci pada kolom pencarian berdasarkan placeholder
                    ->type('[placeholder="Search stories..."]', 'Aksi Iklim')
                    // Memastikan tombol Write Blog ada dan terlihat
                    ->assertPresent('#write-blog-btn')
                    ->assertSee('Write Blog')
                    // Memastikan tombol My Blogs ada dan terlihat
                    ->assertPresent('#my-blogs-btn')
                    ->assertSee('My Blogs');
        });
    }

    /**
     * Skenario 2: Test muncul error validasi ketika submit form dalam keadaan kosong.
     *
     * @return void
     */
    public function test_validation_error_when_submitting_empty_form()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(7))
                    // Buka halaman buat blog baru
                    ->visit('/blogs/create')
                    // Langsung klik tombol submit tanpa mengisi title
                    ->click('#submit-for-review')
                    // Tunggu sebentar untuk proses validasi
                    ->pause(1000)
                    // Memastikan sistem memblokir form dan tetap berada di halaman form
                    ->assertPathIs('/blogs/create')
                    // Memastikan terdapat pesan error validasi
                    ->assertSee('The title field is required');
        });
    }

    /**
     * Skenario 3: Test user dapat membuat draft blog baru dengan sukses.
     *
     * @return void
     */
    public function test_user_can_successfully_submit_new_blog_draft()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(7))
                    // Buka halaman buat blog baru
                    ->visit('/blogs/create')
                    // Memilih kategori pertama ("Transportation")
                    ->select('category_id', 'Transportation')
                    // Mengisi input judul
                    ->type('title', 'Tips Hemat Energi')
                    // Mengisi textarea excerpt
                    ->type('excerpt', 'Deskripsi pendek tips hemat energi')
                    // Mengisi Rich Text Editor (menggunakan selector class atau id yang tersedia)
                    ->keys('#blog-content', 'Ini adalah isi dari blog mengenai langkah-langkah dan tips hemat energi. ' . str_repeat('Dan ini adalah kalimat tambahan untuk memastikan panjang teks memenuhi kriteria minimum 300 karakter yang diperlukan oleh sistem validasi backend. ', 3))
                    // Klik tombol submit form
                    ->click('#submit-for-review')
                    // Tunggu sebentar agar proses submit selesai
                    ->pause(1000)
                    // Memastikan redirect otomatis ke halaman My Blogs
                    ->assertPathIs('/blogs/my')
                    // Memastikan menampilkan flash message sukses
                    ->waitForText('Blog post submitted for review!', 5);
        });
    }

    /**
     * Skenario 4: Test user dapat melihat list blog mereka sendiri dengan status PENDING.
     *
     * @return void
     */
    public function test_user_can_see_their_own_submitted_blog_with_pending_status()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(7))
                    // Buka halaman list blog pribadi
                    ->visit('/blogs/my')
                    // Memastikan blog berjudul "Tips Hemat Energi" muncul di list (menunggu animasi selesai)
                    ->waitForText('Tips Hemat Energi', 5)
                    // Memastikan badge status dengan class .badge-pending ada di baris tersebut
                    ->waitFor('.badge-pending', 5)
                    // Memastikan teks di dalam badge status adalah PENDING
                    ->assertSeeIn('.badge-pending', 'PENDING');
        });
    }
}
