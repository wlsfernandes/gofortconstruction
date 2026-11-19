<x-layouts.site title="Projects">
    <x-page-hero
        image="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1920&q=80"
        image-alt="Projects"
        eyebrow="Our Portfolio"
        title="Featured Projects"
    >
        <p class="mx-auto max-w-3xl text-xl text-gray-300">
            Browse our collection of completed projects showcasing our craftsmanship and attention to detail.
        </p>
    </x-page-hero>

    <div x-data="{
        activeCategory: 'all',
        projectCategories: @js($projects->map(fn ($project) => $project->category->value)->values()),
    }">
        {{-- Filter Tabs --}}
        <section class="sticky top-[72px] z-40 border-b border-gray-100 bg-white shadow-sm">
            <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex gap-2 overflow-x-auto pb-2">
                    <button
                        type="button"
                        x-on:click="activeCategory = 'all'"
                        class="whitespace-nowrap rounded-full px-5 py-2 text-sm font-medium transition-all"
                        x-bind:class="activeCategory === 'all' ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                    >All Projects</button>
                    @foreach (\App\Enums\ProjectCategory::cases() as $category)
                        <button
                            type="button"
                            x-on:click="activeCategory = @js($category->value)"
                            class="whitespace-nowrap rounded-full px-5 py-2 text-sm font-medium transition-all"
                            x-bind:class="activeCategory === @js($category->value) ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                        >{{ $category->label() }}</button>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Projects Grid --}}
        <section class="py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                @if ($projects->isEmpty())
                    <div class="py-16 text-center">
                        <p class="text-lg text-gray-500">No projects yet.</p>
                    </div>
                @else
                    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                        @foreach ($projects as $project)
                            <div x-show="activeCategory === 'all' || activeCategory === @js($project->category->value)">
                                <a
                                    href="{{ route('projects.show', $project) }}"
                                    class="group block overflow-hidden rounded-2xl bg-white shadow-sm transition-all duration-300 hover:shadow-xl"
                                >
                                    <div class="relative h-64 overflow-hidden">
                                        @if ($project->featured_image)
                                            <img
                                                src="{{ $project->featured_image }}"
                                                alt="{{ $project->title }}"
                                                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                            >
                                        @else
                                            <div class="h-full w-full bg-gray-200"></div>
                                        @endif
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 transition-opacity group-hover:opacity-100"></div>
                                        <span class="absolute left-4 top-4 rounded-full bg-orange-500 px-3 py-1 text-xs font-semibold capitalize text-white">
                                            {{ str_replace('-', ' ', $project->category->value) }}
                                        </span>
                                    </div>
                                    <div class="p-6">
                                        <h3 class="mb-2 text-xl font-bold text-gray-900 transition-colors group-hover:text-orange-500">
                                            {{ $project->title }}
                                        </h3>
                                        @if ($project->location)
                                            <div class="mb-3 flex items-center gap-2 text-sm text-gray-500">
                                                <x-icon name="map-pin" class="size-4" />
                                                <span>{{ $project->location }}, GA</span>
                                            </div>
                                        @endif
                                        @if ($project->short_description)
                                            <p class="mb-4 line-clamp-2 text-sm text-gray-600">
                                                {{ $project->short_description }}
                                            </p>
                                        @endif
                                        <span class="inline-flex items-center text-sm font-semibold text-orange-500 transition-colors group-hover:text-orange-600">
                                            View Project
                                            <x-icon name="arrow-right" class="ml-2 size-4 transition-transform group-hover:translate-x-1" />
                                        </span>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>

                    <div
                        x-show="activeCategory !== 'all' && ! projectCategories.includes(activeCategory)"
                        x-cloak
                        class="py-16 text-center"
                    >
                        <p class="text-lg text-gray-500">No projects found in this category.</p>
                    </div>
                @endif
            </div>
        </section>
    </div>

    {{-- CTA Section --}}
    <section class="bg-gray-50 py-20">
        <div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
            <h2 class="mb-6 text-3xl font-bold text-gray-900 md:text-4xl">
                Love What You See?
            </h2>
            <p class="mb-8 text-xl text-gray-600">
                Let's discuss how we can create something amazing for your home.
            </p>
            <x-button-link :href="route('contact')" variant="primary" size="lg">
                Start Your Project
                <x-icon name="arrow-right" class="size-4" />
            </x-button-link>
        </div>
    </section>
</x-layouts.site>
