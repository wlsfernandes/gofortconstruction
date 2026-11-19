<?php

namespace Tests\Feature\Database;

use App\Enums\ContactStatus;
use App\Enums\PreferredContact;
use App\Models\BlogPost;
use App\Models\ContactSubmission;
use App\Models\Project;
use App\Models\ServiceArea;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SchemaConstraintsTest extends TestCase
{
    use RefreshDatabase;

    public function test_project_slugs_must_be_unique(): void
    {
        Project::factory()->create(['slug' => 'modern-deck']);

        $this->expectException(UniqueConstraintViolationException::class);

        Project::factory()->create(['slug' => 'modern-deck']);
    }

    public function test_blog_post_slugs_must_be_unique(): void
    {
        BlogPost::factory()->create(['slug' => 'deck-trends']);

        $this->expectException(UniqueConstraintViolationException::class);

        BlogPost::factory()->create(['slug' => 'deck-trends']);
    }

    public function test_a_city_can_only_be_listed_once_per_state(): void
    {
        ServiceArea::factory()->create(['city' => 'Cumming', 'state' => 'Georgia']);

        $this->expectException(UniqueConstraintViolationException::class);

        ServiceArea::factory()->create(['city' => 'Cumming', 'state' => 'Georgia']);
    }

    public function test_database_rejects_unknown_project_category(): void
    {
        $this->expectException(QueryException::class);

        DB::table('projects')->insert(['title' => 'Pool', 'slug' => 'pool', 'category' => 'swimming-pool']);
    }

    public function test_database_rejects_unknown_contact_status(): void
    {
        $this->expectException(QueryException::class);

        DB::table('contact_submissions')->insert(['name' => 'Ana', 'phone' => '555-0100', 'status' => 'spam']);
    }

    public function test_contact_submission_gets_default_status_and_contact_preference(): void
    {
        $submission = ContactSubmission::create(['name' => 'Ana', 'phone' => '555-0100'])->fresh();

        $this->assertSame(ContactStatus::New, $submission->status);
        $this->assertSame(PreferredContact::Phone, $submission->preferred_contact);
    }

    public function test_project_image_lists_default_to_empty_arrays(): void
    {
        DB::table('projects')->insert(['title' => 'Deck', 'slug' => 'deck', 'category' => 'deck']);

        $project = Project::where('slug', 'deck')->sole();

        $this->assertSame([], $project->gallery_images);
        $this->assertSame([], $project->tags);
    }

    public function test_contact_status_cannot_be_mass_assigned(): void
    {
        $submission = ContactSubmission::create(['name' => 'Ana', 'phone' => '555-0100', 'status' => 'completed'])->fresh();

        $this->assertSame(ContactStatus::New, $submission->status);
    }

    public function test_admin_flag_cannot_be_mass_assigned(): void
    {
        $user = User::create(['name' => 'Ana', 'email' => 'ana@example.com', 'password' => 'secret-password', 'is_admin' => true])->fresh();

        $this->assertFalse($user->is_admin);
    }

    public function test_content_migrations_roll_back_and_reapply(): void
    {
        $contentTables = ['projects', 'blog_posts', 'testimonials', 'faqs', 'service_areas', 'contact_submissions', 'site_settings'];

        Artisan::call('migrate:rollback', ['--step' => 8]);

        foreach ($contentTables as $table) {
            $this->assertFalse(Schema::hasTable($table), "{$table} should be dropped");
        }
        $this->assertFalse(Schema::hasColumn('users', 'is_admin'));

        Artisan::call('migrate');

        foreach ($contentTables as $table) {
            $this->assertTrue(Schema::hasTable($table), "{$table} should be recreated");
        }
        $this->assertTrue(Schema::hasColumn('users', 'is_admin'));
    }
}
