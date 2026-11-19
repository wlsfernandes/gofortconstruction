<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ContactStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateContactSubmissionStatusRequest;
use App\Models\ContactSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactSubmissionController extends Controller
{
    private const PER_PAGE = 25;

    public function index(Request $request): View
    {
        $statusFilter = ContactStatus::tryFrom((string) $request->query('status'));

        return view('admin.contact-submissions.index', [
            'contactSubmissions' => ContactSubmission::query()
                ->when($statusFilter, fn ($query, ContactStatus $status) => $query->where('status', $status))
                ->latest()
                ->paginate(self::PER_PAGE)
                ->withQueryString(),
            'statusFilter' => $statusFilter,
        ]);
    }

    public function show(ContactSubmission $contactSubmission): View
    {
        return view('admin.contact-submissions.show', ['contactSubmission' => $contactSubmission]);
    }

    public function update(UpdateContactSubmissionStatusRequest $request, ContactSubmission $contactSubmission): RedirectResponse
    {
        // status is deliberately not mass assignable (the public form must not set it).
        $contactSubmission->status = ContactStatus::from($request->validated('status'));
        $contactSubmission->save();

        return redirect()
            ->route('admin.contact-submissions.show', $contactSubmission)
            ->with('status', 'Status changed to '.$contactSubmission->status->label().'.');
    }
}
