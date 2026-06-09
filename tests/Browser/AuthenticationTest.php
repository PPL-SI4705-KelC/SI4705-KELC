<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AuthenticationTest extends DuskTestCase
{
    use DatabaseTruncation;

    /**
     * Test a user can register successfully.
     */
    public function test_user_can_register(): void
    {
        $this->browse(function (Browser $browser) {
            $timestamp = time();
            $email = 'dusk_test_' . $timestamp . '@example.com';
            $username = 'duskuser' . $timestamp;
            
            $browser->visit('/register')
                    ->assertSee('Create account')
                    ->type('username', $username)
                    ->type('email', $email)
                    ->type('password', 'password')
                    ->type('password_confirmation', 'password')
                    ->press('button[type="submit"]')
                    ->pause(1500)
                    ->assertPathIs('/dashboard')
                    ->assertSee('Climate Impact Calculator')
                    ->assertSee('Logout');
        });
    }

    /**
     * Test a normal user can login successfully.
     */
    public function test_user_can_login(): void
    {
        $timestamp = time();
        $user = User::factory()->create([
            'username' => 'loginuser' . $timestamp,
            'email' => 'login_dusk_' . $timestamp . '@example.com',
            'password' => bcrypt('password'),
            'role' => 'user'
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login');
            $browser->driver->manage()->deleteAllCookies();
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'password')
                    ->press('button[type="submit"]')
                    ->pause(1500)
                    ->assertPathIs('/dashboard')
                    ->assertSee('Climate Impact Calculator')
                    ->assertSee('Logout');
        });
    }

    /**
     * Test an admin can login successfully and gets redirected to admin dashboard.
     */
    public function test_admin_can_login(): void
    {
        $timestamp = time();
        $admin = User::factory()->create([
            'username' => 'adminuser' . $timestamp,
            'email' => 'admin_dusk_' . $timestamp . '@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->visit('/login');
            $browser->driver->manage()->deleteAllCookies();
            $browser->visit('/login')
                    ->type('email', $admin->email)
                    ->type('password', 'password')
                    ->press('button[type="submit"]')
                    ->pause(1500)
                    ->assertPathIs('/admin')
                    ->assertSee($admin->name);
        });
    }
}
