@php
    $maximumStarRating = 5;
@endphp

<section class="bg-white py-20 md:py-28">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-section-header
            subtitle="Testimonials"
            title="What Our Clients Say"
            description="Don't just take our word for it. Hear from homeowners who trusted us with their projects."
        />

        @if ($featuredTestimonials->isNotEmpty())
            <div class="grid gap-8 md:grid-cols-3">
                @foreach ($featuredTestimonials->take(3) as $testimonial)
                    <div class="relative rounded-2xl bg-gray-50 p-8">
                        <x-icon name="quote" class="absolute right-6 top-6 size-10 text-orange-100" />

                        <div class="mb-4 flex gap-1">
                            <span class="sr-only">Rated {{ $testimonial->rating }} out of {{ $maximumStarRating }}</span>
                            @for ($starIndex = 1; $starIndex <= $maximumStarRating; $starIndex++)
                                @if ($starIndex <= $testimonial->rating)
                                    <x-icon name="star" class="size-5 fill-orange-500 text-orange-500" />
                                @else
                                    <x-icon name="star" class="size-5 text-gray-300" />
                                @endif
                            @endfor
                        </div>

                        <p class="mb-6 leading-relaxed text-gray-600">
                            "{{ $testimonial->content }}"
                        </p>

                        <div class="flex items-center gap-4">
                            <div class="flex size-12 items-center justify-center rounded-full bg-orange-100">
                                <span class="text-lg font-bold text-orange-600">{{ mb_substr($testimonial->client_name, 0, 1) }}</span>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">{{ $testimonial->client_name }}</div>
                                <div class="text-sm text-gray-500">
                                    {{ $testimonial->project_type }} &bull; {{ $testimonial->location }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Google reviews badge --}}
        <div class="mt-12 text-center">
            <div class="inline-flex items-center gap-4 rounded-full border border-gray-200 bg-white px-6 py-3 shadow-sm">
                <div class="flex gap-0.5">
                    @for ($starIndex = 1; $starIndex <= $maximumStarRating; $starIndex++)
                        <x-icon name="star" class="size-5 fill-yellow-400 text-yellow-400" />
                    @endfor
                </div>
                <span class="font-semibold text-gray-900">5.0</span>
                <span class="text-gray-500">on Google Reviews</span>
            </div>
        </div>
    </div>
</section>
