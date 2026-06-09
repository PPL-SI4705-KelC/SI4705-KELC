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
     * Skenario A & B: Admin mengawasi, mengubah, memoderasi, dan menghapus komunitas.
     */
    public function test_admin_flow_scenarios_a_and_b(): void
    {
        // 1. Precondition: Ambil data admin yang sudah ada di database
        $admin = User::where('email', 'admin@act4climate.com')->first();
        
        $this->assertNotNull($admin, 'Akun admin@act4climate.com tidak ditemukan di database. Pastikan database sudah ter-seed.');

        // Bersihkan data sampah hasil pengetesan sebelumnya agar tidak terjadi error duplicate
        Community::where('name', 'Zero Waste Society')->delete();
        Community::where('name', 'Zero Waste Society Updated')->delete();
        Community::where('slug', 'zero-waste-society')->delete();
        Community::where('slug', 'zero-waste-society-updated')->delete();

        // Buat komunitas awal untuk bahan testing
        $community = Community::create([
            'name' => 'Zero Waste Society',
            'slug' => 'zero-waste-society',
            'description' => 'A group for climate actions and reducing waste.',
            'created_by' => $admin->id,
            'is_active' => true,
        ]);

        // Buat postingan uji coba untuk memoderasi/memantau kiriman pengguna (TC-05)
        $post = Post::create([
            'user_id' => $admin->id,
            'community_id' => $community->id,
            'content' => 'Daily user post about recycling plastic bottles.',
        ]);

        $this->browse(function (Browser $browser) use ($admin, $community, $post) {
            $browser->visit('/login');
            $browser->driver->manage()->deleteAllCookies();
            
            // 2. Login Admin menggunakan akun admin@act4climate.com
            $browser->visit('/login')
                    ->type('email', 'admin@act4climate.com')
                    ->type('password', 'password') // password default dari seeder
                    ->press('button[type="submit"]')
                    ->pause(10000)

                    // 3. Memantau daftar komunitas (Skenario A - Poin a, b / TC-01)
                    ->visit('/admin/communities')
                    ->pause(10000) 
                    ->assertSee('Community Management')
                    ->assertSee('TOTAL COMMUNITIES')
                    ->assertSee('ACTIVE MEMBERS')
                    ->assertSee($community->name)

                    // 4. Memilih komunitas dan menekan "Edit Details" (Skenario A - Poin c, d / TC-02)
                    ->clickLink('Edit Details')
                    ->pause(10000) 
                    ->assertSee('Edit Community')
                    ->assertVisible('#name')
                    ->assertVisible('#description')
                    ->assertVisible('#is_active')
                    ->assertVisible('#upload-zone')
                    ->assertValue('#name', $community->name)
                    
                    // 5. Menyunting informasi komunitas (Skenario A - Poin e)
                    ->type('name', 'Zero Waste Society Updated')
                    ->type('description', 'Updated description for climate actions.')
                    ->pause(3000) // Jeda 3 detik melihat data yang terketik

                    // 6. Menyimpan perubahan (Skenario A - Poin f, g, h / TC-03)
                    ->press('Save Changes')
                    ->pause(4000) // Jeda 4 detik melihat redirect sukses
                    ->assertPathIs('/admin/communities')
                    ->assertSee('Community updated successfully!')
                    ->assertSee('Zero Waste Society Updated')

                    // 7. Melakukan Moderation Check / Preview (Skenario B - Poin j, k / TC-05)
                    ->clickLink('Preview')
                    ->pause(4000) // Jeda 4 detik memantau daftar postingan pengguna
                    ->assertPathIs('/community/' . $community->id)
                    ->assertSee('Daily user post about recycling plastic bottles.')
                    ->assertSee('Join Community')
                    ->assertSee('Join the community to comment.')
                    ->assertDontSee('Posting')
                    
                    // Kembali ke halaman list admin
                    ->visit('/admin/communities')
                    ->pause(3000)

                    // 8. Menghapus data komunitas (Skenario B - Poin l, m, n / TC-06)
                    ->click("form[action*='communities/" . $community->id . "'] button")
                    ->pause(2000) // Jeda 2 detik agar modal confirm muncul
                    ->assertVisible('#global-confirm-modal')
                    
                    // TC-06: Exception Batal / Cancel
                    ->click('#global-confirm-cancel')
                    ->pause(2000)
                    ->assertSee('Zero Waste Society Updated') // Masih ada di list

                    // Klik hapus lagi untuk melakukan konfirmasi Ya/OK
                    ->click("form[action*='communities/" . $community->id . "'] button")
                    ->pause(2000)
                    ->click('#global-confirm-btn') // Menekan tombol "Yes, delete" pada modal konfirmasi kustom
                    ->pause(4000) // Jeda 4 detik melihat hasil akhir setelah terhapus
                    ->assertDontSee('Zero Waste Society Updated');
        });
    }

    /**
     * Skenario Batas: Menyimpan input wajib yang tidak valid (kosong / kurang dari 3 karakter) (TC-04).
     */
    public function test_admin_cannot_save_invalid_data(): void
    {
        $admin = User::where('email', 'admin@act4climate.com')->first();
        $this->assertNotNull($admin, 'Akun admin@act4climate.com tidak ditemukan di database.');

        // Dapatkan satu komunitas acak untuk dites edit
        $community = Community::firstOrCreate(
            ['slug' => 'test-validation-community'],
            [
                'name' => 'Validation Test Community',
                'description' => 'Temporary community for validation test',
                'created_by' => $admin->id,
                'is_active' => true,
            ]
        );

        $this->browse(function (Browser $browser) use ($community) {
            $browser->visit('/login');
            $browser->driver->manage()->deleteAllCookies();
            $browser->visit('/login')
                    ->type('email', 'admin@act4climate.com')
                    ->type('password', 'password')
                    ->press('button[type="submit"]')
                    ->pause(3000)

                    // Masuk ke form edit komunitas
                    ->visit("/admin/communities/{$community->id}/edit")
                    ->pause(3000)

                    // TC-04: Kurang dari 3 karakter (e.g. 'Ab')
                    ->type('name', 'Ab')
                    ->press('Save Changes')
                    ->pause(3000)
                    ->assertPathIs("/admin/communities/{$community->id}/edit")
                    ->assertSee('The name field must be at least 3 characters.')

                    // TC-04: Mengosongkan field name dengan menghapus attribute required via JS agar bisa disubmit ke Laravel
                    ->script("document.getElementById('name').removeAttribute('required');");
            
            $browser->clear('name')
                    ->press('Save Changes')
                    ->pause(3000)
                    ->assertPathIs("/admin/communities/{$community->id}/edit")
                    ->assertSee('The name field is required.');
        });
    }

    /**
     * Skenario Batas: Halaman kosong ketika tidak ada komunitas (TC-07).
     */
    public function test_empty_state_shows_correct_placeholder(): void
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
                        ->pause(3000)

                        // Masuk ke halaman list utama admin
                        ->visit('/admin/communities')
                        ->pause(4000) // Jeda 4 detik memantau placeholder kosong
                        ->assertSee('No communities found')
                        ->pause(3000);
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
