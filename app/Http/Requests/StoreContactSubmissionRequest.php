<?php

namespace App\Http\Requests;

use App\Enums\ContactProjectType;
use App\Http\Controllers\Site\ContactSubmissionController;
use App\Rules\LatinScriptOnly;
use App\Rules\NoSpamContent;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;

class StoreContactSubmissionRequest extends FormRequest
{
    /** Hidden field people never see; bots that fill every input fill it too. */
    public const HONEYPOT_FIELD = 'website_address';

    /** Encrypted time the form was rendered, so it cannot be forged. */
    public const STARTED_AT_FIELD = 'form_started_at';

    /** People need longer than this to fill in the form; bots post at once. */
    private const MINIMUM_SECONDS_TO_FILL = 3;

    /**
     * Value for the form's start time field: the current time, encrypted.
     */
    public static function startedAtToken(): string
    {
        return Crypt::encryptString((string) now()->timestamp);
    }

    /**
     * The estimate form is public; anyone may submit it.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', new LatinScriptOnly],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'project_type' => ['nullable', Rule::enum(ContactProjectType::class)],
            'message' => ['nullable', 'string', 'max:5000', new LatinScriptOnly, new NoSpamContent],
        ];
    }

    /**
     * Send the visitor back to the form itself, not the top of the page.
     */
    protected function getRedirectUrl(): string
    {
        return route('contact').'#'.ContactSubmissionController::FORM_ANCHOR;
    }

    /**
     * Why the submission looks automated, or null when it looks like a person.
     * The caller shows a normal thank-you page for these, so bots learn nothing.
     */
    public function likelyBotReason(): ?string
    {
        if ($this->filled(self::HONEYPOT_FIELD)) {
            return 'honeypot filled';
        }

        $startedAt = $this->decryptedStartedAt();

        if ($startedAt === null) {
            return 'missing or forged start time';
        }

        if (now()->timestamp - $startedAt < self::MINIMUM_SECONDS_TO_FILL) {
            return 'submitted too fast';
        }

        return null;
    }

    private function decryptedStartedAt(): ?int
    {
        $startedAtToken = $this->input(self::STARTED_AT_FIELD);

        if (! is_string($startedAtToken) || $startedAtToken === '') {
            return null;
        }

        try {
            $startedAt = Crypt::decryptString($startedAtToken);
        } catch (DecryptException) {
            // A value that does not decrypt was not made by this app: treat it as forged.
            return null;
        }

        return ctype_digit($startedAt) ? (int) $startedAt : null;
    }
}
