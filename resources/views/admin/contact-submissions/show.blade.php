<x-layouts.admin :title="'Request from '.$contactSubmission->name">
    <x-slot:actions>
        <a href="{{ route('admin.contact-submissions.index') }}" class="text-sm text-stone-600 hover:underline">Back to all requests</a>
    </x-slot:actions>

    <div class="grid gap-6 lg:grid-cols-3">
        <dl class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-stone-200 lg:col-span-2">
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-semibold uppercase text-stone-500">Phone</dt>
                    <dd class="mt-1"><a href="tel:{{ $contactSubmission->phone }}" class="text-orange-600 hover:underline">{{ $contactSubmission->phone }}</a></dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase text-stone-500">Email</dt>
                    <dd class="mt-1">
                        @if ($contactSubmission->email)
                            <a href="mailto:{{ $contactSubmission->email }}" class="text-orange-600 hover:underline">{{ $contactSubmission->email }}</a>
                        @else
                            —
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase text-stone-500">Project type</dt>
                    <dd class="mt-1">{{ $contactSubmission->project_type?->label() ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase text-stone-500">Prefers</dt>
                    <dd class="mt-1">{{ ucfirst($contactSubmission->preferred_contact->value) }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase text-stone-500">Received</dt>
                    <dd class="mt-1">{{ $contactSubmission->created_at->format('F j, Y g:i a') }}</dd>
                </div>
            </div>
            <div class="mt-6">
                <dt class="text-xs font-semibold uppercase text-stone-500">Project details</dt>
                <dd class="mt-1 whitespace-pre-line text-stone-800">{{ $contactSubmission->message ?? '—' }}</dd>
            </div>
        </dl>

        <form method="POST" action="{{ route('admin.contact-submissions.update', $contactSubmission) }}" class="h-fit rounded-xl bg-white p-6 shadow-sm ring-1 ring-stone-200">
            @csrf
            @method('PATCH')
            <x-admin.select
                name="status"
                label="Status"
                :options="collect(\App\Enums\ContactStatus::cases())->mapWithKeys(fn ($status) => [$status->value => $status->label()])->all()"
                :value="$contactSubmission->status->value"
            />
            <button type="submit" class="w-full rounded-lg bg-orange-500 px-4 py-2 text-sm font-semibold text-white hover:bg-orange-600">Update status</button>
        </form>
    </div>
</x-layouts.admin>
