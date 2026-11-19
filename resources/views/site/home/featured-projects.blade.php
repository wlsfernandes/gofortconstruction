@php
    $largeFeaturedProjects = $featuredProjects->take(2);
    $smallFeaturedProjects = $featuredProjects->slice(2, 4);
@endphp

<section class="bg-gray-50 py-20 md:py-28">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-section-header
            subtitle="Our Work"
            title="Featured Projects"
            description="Browse our portfolio of completed projects showcasing our craftsmanship and attention to detail."
        />

        @if ($featuredProjects->isEmpty())
            <p class="text-center text-gray-600">Projects coming soon.</p>
        @else
            <div class="grid gap-8 md:grid-cols-2">
                @foreach ($largeFeaturedProjects as $project)
                    <a href="{{ route('projects.show', $project) }}" class="group relative block h-96 overflow-hidden rounded-2xl bg-gray-200">
                        @if ($project->featured_image)
                            <img
                                src="{{ $project->featured_image }}"
                                alt="{{ $project->title }}"
                                class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                            >
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-8">
                            <span class="mb-3 inline-block rounded-full bg-orange-500 px-3 py-1 text-xs font-semibold capitalize text-white">
                                {{ str_replace('-', ' ', $project->category->value) }}
                            </span>
                            <h3 class="mb-2 text-2xl font-bold text-white">{{ $project->title }}</h3>
                            @if ($project->location)
                                <div class="flex items-center gap-2 text-gray-300">
                                    <x-icon name="map-pin" class="size-4" />
                                    <span>{{ $project->location }}, GA</span>
                                </div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>

            @if ($smallFeaturedProjects->isNotEmpty())
                <div class="mt-8 grid gap-6 md:grid-cols-4">
                    @foreach ($smallFeaturedProjects as $project)
                        <a href="{{ route('projects.show', $project) }}" class="group relative block h-64 overflow-hidden rounded-xl bg-gray-200">
                            @if ($project->featured_image)
                                <img
                                    src="{{ $project->featured_image }}"
                                    alt="{{ $project->title }}"
                                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                >
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-4">
                                <h3 class="text-lg font-bold text-white">{{ $project->title }}</h3>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        @endif

        <div class="mt-12 text-center">
            <x-button-link :href="route('projects.index')" variant="outline" size="lg">
                View All Projects
                <x-icon name="arrow-right" class="ml-2 size-4" />
            </x-button-link>
        </div>
    </div>
</section>
