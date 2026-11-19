<?php

namespace Tests\Feature\Admin;

use App\Enums\ContactStatus;
use App\Models\ContactSubmission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactSubmissionAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->admin()->create());
    }

    public function test_inbox_lists_requests_newest_first(): void
    {
        ContactSubmission::factory()->create(['name' => 'Older Client', 'created_at' => now()->subDay()]);
        ContactSubmission::factory()->create(['name' => 'Newer Client']);

        $this->get('/admin/contact-submissions')->assertOk()->assertSeeInOrder(['Newer Client', 'Older Client']);
    }

    public function test_inbox_filters_by_status(): void
    {
        ContactSubmission::factory()->create(['name' => 'Fresh Lead']);
        $quoted = ContactSubmission::factory()->create(['name' => 'Quoted Lead']);
        $quoted->forceFill(['status' => ContactStatus::Quoted])->save();

        $this->get('/admin/contact-submissions?status=quoted')
            ->assertSee('Quoted Lead')
            ->assertDontSee('Fresh Lead');
    }

    public function test_unknown_status_filter_shows_everything(): void
    {
        ContactSubmission::factory()->create(['name' => 'Fresh Lead']);

        $this->get('/admin/contact-submissions?status=bogus')->assertOk()->assertSee('Fresh Lead');
    }

    public function test_request_detail_shows_all_fields(): void
    {
        $submission = ContactSubmission::factory()->create([
            'name' => 'Jordan Smith',
            'phone' => '470-555-0100',
            'email' => 'jordan@example.com',
            'message' => 'Need a deck by spring.',
        ]);

        $this->get("/admin/contact-submissions/{$submission->id}")
            ->assertOk()
            ->assertSee('Jordan Smith')
            ->assertSee('470-555-0100')
            ->assertSee('jordan@example.com')
            ->assertSee('Need a deck by spring.');
    }

    public function test_admin_can_change_request_status(): void
    {
        $submission = ContactSubmission::factory()->create();

        $this->patch("/admin/contact-submissions/{$submission->id}", ['status' => 'contacted'])
            ->assertRedirect("/admin/contact-submissions/{$submission->id}");

        $this->assertSame(ContactStatus::Contacted, $submission->fresh()->status);
    }

    public function test_invalid_status_is_rejected(): void
    {
        $submission = ContactSubmission::factory()->create();

        $this->patch("/admin/contact-submissions/{$submission->id}", ['status' => 'spam'])
            ->assertSessionHasErrors('status');

        $this->assertSame(ContactStatus::New, $submission->fresh()->status);
    }

    public function test_dashboard_counts_new_requests(): void
    {
        ContactSubmission::factory()->count(2)->create();
        ContactSubmission::factory()->create(['name' => 'Already Handled'])->forceFill(['status' => ContactStatus::Completed])->save();

        $this->get('/admin')
            ->assertOk()
            ->assertSeeInOrder(['2', 'New estimate requests'])
            ->assertDontSee('Already Handled');
    }
}
