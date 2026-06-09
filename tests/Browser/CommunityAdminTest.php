<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Community;
use App\Models\Post;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

use Illuminate\Foundation\Testing\DatabaseTruncation;

class CommunityAdminTest extends DuskTestCase
{
    use DatabaseTruncation;

    protected bool $seed = true;

    /**
     * Disable headless mode so the browser window is visible during testing.
     */
    protected function hasHeadlessDisabled(): bool
    {
        return true;
    }

    /**
     * TC-01: View Community List
     * Admin membuka menu Community Management. Sistem menampilkan statistik total komunitas/anggota aktif serta daftar komunitas terdaftar.
     */
    public function test_tc01_view_community_list(): void
    {
        $admin = User::where('email', 'admin@act4climate.com')->first();
        $this->assertNotNull($admin, 'Akun admin@act4climate.com tidak ditemukan di database. Pastikan database sudah ter-seed.');

        Community::where('slug', 'test-community-tc01')->delete();
        $community = Community::create([
            'name' => 'Community TC01',
            'slug' => 'test-community-tc01',
            'description' => 'A group for climate actions.',
            'created_by' => $admin->id,
            'is_active' => true,
        ]);

        $this->browse(function (Browser $browser) use ($community) {
            $browser->visit('/login');
            $browser->driver->manage()->deleteAllCookies();
            
            $browser->visit('/login')
                    ->type('email', 'admin@act4climate.com')
                    ->type('password', 'password')
                    ->press('button[type="submit"]')
                    ->pause(10000)
                    ->visit('/admin/communities')
                    ->pause(10000)
                    ->assertSee('Community Management')
                    ->assertSee('TOTAL COMMUNITIES')
                    ->assertSee('ACTIVE MEMBERS')
                    ->assertSee($community->name);
        });
    }

    /**
     * TC-02: Detail Oversight
     * Admin memilih salah satu komunitas dan klik tombol "Edit Details". Sistem mengambil data dari database dan menampilkan form informasi detail komunitas secara terstruktur.
     */
    public function test_tc02_detail_oversight(): void
    {
        $admin = User::where('email', 'admin@act4climate.com')->first();
        $this->assertNotNull($admin, 'Akun admin@act4climate.com tidak ditemukan di database.');

        Community::where('slug', 'test-community-tc02')->delete();
        $community = Community::create([
            'name' => 'Community TC02',
            'slug' => 'test-community-tc02',
            'description' => 'Detail Oversight test community',
            'created_by' => $admin->id,
            'is_active' => true,
        ]);

        $this->browse(function (Browser $browser) use ($community) {
            $browser->visit('/login');
            $browser->driver->manage()->deleteAllCookies();
            
            $browser->visit('/login')
                    ->type('email', 'admin@act4climate.com')
                    ->type('password', 'password')
                    ->press('button[type="submit"]')
                    ->pause(10000)
                    ->visit('/admin/communities')
                    ->pause(10000)
                    ->clickLink('Edit Details')
                    ->pause(10000)
                    ->assertSee('Edit Community')
                    ->assertVisible('#name')
                    ->assertVisible('#description')
                    ->assertVisible('#is_active')
                    ->assertVisible('#upload-zone')
                    ->assertValue('#name', $community->name);
        });
    }

    /**
     * TC-03: Update Info Success
     * Admin mengubah nama atau deskripsi komunitas lalu klik "Save Changes". Sistem memvalidasi data, memperbarui database, dan memuat notifikasi sukses "Community updated successfully!".
     */
    public function test_tc03_update_info_success(): void
    {
        $admin = User::where('email', 'admin@act4climate.com')->first();
        $this->assertNotNull($admin, 'Akun admin@act4climate.com tidak ditemukan di database.');

        Community::where('slug', 'test-community-tc03')->delete();
        Community::where('slug', 'test-community-tc03-updated')->delete();
        Community::where('name', 'Community TC03 Updated')->delete();
        
        $community = Community::create([
            'name' => 'Community TC03',
            'slug' => 'test-community-tc03',
            'description' => 'Original description',
            'created_by' => $admin->id,
            'is_active' => true,
        ]);

        $this->browse(function (Browser $browser) use ($community) {
            $browser->visit('/login');
            $browser->driver->manage()->deleteAllCookies();
            
            $browser->visit('/login')
                    ->type('email', 'admin@act4climate.com')
                    ->type('password', 'password')
                    ->press('button[type="submit"]')
                    ->pause(10000)
                    ->visit("/admin/communities/{$community->id}/edit")
                    ->pause(10000)
                    ->type('name', 'Community TC03 Updated')
                    ->type('description', 'Updated description text')
                    ->press('Save Changes')
                    ->pause(10000)
                    ->assertPathIs('/admin/communities')
                    ->assertSee('Community updated successfully!')
                    ->assertSee('Community TC03 Updated');
        });
    }

    /**
     * TC-04: Save Invalid Data (Validation)
     * Admin mengosongkan nama komunitas atau memasukkan kurang dari 3 karakter lalu klik "Save Changes". Sistem menolak perubahan, menampilkan pesan error validasi di bawah field, dan posisi tetap di form edit.
     */
    public function test_tc04_save_invalid_data(): void
    {
        $admin = User::where('email', 'admin@act4climate.com')->first();
        $this->assertNotNull($admin, 'Akun admin@act4climate.com tidak ditemukan di database.');

        Community::where('slug', 'test-community-tc04')->delete();
        $community = Community::create([
            'name' => 'Community TC04',
            'slug' => 'test-community-tc04',
            'description' => 'Validation test community',
            'created_by' => $admin->id,
            'is_active' => true,
        ]);

        $this->browse(function (Browser $browser) use ($community) {
            $browser->visit('/login');
            $browser->driver->manage()->deleteAllCookies();
            $browser->visit('/login')
                    ->type('email', 'admin@act4climate.com')
                    ->type('password', 'password')
                    ->press('button[type="submit"]')
                    ->pause(10000)
                    ->visit("/admin/communities/{$community->id}/edit")
                    ->pause(10000)

                    // Kurang dari 3 karakter
                    ->type('name', 'Ab')
                    ->press('Save Changes')
                    ->pause(10000)
                    ->assertPathIs("/admin/communities/{$community->id}/edit")
                    ->assertSee('The name field must be at least 3 characters.')

                    // Mengosongkan field name dengan menghapus attribute required via JS agar bisa disubmit ke Laravel
                    ->script("document.getElementById('name').removeAttribute('required');");
            
            $browser->clear('name')
                    ->press('Save Changes')
                    ->pause(10000)
                    ->assertPathIs("/admin/communities/{$community->id}/edit")
                    ->assertSee('The name field is required.');
        });
    }

    /**
     * TC-05: Moderation Check (Preview)
     * Admin menekan tombol "Preview" pada baris komunitas tertentu. Sistem mengarahkan ke halaman publik komunitas dengan status preview aktif sehingga admin dapat melihat postingan harian pengguna secara real-time.
     */
    public function test_tc05_moderation_check(): void
    {
        $admin = User::where('email', 'admin@act4climate.com')->first();
        $this->assertNotNull($admin, 'Akun admin@act4climate.com tidak ditemukan di database.');

        Community::where('slug', 'test-community-tc05')->delete();
        $community = Community::create([
            'name' => 'Community TC05',
            'slug' => 'test-community-tc05',
            'description' => 'Moderation Check community',
            'created_by' => $admin->id,
            'is_active' => true,
        ]);

        $post = Post::create([
            'user_id' => $admin->id,
            'community_id' => $community->id,
            'content' => 'TC05 Real-time User post content.',
        ]);

        $this->browse(function (Browser $browser) use ($community, $post) {
            $browser->visit('/login');
            $browser->driver->manage()->deleteAllCookies();
            
            $browser->visit('/login')
                    ->type('email', 'admin@act4climate.com')
                    ->type('password', 'password')
                    ->press('button[type="submit"]')
                    ->pause(10000)
                    ->visit('/admin/communities')
                    ->pause(10000)
                    ->clickLink('Preview')
                    ->pause(10000)
                    ->assertPathIs('/community/' . $community->id)
                    ->assertSee('TC05 Real-time User post content.')
                    ->assertSee('Join Community')
                    ->assertSee('Join the community to comment.')
                    ->assertDontSee('Posting');
        });
    }

    /**
     * TC-06: Delete Community Confirm
     * Admin menekan tombol hapus komunitas dan menyetujui konfirmasi dialog browser. Komunitas terhapus dari database beserta relasi anggotanya, dan menghilang dari list UI.
     */
    public function test_tc06_delete_community_confirm(): void
    {
        $admin = User::where('email', 'admin@act4climate.com')->first();
        $this->assertNotNull($admin, 'Akun admin@act4climate.com tidak ditemukan di database.');

        Community::where('slug', 'test-community-tc06')->delete();
        $community = Community::create([
            'name' => 'Community TC06 To Be Deleted',
            'slug' => 'test-community-tc06',
            'description' => 'Delete confirm community',
            'created_by' => $admin->id,
            'is_active' => true,
        ]);

        $this->browse(function (Browser $browser) use ($community) {
            $browser->visit('/login');
            $browser->driver->manage()->deleteAllCookies();
            
            $browser->visit('/login')
                    ->type('email', 'admin@act4climate.com')
                    ->type('password', 'password')
                    ->press('button[type="submit"]')
                    ->pause(10000)
                    ->visit('/admin/communities')
                    ->pause(10000)
                    
                    // Klik hapus dan muncul modal confirm
                    ->click("form[action*='communities/" . $community->id . "'] button")
                    ->pause(5000)
                    ->assertVisible('#global-confirm-modal')
                    
                    // Uji Exception: Klik Cancel/Batal
                    ->click('#global-confirm-cancel')
                    ->pause(5000)
                    ->assertSee('Community TC06 To Be Deleted')

                    // Klik hapus lagi untuk dikonfirmasi
                    ->click("form[action*='communities/" . $community->id . "'] button")
                    ->pause(5000)
                    ->click('#global-confirm-btn')
                    ->pause(10000)
                    ->assertDontSee('Community TC06 To Be Deleted');
        });
    }

    /**
     * TC-07: Empty State
     * Database belum memiliki data komunitas sama sekali / hasil pencarian nihil. Sistem menampilkan placeholder kosong "No communities found".
     */
    public function test_tc07_empty_state(): void
    {
        $admin = User::where('email', 'admin@act4climate.com')->first();
        $this->assertNotNull($admin, 'Akun admin@act4climate.com tidak ditemukan.');

        // Backup data komunitas sementara agar bisa memicu empty state
        $backupCommunities = Community::all();
        foreach ($backupCommunities as $c) {
            $c->delete();
        }

        try {
            $this->browse(function (Browser $browser) {
                $browser->visit('/login');
                $browser->driver->manage()->deleteAllCookies();
                $browser->visit('/login')
                        ->type('email', 'admin@act4climate.com')
                        ->type('password', 'password')
                        ->press('button[type="submit"]')
                        ->pause(10000)

                        // Masuk ke halaman list utama admin
                        ->visit('/admin/communities')
                        ->pause(10000) // Jeda memantau placeholder kosong
                        ->assertSee('No communities found')
                        ->pause(5000);
            });
        } finally {
            // Restore kembali data komunitas setelah pengetesan empty state selesai
            foreach ($backupCommunities as $backup) {
                Community::create([
                    'id' => $backup->id,
                    'name' => $backup->name,
                    'slug' => $backup->slug,
                    'description' => $backup->description,
                    'cover_image' => $backup->cover_image,
                    'created_by' => $backup->created_by,
                    'member_count' => $backup->member_count,
                    'is_active' => $backup->is_active,
                ]);
            }
        }
    }
}
