<?php

namespace App\Notifications;

use App\Models\ContactSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactSubmissionReceived extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public ContactSubmission $contactSubmission) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $submission = $this->contactSubmission;
        $notProvided = 'Not provided';

        $mail = (new MailMessage)
            ->subject('New estimate request from '.$submission->name)
            ->greeting('New estimate request')
            ->line('Name: '.$submission->name)
            ->line('Phone: '.$submission->phone)
            ->line('Email: '.($submission->email ?? $notProvided))
            ->line('Project type: '.($submission->project_type?->label() ?? $notProvided))
            ->line('Details: '.($submission->message ?? $notProvided));

        if ($submission->email !== null) {
            $mail->replyTo($submission->email, $submission->name);
        }

        return $mail;
    }
}
