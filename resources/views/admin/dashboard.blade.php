<x-layouts.admin title="Dashboard">
    @php
        $statistics = [
            ['label' => 'New estimate requests', 'value' => $newRequestCount, 'route' => route('admin.contact-submissions.index', ['status' => 'new'])],
            ['label' => 'Projects', 'value' => $projectCount, 'route' => route('admin.projects.index')],
            ['label' => 'Published blog posts', 'value' => $publishedPostCount, 'route' => route('admin.blog-posts.index')],
            ['label' => 'Testimonials', 'value' => $testimonialCount, 'route' => route('admin.testimonials.index')],
        ];
    @endphp

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($statistics as $statistic)
            <a href="{{ $statistic['route'] }}" class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-stone-200 hover:ring-orange-300">
                <div class="text-3xl font-bold">{{ $statistic['value'] }}</div>
                <div class="mt-1 text-sm text-stone-600">{{ $statistic['label'] }}</div>
            </a>
        @endforeach
    </div>

    <section class="mt-8 rounded-xl bg-white p-6 shadow-sm ring-1 ring-stone-200">
        <h2 class="mb-4 text-lg font-semibold">Newest requests</h2>
        @forelse ($recentNewRequests as $contactSubmission)
            <a href="{{ route('admin.contact-submissions.show', $contactSubmission) }}" class="flex items-center justify-between border-t border-stone-100 py-3 first:border-t-0 hover:text-orange-600">
                <span class="font-medium">{{ $contactSubmission->name }}</span>
                <span class="text-sm text-stone-500">{{ $contactSubmission->created_at->diffForHumans() }}</span>
            </a>
        @empty
            <p class="text-stone-500">No new requests.</p>
        @endforelse
    </section>
</x-layouts.admin>
