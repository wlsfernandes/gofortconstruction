@php
    $lightboxImages = collect($project->gallery_images ?? [])
        ->push($project->featured_image)
        ->filter()
        ->values()
        ->all();
    $beforeImages = $project->before_images ?? [];
    $afterImages = $project->after_images ?? [];
    $galleryImages = $project->gallery_images ?? [];
    $hasTransformation = count($beforeImages) > 0 || count($afterImages) > 0;
    $hasLightboxImages = count($lightboxImages) > 0;
    $descriptionParagraphs = $project->description
        ? preg_split('/\R+/', trim($project->description))
        : [];
@endphp

<x-layouts.site :title="$project->title" :description="$project->short_description ?: null">
    <div
        x-data="{
            lightboxImages: @js($lightboxImages),
            isLightboxOpen: false,
            lightboxIndex: 0,
            isShowingAfter: true,
            openLightbox(index) {
                if (this.lightboxImages.length === 0) return;
                this.lightboxIndex = index;
                this.isLightboxOpen = true;
            },
            showPreviousImage() {
                this.lightboxIndex = (this.lightboxIndex - 1 + this.lightboxImages.length) % this.lightboxImages.length;
            },
            showNextImage() {
                this.lightboxIndex = (this.lightboxIndex + 1) % this.lightboxImages.length;
            },
        }"
        x-on:keydown.escape.window="isLightboxOpen = false"
    >
        {{-- Hero Image --}}
        <section class="relative h-[60vh] min-h-[400px] bg-gray-900">
            @if ($project->featured_image)
                <img
                    src="{{ $project->featured_image }}"
                    alt="{{ $project->title }}"
                    class="h-full w-full object-cover"
                >
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>

            <div class="absolute bottom-0 left-0 right-0 p-8 md:p-16">
                <div class="mx-auto max-w-7xl">
                    <a
                        href="{{ route('projects.index') }}"
                        class="mb-4 inline-flex items-center text-white/80 transition-colors hover:text-white"
                    >
                        <x-icon name="arrow-left" class="mr-2 size-4" />
                        Back to Projects
                    </a>
                    <span class="mb-4 block w-fit rounded-full bg-orange-500 px-3 py-1 text-sm font-semibold capitalize text-white">
                        {{ str_replace('-', ' ', $project->category->value) }}
                    </span>
                    <h1 class="mb-4 text-3xl font-bold text-white md:text-5xl">
                        {{ $project->title }}
                    </h1>
                    <div class="flex flex-wrap items-center gap-6 text-white/80">
                        @if ($project->location)
                            <div class="flex items-center gap-2">
                                <x-icon name="map-pin" class="size-5" />
                                <span>{{ $project->location }}, GA</span>
                            </div>
                        @endif
                        @if ($project->completion_date)
                            <div class="flex items-center gap-2">
                                <x-icon name="calendar" class="size-5" />
                                <span>Completed {{ $project->completion_date->format('F Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        {{-- Content --}}
        <section class="py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid gap-16 lg:grid-cols-3">
                    {{-- Main Content --}}
                    <div class="lg:col-span-2">
                        <h2 class="mb-6 text-2xl font-bold text-gray-900">Project Overview</h2>
                        <div class="max-w-none space-y-5 text-lg leading-relaxed text-gray-600">
                            @foreach ($descriptionParagraphs as $paragraph)
                                <p>{{ $paragraph }}</p>
                            @endforeach
                        </div>

                        {{-- Before/After --}}
                        @if ($hasTransformation)
                            <div class="mt-12">
                                <div class="mb-6 flex items-center justify-between">
                                    <h2 class="text-2xl font-bold text-gray-900">Transformation</h2>
                                    <div class="flex rounded-full bg-gray-100 p-1">
                                        <button
                                            type="button"
                                            x-on:click="isShowingAfter = false"
                                            class="rounded-full px-4 py-2 text-sm font-medium transition-colors"
                                            x-bind:class="! isShowingAfter ? 'bg-orange-500 text-white' : 'text-gray-600'"
                                        >Before</button>
                                        <button
                                            type="button"
                                            x-on:click="isShowingAfter = true"
                                            class="rounded-full px-4 py-2 text-sm font-medium transition-colors"
                                            x-bind:class="isShowingAfter ? 'bg-orange-500 text-white' : 'text-gray-600'"
                                        >After</button>
                                    </div>
                                </div>

                                <div x-show="! isShowingAfter" x-cloak class="grid gap-4 sm:grid-cols-2">
                                    @foreach ($beforeImages as $image)
                                        <img
                                            src="{{ $image }}"
                                            alt="Before {{ $loop->iteration }}"
                                            @class(['h-64 w-full rounded-xl object-cover', 'cursor-pointer transition-opacity hover:opacity-90' => $hasLightboxImages])
                                            x-on:click="openLightbox(0)"
                                        >
                                    @endforeach
                                </div>
                                <div x-show="isShowingAfter" class="grid gap-4 sm:grid-cols-2">
                                    @foreach ($afterImages as $image)
                                        <img
                                            src="{{ $image }}"
                                            alt="After {{ $loop->iteration }}"
                                            @class(['h-64 w-full rounded-xl object-cover', 'cursor-pointer transition-opacity hover:opacity-90' => $hasLightboxImages])
                                            x-on:click="openLightbox(0)"
                                        >
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Gallery --}}
                        @if (count($galleryImages) > 0)
                            <div class="mt-12">
                                <h2 class="mb-6 text-2xl font-bold text-gray-900">Project Gallery</h2>
                                <div class="grid grid-cols-2 gap-4 md:grid-cols-3">
                                    @foreach ($galleryImages as $image)
                                        <img
                                            src="{{ $image }}"
                                            alt="Gallery {{ $loop->iteration }}"
                                            class="h-48 w-full cursor-pointer rounded-xl object-cover transition-opacity hover:opacity-90"
                                            x-on:click="openLightbox({{ $loop->index }})"
                                        >
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Sidebar --}}
                    <div>
                        @if ($project->testimonial)
                            <div class="mb-8 rounded-2xl bg-gray-50 p-8">
                                <x-icon name="quote" class="mb-4 size-10 text-orange-200" />
                                <p class="mb-4 italic text-gray-700">
                                    "{{ $project->testimonial }}"
                                </p>
                                @if ($project->client_name)
                                    <div class="font-semibold text-gray-900">
                                        — {{ $project->client_name }}
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div class="rounded-2xl bg-gray-900 p-8 text-center text-white">
                            <h3 class="mb-4 text-xl font-bold">Love This Project?</h3>
                            <p class="mb-6 text-gray-300">
                                Let's create something amazing for your home.
                            </p>
                            <x-button-link :href="route('contact')" variant="primary" class="w-full">
                                Get Free Estimate
                            </x-button-link>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Lightbox --}}
        @if ($hasLightboxImages)
            <div
                x-show="isLightboxOpen"
                x-cloak
                x-transition.opacity
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/95 p-4"
                x-on:click="isLightboxOpen = false"
                role="dialog"
                aria-modal="true"
                aria-label="Project image viewer"
            >
                <button
                    type="button"
                    x-on:click="isLightboxOpen = false"
                    class="absolute right-4 top-4 p-2 text-white/80 hover:text-white"
                    aria-label="Close"
                >
                    <x-icon name="x" class="size-8" />
                </button>

                <button
                    type="button"
                    x-on:click.stop="showPreviousImage()"
                    class="absolute left-4 p-2 text-white/80 hover:text-white"
                    aria-label="Previous image"
                >
                    <x-icon name="chevron-left" class="size-10" />
                </button>

                <img
                    x-bind:src="lightboxImages[lightboxIndex]"
                    alt="Gallery"
                    class="max-h-[80vh] max-w-[90vw] object-contain"
                    x-on:click.stop
                >

                <button
                    type="button"
                    x-on:click.stop="showNextImage()"
                    class="absolute right-4 p-2 text-white/80 hover:text-white"
                    aria-label="Next image"
                >
                    <x-icon name="chevron-right" class="size-10" />
                </button>

                <div class="absolute bottom-4 text-sm text-white/60">
                    <span x-text="lightboxIndex + 1"></span> / <span x-text="lightboxImages.length"></span>
                </div>
            </div>
        @endif
    </div>
</x-layouts.site>
