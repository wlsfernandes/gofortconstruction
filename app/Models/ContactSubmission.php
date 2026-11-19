<?php

namespace App\Models;

use App\Enums\ContactProjectType;
use App\Enums\ContactStatus;
use App\Enums\PreferredContact;
use Database\Factories\ContactSubmissionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'email', 'phone', 'project_type', 'message', 'preferred_contact', 'source'])]
class ContactSubmission extends Model
{
    /** @use HasFactory<ContactSubmissionFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'project_type' => ContactProjectType::class,
            'preferred_contact' => PreferredContact::class,
            'status' => ContactStatus::class,
        ];
    }
}
