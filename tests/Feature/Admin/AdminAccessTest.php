<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, array{string}>
     */
    public static function adminPaths(): array
    {
        return [
            'dashboard' => ['/admin'],
            'estimate requests' => ['/admin/contact-submissions'],
            'projects' => ['/admin/projects'],
            'new project' => ['/admin/projects/create'],
            'blog posts' => ['/admin/blog-posts'],
            'testimonials' => ['/admin/testimonials'],
            'faqs' => ['/admin/faqs'],
            'service areas' => ['/admin/service-areas'],
        ];
    }

    #[DataProvider('adminPaths')]
    public function test_guest_is_sent_to_login(string $path): void
    {
        $this->get($path)->assertRedirect('/login');
    }

    #[DataProvider('adminPaths')]
    public function test_signed_in_user_without_admin_flag_is_forbidden(string $path): void
    {
        $this->actingAs(User::factory()->create())->get($path)->assertForbidden();
    }

    #[DataProvider('adminPaths')]
    public function test_admin_can_open_page(string $path): void
    {
        $this->actingAs(User::factory()->admin()->create())->get($path)->assertOk();
    }

    public function test_non_admin_cannot_change_content(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/admin/service-areas', ['city' => 'Atlanta', 'state' => 'Georgia', 'is_primary' => '0'])
            ->assertForbidden();

        $this->assertDatabaseMissing('service_areas', ['city' => 'Atlanta']);
    }
}
