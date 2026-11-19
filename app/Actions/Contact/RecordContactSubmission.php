<?php

namespace App\Actions\Contact;

use App\Models\ContactSubmission;
use App\Notifications\ContactSubmissionReceived;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class RecordContactSubmission
{
    /**
     * Save an estimate request and email the business about it.
     *
     * @param  array{name: string, phone: string, email?: string|null, project_type?: string|null, message?: string|null}  $validatedInput
     */
    public function handle(array $validatedInput): ContactSubmission
    {
        $contactSubmission = ContactSubmission::create($validatedInput);

        Notification::route('mail', config('site.contact_recipient'))
            ->notify(new ContactSubmissionReceived($contactSubmission));

        Log::info('Contact submission received.', ['contact_submission_id' => $contactSubmission->id]);

        return $contactSubmission;
    }
}
