<?php

namespace App\Http\Controllers\Site;

use App\Actions\Contact\RecordContactSubmission;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactSubmissionRequest;
use Illuminate\Http\RedirectResponse;

class ContactSubmissionController extends Controller
{
    /** Element id of the estimate card, so the visitor lands on the result. */
    public const FORM_ANCHOR = 'request-estimate';

    public function store(StoreContactSubmissionRequest $request, RecordContactSubmission $recordContactSubmission): RedirectResponse
    {
        $recordContactSubmission->handle($request->validated());

        return redirect()->to(route('contact').'#'.self::FORM_ANCHOR)->with('contactSubmitted', true);
    }
}
