<?php

namespace App\Http\Controllers\Site;

use App\Actions\Contact\RecordContactSubmission;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactSubmissionRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class ContactSubmissionController extends Controller
{
    /** Element id of the estimate card, so the visitor lands on the result. */
    public const FORM_ANCHOR = 'request-estimate';

    public function store(StoreContactSubmissionRequest $request, RecordContactSubmission $recordContactSubmission): RedirectResponse
    {
        $likelyBotReason = $request->likelyBotReason();

        if ($likelyBotReason !== null) {
            Log::notice('Contact submission discarded as likely bot.', ['reason' => $likelyBotReason]);
        } else {
            $recordContactSubmission->handle($request->validated());
        }

        // Bots get the same thank-you page as people, so they cannot tell they were caught.
        return redirect()->to(route('contact').'#'.self::FORM_ANCHOR)->with('contactSubmitted', true);
    }
}
