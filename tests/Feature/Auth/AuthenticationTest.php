<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_renders(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('Sign in');
    }

    public function test_user_can_sign_in_with_valid_credentials(): void
    {
        $user = User::factory()->create();

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect('/admin');

        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_sign_in_with_wrong_password(): void
    {
        $user = User::factory()->create();

        $this->from('/login')
            ->post('/login', ['email' => $user->email, 'password' => 'wrong-password'])
            ->assertRedirect('/login')
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_login_is_throttled_after_five_failed_attempts(): void
    {
        $user = User::factory()->create();

        foreach (range(1, 5) as $attempt) {
            $this->post('/login', ['email' => $user->email, 'password' => 'wrong-password']);
        }

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertTooManyRequests();

        $this->assertGuest();
    }

    public function test_user_can_sign_out(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/logout')
            ->assertRedirect('/');

        $this->assertGuest();
    }

    public function test_guest_is_redirected_from_admin_to_login(): void
    {
        $this->get('/admin')->assertRedirect('/login');
    }

    public function test_signed_in_user_sees_admin_dashboard(): void
    {
        $user = User::factory()->create(['name' => 'Dana Builder']);

        $this->actingAs($user)
            ->get('/admin')
            ->assertOk()
            ->assertSee('Dana Builder');
    }

    public function test_public_registration_is_disabled(): void
    {
        $this->get('/register')->assertNotFound();
        $this->post('/register', [
            'name' => 'Intruder',
            'email' => 'intruder@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertNotFound();

        $this->assertDatabaseMissing('users', ['email' => 'intruder@example.com']);
    }
}
