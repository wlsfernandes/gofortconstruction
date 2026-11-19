<x-layouts.admin title="Estimate requests">
    <nav class="mb-4 flex flex-wrap gap-2 text-sm" aria-label="Filter by status">
        <a href="{{ route('admin.contact-submissions.index') }}" @class([
            'rounded-full px-3 py-1',
            'bg-stone-900 text-white' => $statusFilter === null,
            'bg-white text-stone-700 ring-1 ring-stone-200 hover:bg-stone-50' => $statusFilter !== null,
        ])>All</a>
        @foreach (\App\Enums\ContactStatus::cases() as $status)
            <a href="{{ route('admin.contact-submissions.index', ['status' => $status->value]) }}" @class([
                'rounded-full px-3 py-1',
                'bg-stone-900 text-white' => $statusFilter === $status,
                'bg-white text-stone-700 ring-1 ring-stone-200 hover:bg-stone-50' => $statusFilter !== $status,
            ])>{{ $status->label() }}</a>
        @endforeach
    </nav>

    <div class="overflow-x-auto rounded-xl bg-white shadow-sm ring-1 ring-stone-200">
        <table class="min-w-full divide-y divide-stone-200 text-sm">
            <thead class="bg-stone-50 text-left text-xs font-semibold uppercase tracking-wide text-stone-500">
                <tr>
                    <th scope="col" class="px-4 py-3">Received</th>
                    <th scope="col" class="px-4 py-3">Name</th>
                    <th scope="col" class="px-4 py-3">Phone</th>
                    <th scope="col" class="px-4 py-3">Project</th>
                    <th scope="col" class="px-4 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($contactSubmissions as $contactSubmission)
                    <tr class="hover:bg-stone-50">
                        <td class="whitespace-nowrap px-4 py-3 text-stone-600">{{ $contactSubmission->created_at->format('M j, Y g:i a') }}</td>
                        <td class="px-4 py-3 font-medium">
                            <a href="{{ route('admin.contact-submissions.show', $contactSubmission) }}" class="text-orange-600 hover:underline">{{ $contactSubmission->name }}</a>
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 text-stone-600">{{ $contactSubmission->phone }}</td>
                        <td class="px-4 py-3 text-stone-600">{{ $contactSubmission->project_type?->label() ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span @class([
                                'rounded-full px-2 py-0.5 text-xs font-semibold',
                                'bg-orange-100 text-orange-700' => $contactSubmission->status === \App\Enums\ContactStatus::New,
                                'bg-stone-100 text-stone-700' => $contactSubmission->status !== \App\Enums\ContactStatus::New,
                            ])>{{ $contactSubmission->status->label() }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-stone-500">No estimate requests{{ $statusFilter ? ' with status '.$statusFilter->label() : '' }}.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $contactSubmissions->links() }}</div>
</x-layouts.admin>
