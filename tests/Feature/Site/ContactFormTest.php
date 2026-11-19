<?php

namespace Tests\Feature\Site;

use App\Enums\ContactProjectType;
use App\Enums\ContactStatus;
use App\Models\ContactSubmission;
use App\Notifications\ContactSubmissionReceived;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, string>
     */
    private function validSubmission(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Jordan Smith',
            'phone' => '(470) 555-0123',
            'email' => 'jordan@example.com',
            'project_type' => 'kitchen',
            'message' => 'We want a new kitchen island.',
        ], $overrides);
    }

    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();
        config()->set('site.contact_recipient', 'estimates@example.com');
    }

    public function test_contact_page_shows_the_estimate_form(): void
    {
        $this->get('/contact')
            ->assertOk()
            ->assertSee('action="'.route('contact.store').'"', false)
            ->assertSee('name="_token"', false)
            ->assertSee('Request Free Estimate')
            ->assertSee('id="request-estimate"', false);
    }

    public function test_valid_submission_is_stored_as_new(): void
    {
        $this->post('/contact', $this->validSubmission())
            ->assertRedirect(route('contact').'#request-estimate')
            ->assertSessionHas('contactSubmitted', true);

        $submission = ContactSubmission::sole();
        $this->assertSame('Jordan Smith', $submission->name);
        $this->assertSame('(470) 555-0123', $submission->phone);
        $this->assertSame('jordan@example.com', $submission->email);
        $this->assertSame(ContactProjectType::Kitchen, $submission->project_type);
        $this->assertSame(ContactStatus::New, $submission->fresh()->status);
    }

    public function test_thank_you_message_shows_after_submitting(): void
    {
        $this->followingRedirects()
            ->post('/contact', $this->validSubmission())
            ->assertOk()
            ->assertSee('Thank You!')
            ->assertSee('received your request and will contact you within 24 hours');
    }

    public function test_business_is_emailed_about_the_submission(): void
    {
        $this->post('/contact', $this->validSubmission());

        $submission = ContactSubmission::sole();
        Notification::assertSentOnDemand(
            ContactSubmissionReceived::class,
            function (ContactSubmissionReceived $notification, array $channels, AnonymousNotifiable $notifiable) use ($submission): bool {
                return $notifiable->routes['mail'] === 'estimates@example.com'
                    && $notification->contactSubmission->is($submission);
            },
        );
    }

    public function test_notification_email_lists_the_submission_details_and_replies_to_the_client(): void
    {
        $submission = ContactSubmission::factory()->create($this->validSubmission());

        $mail = (new ContactSubmissionReceived($submission))->toMail(new AnonymousNotifiable);
        $renderedMail = (string) $mail->render();

        $this->assertStringContainsString('Jordan Smith', $renderedMail);
        $this->assertStringContainsString('(470) 555-0123', $renderedMail);
        $this->assertStringContainsString('Kitchen Remodeling', $renderedMail);
        $this->assertSame([['jordan@example.com', 'Jordan Smith']], $mail->replyTo);
    }

    public function test_notification_is_queued(): void
    {
        $this->assertContains(
            ShouldQueue::class,
            class_implements(ContactSubmissionReceived::class),
        );
    }

    public function test_only_name_and_phone_are_required(): void
    {
        $this->post('/contact', ['name' => 'Jordan Smith', 'phone' => '470-555-0123'])
            ->assertSessionHasNoErrors();

        $this->assertSame(1, ContactSubmission::count());
    }

    public function test_name_and_phone_are_required(): void
    {
        $this->from('/contact')
            ->post('/contact', $this->validSubmission(['name' => '', 'phone' => '']))
            ->assertRedirect(route('contact').'#request-estimate')
            ->assertSessionHasErrors(['name', 'phone']);

        $this->assertSame(0, ContactSubmission::count());
        Notification::assertNothingSent();
    }

    public function test_invalid_email_and_project_type_are_rejected(): void
    {
        $this->post('/contact', $this->validSubmission(['email' => 'not-an-email', 'project_type' => 'swimming-pool']))
            ->assertSessionHasErrors(['email', 'project_type']);

        $this->assertSame(0, ContactSubmission::count());
    }

    public function test_overlong_fields_are_rejected(): void
    {
        $this->post('/contact', $this->validSubmission([
            'name' => Str::repeat('a', 256),
            'phone' => Str::repeat('1', 51),
            'message' => Str::repeat('m', 5001),
        ]))->assertSessionHasErrors(['name', 'phone', 'message']);
    }

    public function test_validation_errors_keep_the_entered_values(): void
    {
        $this->followingRedirects()
            ->from('/contact')
            ->post('/contact', $this->validSubmission(['phone' => '', 'message' => 'Keep my deck notes']))
            ->assertSee('Keep my deck notes')
            ->assertSee('The phone field is required.');
    }

    public function test_status_cannot_be_set_by_the_visitor(): void
    {
        $this->post('/contact', $this->validSubmission(['status' => 'completed']));

        $this->assertSame(ContactStatus::New, ContactSubmission::sole()->fresh()->status);
    }

    public function test_submissions_are_rate_limited_per_visitor(): void
    {
        foreach (range(1, 5) as $attempt) {
            $this->post('/contact', $this->validSubmission())->assertRedirect();
        }

        $this->post('/contact', $this->validSubmission())->assertTooManyRequests();

        $this->assertSame(5, ContactSubmission::count());
    }
}
