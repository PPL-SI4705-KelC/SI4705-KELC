<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Community;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CommunityAdminTest extends DuskTestCase
{
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

        $this->browse(function (Browser $browser) use ($admin, $community) {
            // 2. Login Admin menggunakan akun admin@act4climate.com
            $browser->visit('/login')
                    ->type('email', 'admin@act4climate.com')
                    ->type('password', 'password') // password default dari seeder
                    ->press('button[type="submit"]')
                    ->pause(3000) // Jeda 3 detik melihat proses login

                    // 3. Memantau daftar komunitas (Skenario A - Poin a, b)
                    ->visit('/admin/communities')
                    ->pause(4000) // Jeda 4 detik memantau tabel data list
                    ->assertSee('Community Management')
                    ->assertSee($community->name)

                    // 4. Memilih komunitas dan menekan "Edit Details" (Skenario A - Poin c, d)
                    ->clickLink('Edit Details')
                    ->pause(3000) // Jeda 3 detik memantau form edit termuat
                    ->assertSee('Edit Community')
                    ->assertValue('#name', $community->name)
                    
                    // 5. Menyunting informasi komunitas (Skenario A - Poin e)
                    ->type('name', 'Zero Waste Society Updated')
                    ->type('description', 'Updated description for climate actions.')
                    ->pause(3000) // Jeda 3 detik melihat data yang terketik

                    // 6. Menyimpan perubahan (Skenario A - Poin f, g, h)
                    ->press('Save Changes')
                    ->pause(4000) // Jeda 4 detik melihat redirect sukses
                    ->assertPathIs('/admin/communities')
                    ->assertSee('Zero Waste Society Updated')

                    // 7. Melakukan Moderation Check (Skenario B - Poin j, k)
                    ->clickLink('Preview')
                    ->pause(4000) // Jeda 4 detik memantau daftar postingan pengguna
                    ->assertPathContains('/community/zero-waste-society')
                    
                    // Kembali ke halaman list admin
                    ->visit('/admin/communities')
                    ->pause(3000)

                    // 8. Menghapus data komunitas (Skenario B - Poin l, m, n)
                    ->press('form[action*="destroy"] button[type="submit"]')
                    ->pause(2000) // Jeda 2 detik sebelum konfirmasi dialog browser muncul
                    ->acceptDialog() // Menekan tombol "OK" pada dialog pop-up konfirmasi browser
                    ->pause(4000) // Jeda 4 detik melihat hasil akhir setelah terhapus
                    ->assertDontSee('Zero Waste Society Updated');
        });
    }

    /**
     * Skenario Batas: Menyimpan input wajib yang tidak valid (kosong).
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
            $browser->visit('/login')
                    ->type('email', 'admin@act4climate.com')
                    ->type('password', 'password')
                    ->press('button[type="submit"]')
                    ->pause(3000)

                    // Masuk ke form edit komunitas
                    ->visit("/admin/communities/{$community->id}/edit")
                    ->pause(3000)

                    // Mengosongkan field name (wajib)
                    ->clear('name')
                    ->pause(3000) // Jeda 3 detik melihat input kosong

                    // Mencoba menyimpan perubahan kosong
                    ->press('Save Changes')
                    ->pause(3000); // Jeda 3 detik melihat browser validation error
        });
    }

    /**
     * Skenario Batas: Halaman kosong ketika tidak ada komunitas.
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
