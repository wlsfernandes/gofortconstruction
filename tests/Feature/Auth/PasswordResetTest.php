<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_page_renders(): void
    {
        $this->get('/forgot-password')
            ->assertOk()
            ->assertSee('Reset your password');
    }

    public function test_reset_link_is_emailed_to_existing_user(): void
    {
        Notification::fake();
        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email])
            ->assertSessionHasNoErrors();

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_reset_password_page_renders_with_token(): void
    {
        $this->get('/reset-password/some-token?email=owner@example.com')
            ->assertOk()
            ->assertSee('Choose a new password')
            ->assertSee('owner@example.com');
    }

    public function test_user_can_reset_password_with_valid_token(): void
    {
        Notification::fake();
        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) use ($user): bool {
            $this->post('/reset-password', [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'new-secure-password',
                'password_confirmation' => 'new-secure-password',
            ])->assertSessionHasNoErrors()->assertRedirect('/login');

            return true;
        });

        $this->assertTrue(Hash::check('new-secure-password', $user->fresh()->password));
    }

    public function test_reset_fails_with_invalid_token(): void
    {
        $user = User::factory()->create();

        $this->post('/reset-password', [
            'token' => 'invalid-token',
            'email' => $user->email,
            'password' => 'new-secure-password',
            'password_confirmation' => 'new-secure-password',
        ])->assertSessionHasErrors('email');

        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }
}
