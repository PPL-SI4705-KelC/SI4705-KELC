<?php

namespace Tests\Browser;

use App\Models\Blog;
use App\Models\User;
use App\Models\XpLog;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RewardXpBlogTest extends DuskTestCase
{
    use DatabaseTruncation;

    /**
     * Skenario 1: Admin menyetujui blog pertama, user mendapat 1000 XP.
     */
    public function test_admin_approves_first_blog_allocates_1000_xp()
    {
        // a. Kondisikan di database terdapat User A yang memiliki 1 draf blog berstatus 'PENDING'
        //    dan belum pernah memiliki blog 'APPROVED' (publikasi pertama).
        $userA = User::factory()->create([
            'xp' => 0,
            'level' => 1
        ]);
        
        $blogA = Blog::create([
            'user_id' => $userA->id,
            'title' => 'Blog Pertama User A',
            'short_description' => 'Ini deskripsi pendek',
            'content' => 'Ini konten blog yang cukup panjang untuk lulus validasi',
            'category' => 'Transportation',
            'status' => Blog::STATUS_PENDING,
        ]);

        $admin = User::factory()->create(['role' => 'admin']);

        $this->browse(function (Browser $browser) use ($admin, $userA, $blogA) {
            // b. Bypass login sebagai Admin, buka halaman /admin/blogs
            $browser->loginAs($admin)
                    ->visit('/admin/blogs')
                    ->waitForText('User Submissions', 5)
                    // c. Klik tombol approve pada draf milik User A
                    ->click('#btn-approve-' . $blogA->id)
                    ->waitForText('Blog approved!', 5); // Menunggu flash message sukses
            
            // Assertion Database (a. Pastikan status blog User A berubah menjadi APPROVED/PUBLISHED)
            $this->assertDatabaseHas('blogs', [
                'id' => $blogA->id,
                'status' => Blog::STATUS_PUBLISHED,
            ]);

            // Assertion Database (b. Terdapat baris di point_logs/xp_logs amount = 1000)
            $this->assertDatabaseHas('xp_logs', [
                'user_id' => $userA->id,
                'xp_amount' => 1000,
                'source' => 'blog',
            ]);

            // Assertion Database (c. Pastikan total_xp/xp milik User A bertambah 1000 poin)
            $this->assertDatabaseHas('users', [
                'id' => $userA->id,
                'xp' => 1000,
                'level' => 2, // 1000 XP seharusnya sudah cukup untuk naik ke Level 2
            ]);
        });
    }

    /**
     * Skenario 2: Admin menyetujui blog berikutnya, user mendapat 500 XP.
     */
    public function test_admin_approves_subsequent_blog_allocates_500_xp()
    {
        // a. Kondisikan di database terdapat User B yang sudah memiliki 1 blog APPROVED (1000 XP)
        $userB = User::factory()->create([
            'xp' => 1000,
            'level' => 2
        ]);
        
        // Blog pertama (sudah published)
        Blog::create([
            'user_id' => $userB->id,
            'title' => 'Blog Lama User B',
            'short_description' => 'Ini deskripsi pendek',
            'content' => 'Ini konten blog yang cukup panjang untuk lulus validasi',
            'category' => 'Transportation',
            'status' => Blog::STATUS_PUBLISHED,
        ]);

        // Blog kedua (pending)
        $blogB = Blog::create([
            'user_id' => $userB->id,
            'title' => 'Blog Baru User B',
            'short_description' => 'Ini deskripsi pendek',
            'content' => 'Ini konten blog yang cukup panjang untuk lulus validasi',
            'category' => 'Transportation',
            'status' => Blog::STATUS_PENDING,
        ]);

        $admin = User::factory()->create(['role' => 'admin']);

        $this->browse(function (Browser $browser) use ($admin, $userB, $blogB) {
            // b. Bypass login sebagai Admin, buka halaman /admin/blogs
            $browser->loginAs($admin)
                    ->visit('/admin/blogs')
                    ->waitForText('User Submissions', 5)
                    // c. Klik tombol approve pada draf baru milik User B
                    ->click('#btn-approve-' . $blogB->id)
                    ->waitForText('Blog approved!', 5);
            
            // Assertion Database (a. Status blog kedua berubah menjadi APPROVED/PUBLISHED)
            $this->assertDatabaseHas('blogs', [
                'id' => $blogB->id,
                'status' => Blog::STATUS_PUBLISHED,
            ]);

            // Assertion Database (b. Tercatat di xp_logs penambahan sebesar 500 poin)
            $this->assertDatabaseHas('xp_logs', [
                'user_id' => $userB->id,
                'xp_amount' => 500,
                'source' => 'blog',
            ]);

            // Assertion Database (c. Pastikan total_xp/xp milik User B bertambah 500 poin menjadi 1500)
            $this->assertDatabaseHas('users', [
                'id' => $userB->id,
                'xp' => 1500,
            ]);
        });
    }

    /**
     * Skenario 3: Akumulasi XP memicu level up.
     */
    public function test_xp_accumulation_triggers_level_up()
    {
        // a. Kondisikan User C memiliki xp awal 900 dan memiliki 1 draf blog PENDING
        $userC = User::factory()->create([
            'xp' => 900,
            'level' => 1
        ]);
        
        $blogC = Blog::create([
            'user_id' => $userC->id,
            'title' => 'Blog User C',
            'short_description' => 'Ini deskripsi pendek',
            'content' => 'Ini konten blog yang cukup panjang untuk lulus validasi',
            'category' => 'Transportation',
            'status' => Blog::STATUS_PENDING,
        ]);

        $admin = User::factory()->create(['role' => 'admin']);

        $this->browse(function (Browser $browser) use ($admin, $userC, $blogC) {
            // b. Jalankan proses approval oleh admin pada draf blog User C
            $browser->loginAs($admin)
                    ->visit('/admin/blogs')
                    ->waitForText('User Submissions', 5)
                    ->click('#btn-approve-' . $blogC->id)
                    ->waitForText('Blog approved!', 5);

            // Assertion Database (a. Pastikan total_xp terakumulasi menjadi 1900)
            // Assertion Database (b. Pastikan kolom level/level_id otomatis naik ke 2)
            $this->assertDatabaseHas('users', [
                'id' => $userC->id,
                'xp' => 1900,
                'level' => 2,
            ]);
        });
    }

    /**
     * Skenario 4: Halaman dashboard menampilkan XP dan Level terbaru.
     */
    public function test_user_dashboard_displays_updated_xp_and_level()
    {
        // a. Lakukan pengondisian database agar akun User D baru saja mendapatkan reward XP
        $userD = User::factory()->create([
            'xp' => 2500,
            'level' => 3
        ]);

        $this->browse(function (Browser $browser) use ($userD) {
            // b. Bypass login sebagai User D
            $browser->loginAs($userD)
                    // c. Buka halaman utama /dashboard
                    ->visit('/dashboard')
                    // Assertion: Pastikan teks informasi total poin dirender pada UI Layer Dashboard (format number)
                    ->waitForText('2,500 XP', 5) // Menunggu angka xp render
                    ->assertSee('2,500 XP'); // Memastikan text ada di UI
        });
    }
}
