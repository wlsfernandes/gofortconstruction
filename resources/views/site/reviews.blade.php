@php
    use App\Enums\TestimonialSource;

    $maximumRating = 5;
    $reviewCount = $testimonials->count();
    $averageRating = $reviewCount > 0 ? (float) $testimonials->avg('rating') : 0.0;
    $averageRatingStars = (int) round($averageRating);
@endphp

<x-layouts.site title="Reviews">
    {{-- Hero Section --}}
    <x-page-hero
        image="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1920&q=80"
        image-alt="Reviews"
        eyebrow="Testimonials"
        title="What Our Clients Say"
    >
        <p class="mx-auto max-w-3xl text-xl text-gray-300">
            Don't just take our word for it. See what homeowners throughout North Atlanta say about working with Fort Construction.
        </p>
    </x-page-hero>

    {{-- Overall Rating --}}
    <section class="border-b bg-white py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center justify-center gap-8 md:flex-row">
                @if ($reviewCount > 0)
                    <div class="text-center md:text-left">
                        <div class="text-6xl font-bold text-gray-900">{{ number_format($averageRating, 1) }}</div>
                        <div class="mt-2 flex justify-center gap-1 md:justify-start">
                            @for ($starIndex = 1; $starIndex <= $maximumRating; $starIndex++)
                                <x-icon
                                    name="star"
                                    :class="Arr::toCssClasses([
                                        'size-6',
                                        'fill-yellow-400 text-yellow-400' => $starIndex <= $averageRatingStars,
                                        'text-gray-300' => $starIndex > $averageRatingStars,
                                    ])"
                                />
                            @endfor
                        </div>
                        <div class="mt-2 text-gray-500">Based on {{ $reviewCount }} {{ Str::plural('review', $reviewCount) }}</div>
                    </div>
                    <div class="hidden h-16 w-px bg-gray-200 md:block"></div>
                @endif
                <div class="text-center">
                    <a
                        href="https://google.com/maps"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-2 rounded-full bg-blue-50 px-6 py-3 font-semibold text-blue-600 transition-colors hover:bg-blue-100"
                    >
                        <img src="https://www.google.com/favicon.ico" alt="Google" class="size-5">
                        See Our Google Reviews
                        <x-icon name="external-link" class="size-4" />
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Testimonials Grid --}}
    <section class="bg-gray-50 py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if ($testimonials->isEmpty())
                <p class="text-center text-lg text-gray-500">No reviews yet. Check back soon.</p>
            @else
                <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($testimonials as $testimonial)
                        <div class="rounded-2xl bg-white p-8 shadow-sm transition-shadow hover:shadow-lg">
                            <x-icon name="quote" class="mb-4 size-10 text-orange-100" />

                            <div class="mb-4 flex gap-1">
                                @for ($starIndex = 1; $starIndex <= $maximumRating; $starIndex++)
                                    <x-icon
                                        name="star"
                                        :class="Arr::toCssClasses([
                                            'size-5',
                                            'fill-yellow-400 text-yellow-400' => $starIndex <= $testimonial->rating,
                                            'text-gray-300' => $starIndex > $testimonial->rating,
                                        ])"
                                    />
                                @endfor
                            </div>

                            <p class="mb-6 leading-relaxed text-gray-600">
                                "{{ $testimonial->content }}"
                            </p>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="flex size-12 items-center justify-center rounded-full bg-orange-100">
                                        <span class="text-lg font-bold text-orange-600">
                                            {{ Str::substr($testimonial->client_name, 0, 1) }}
                                        </span>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $testimonial->client_name }}</div>
                                        <div class="text-sm text-gray-500">
                                            {{ $testimonial->project_type }} • {{ $testimonial->location }}
                                        </div>
                                    </div>
                                </div>
                                @if ($testimonial->source === TestimonialSource::Google)
                                    <img src="https://www.google.com/favicon.ico" alt="Google" class="size-5 opacity-50">
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="bg-gray-900 py-20">
        <div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
            <h2 class="mb-6 text-3xl font-bold text-white md:text-4xl">
                Ready to Join Our Happy Clients?
            </h2>
            <p class="mb-8 text-xl text-gray-300">
                Experience the Fort Construction difference for yourself.
            </p>
            <x-button-link :href="route('contact')" size="lg">
                Start Your Project
                <x-icon name="arrow-right" class="size-4" />
            </x-button-link>
        </div>
    </section>
</x-layouts.site>
