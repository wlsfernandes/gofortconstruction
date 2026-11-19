<?php

namespace Tests\Feature\Database;

use App\Models\BlogPost;
use App\Models\Faq;
use App\Models\Project;
use App\Models\ServiceArea;
use App\Models\Testimonial;
use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('auth.initial_admin', [
            'name' => 'Owner',
            'email' => 'owner@example.com',
            'password' => 'initial-admin-password',
        ]);
    }

    public function test_seeding_creates_the_administrator_from_config(): void
    {
        $this->seed(DatabaseSeeder::class);

        $administrator = User::where('email', 'owner@example.com')->sole();
        $this->assertTrue($administrator->is_admin);
        $this->assertSame('Owner', $administrator->name);
        $this->assertTrue(Hash::check('initial-admin-password', $administrator->password));
    }

    public function test_seeding_loads_the_prototype_sample_content(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertSame(16, ServiceArea::count());
        $this->assertSame(5, ServiceArea::where('is_primary', true)->count());
        $this->assertSame(9, Faq::count());
        $this->assertSame(8, Project::count());
        $this->assertSame(4, Project::where('is_featured', true)->count());
        $this->assertSame(6, Testimonial::count());
        $this->assertSame(3, Testimonial::where('is_featured', true)->count());
        $this->assertSame(4, BlogPost::where('is_published', true)->count());
    }

    public function test_seeding_twice_does_not_duplicate_rows(): void
    {
        $this->seed(DatabaseSeeder::class);
        $this->seed(DatabaseSeeder::class);

        $this->assertSame(1, User::count());
        $this->assertSame(16, ServiceArea::count());
        $this->assertSame(9, Faq::count());
        $this->assertSame(8, Project::count());
        $this->assertSame(6, Testimonial::count());
        $this->assertSame(4, BlogPost::count());
    }

    public function test_sample_content_is_not_seeded_in_production(): void
    {
        $this->app['env'] = 'production';

        $this->app->make(DatabaseSeeder::class)->__invoke();

        $this->assertSame(1, User::count());
        $this->assertSame(0, Project::count());
        $this->assertSame(0, Testimonial::count());
        $this->assertSame(0, BlogPost::count());
    }

    public function test_admin_seeder_refuses_to_run_without_credentials(): void
    {
        config()->set('auth.initial_admin.password', null);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('ADMIN_EMAIL and ADMIN_PASSWORD');

        $this->app->make(AdminUserSeeder::class)->__invoke();
    }
}
