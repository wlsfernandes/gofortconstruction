<?php

namespace Tests\Feature\Admin;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SiteSettingsAdminTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'phone_display' => '404-555-DECK (3325)',
            'phone_short' => '404-555-3325',
            'email' => 'hello@example.com',
            'service_area' => 'Marietta, GA',
            'service_area_tagline' => 'Serving Cobb County',
            'facebook_url' => 'https://facebook.com/fort',
            'instagram_url' => 'https://instagram.com/fort',
            'linkedin_url' => '',
        ], $overrides);
    }

    public function test_form_shows_the_current_defaults_when_nothing_is_saved(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->get('/admin/settings')
            ->assertOk()
            ->assertSee(config('site.phone_short'))
            ->assertSee(config('site.service_area_tagline'));
    }

    public function test_admin_can_save_the_settings(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->put('/admin/settings', $this->validPayload())
            ->assertRedirect('/admin/settings')
            ->assertSessionHas('status');

        $settings = SiteSetting::sole();
        $this->assertSame('404-555-3325', $settings->phone_short);
        $this->assertSame('hello@example.com', $settings->email);
        $this->assertNull($settings->linkedin_url);
    }

    public function test_saving_twice_keeps_a_single_row(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->put('/admin/settings', $this->validPayload());
        $this->actingAs($admin)->put('/admin/settings', $this->validPayload(['email' => 'second@example.com']));

        $this->assertSame('second@example.com', SiteSetting::sole()->email);
    }

    public function test_saved_settings_appear_on_the_public_site(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->put('/admin/settings', $this->validPayload());

        $this->get('/')
            ->assertOk()
            ->assertSee('404-555-DECK (3325)')
            ->assertSee('href="tel:4045553325"', false)
            ->assertSee('hello@example.com')
            ->assertSee('Serving Cobb County')
            ->assertSee('https://instagram.com/fort')
            ->assertDontSee('linkedin.com', false);
    }

    public function test_public_site_uses_config_defaults_when_nothing_is_saved(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee(config('site.phone_display'))
            ->assertSee(config('site.email'));
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function invalidValues(): array
    {
        return [
            'missing phone' => ['phone_display', ''],
            'phone with letters in dial number' => ['phone_short', '470-887-FORT'],
            'bad email' => ['email', 'not-an-email'],
            'missing service area' => ['service_area', ''],
            'missing tagline' => ['service_area_tagline', ''],
            'bad facebook url' => ['facebook_url', 'facebook'],
            'javascript url' => ['instagram_url', 'javascript:alert(1)'],
            'too long service area' => ['service_area', str_repeat('a', 101)],
        ];
    }

    #[DataProvider('invalidValues')]
    public function test_invalid_values_are_rejected(string $field, string $value): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->put('/admin/settings', $this->validPayload([$field => $value]))
            ->assertSessionHasErrors($field);

        $this->assertDatabaseCount('site_settings', 0);
    }

    public function test_non_admin_cannot_change_the_settings(): void
    {
        $this->actingAs(User::factory()->create())
            ->put('/admin/settings', $this->validPayload())
            ->assertForbidden();

        $this->assertDatabaseCount('site_settings', 0);
    }
}
