<?php

namespace Tests\Feature\Site;

use App\Enums\ContactProjectType;
use App\Enums\ContactStatus;
use App\Models\ContactSubmission;
use App\Notifications\ContactSubmissionReceived;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Anti-spam fields the form renders: an empty honeypot and a start time
     * one minute ago, so the submission does not look like an instant bot post.
     *
     * @return array<string, string>
     */
    private function antiSpamFields(): array
    {
        return [
            'website_address' => '',
            'form_started_at' => Crypt::encryptString((string) now()->subMinute()->timestamp),
        ];
    }

    /**
     * @return array<string, string>
     */
    private function submissionDetails(): array
    {
        return [
            'name' => 'Jordan Smith',
            'phone' => '(470) 555-0123',
            'email' => 'jordan@example.com',
            'project_type' => 'kitchen',
            'message' => 'We want a new kitchen island.',
        ];
    }

    /**
     * What the browser posts: the contact details plus the anti-spam fields.
     *
     * @return array<string, string>
     */
    private function validSubmission(array $overrides = []): array
    {
        return array_merge($this->antiSpamFields(), $this->submissionDetails(), $overrides);
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
        $submission = ContactSubmission::factory()->create($this->submissionDetails());

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
        $this->post('/contact', ['name' => 'Jordan Smith', 'phone' => '470-555-0123', ...$this->antiSpamFields()])
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

    public function test_form_renders_the_honeypot_and_start_time_fields(): void
    {
        $this->get('/contact')
            ->assertOk()
            ->assertSee('name="website_address"', false)
            ->assertSee('name="form_started_at"', false);
    }

    public function test_spanish_and_portuguese_messages_with_accents_are_accepted(): void
    {
        $this->post('/contact', $this->validSubmission([
            'name' => 'José Conceição Muñoz',
            'message' => '¿Pueden construir una terraza? Também quero reformar a cozinha, açúcar não. 😊 Budget $25,000 – Pinterest: https://pin.it/abc123',
        ]))->assertSessionHasNoErrors();

        $this->assertSame(1, ContactSubmission::count());
        Notification::assertCount(1);
    }

    public function test_messages_in_non_latin_alphabets_are_rejected_with_an_error(): void
    {
        $this->post('/contact', $this->validSubmission([
            'name' => 'Иван Петров',
            'message' => '我们提供最好的服务',
        ]))->assertSessionHasErrors([
            'name' => 'Please write your name in English, Spanish, or Portuguese.',
            'message' => 'Please write your message in English, Spanish, or Portuguese.',
        ]);

        $this->assertSame(0, ContactSubmission::count());
        Notification::assertNothingSent();
    }

    public function test_messages_with_blocked_words_are_rejected_with_an_error(): void
    {
        $this->post('/contact', $this->validSubmission(['message' => 'Invest in Bitcoin today']))
            ->assertSessionHasErrors(['message' => 'Your message looks like spam. Please describe your project.']);

        $this->assertSame(0, ContactSubmission::count());
    }

    public function test_blocked_words_only_match_whole_words_and_loans_are_allowed(): void
    {
        $this->post('/contact', $this->validSubmission([
            'message' => 'We plan to use a home equity loan. Our cryptographer neighbor recommended you.',
        ]))->assertSessionHasNoErrors();

        $this->assertSame(1, ContactSubmission::count());
    }

    public function test_long_runs_of_the_same_character_are_rejected_but_big_numbers_are_allowed(): void
    {
        $this->post('/contact', $this->validSubmission(['message' => 'cheap!!!!!!!!!! aaaaaaaa']))
            ->assertSessionHasErrors('message');

        $this->post('/contact', $this->validSubmission(['message' => 'Budget around 10000000 total']))
            ->assertSessionHasNoErrors();

        $this->assertSame(1, ContactSubmission::count());
    }

    public function test_filled_honeypot_shows_success_but_stores_and_sends_nothing(): void
    {
        $this->post('/contact', $this->validSubmission(['website_address' => 'https://spam.example']))
            ->assertRedirect(route('contact').'#request-estimate')
            ->assertSessionHas('contactSubmitted', true);

        $this->assertSame(0, ContactSubmission::count());
        Notification::assertNothingSent();
    }

    public function test_submission_sent_too_fast_shows_success_but_stores_nothing(): void
    {
        $this->post('/contact', $this->validSubmission([
            'form_started_at' => Crypt::encryptString((string) now()->subSecond()->timestamp),
        ]))->assertSessionHas('contactSubmitted', true);

        $this->assertSame(0, ContactSubmission::count());
        Notification::assertNothingSent();
    }

    public function test_missing_or_forged_start_time_shows_success_but_stores_nothing(): void
    {
        $this->post('/contact', $this->validSubmission(['form_started_at' => null]))
            ->assertSessionHas('contactSubmitted', true);

        $this->post('/contact', $this->validSubmission(['form_started_at' => (string) now()->subHour()->timestamp]))
            ->assertSessionHas('contactSubmitted', true);

        $this->assertSame(0, ContactSubmission::count());
        Notification::assertNothingSent();
    }

    public function test_form_keeps_the_original_start_time_after_a_validation_error(): void
    {
        $startedAtToken = Crypt::encryptString((string) now()->subMinute()->timestamp);

        $this->followingRedirects()
            ->from('/contact')
            ->post('/contact', $this->validSubmission(['phone' => '', 'form_started_at' => $startedAtToken]))
            ->assertSee('value="'.$startedAtToken.'"', false);
    }
}
