@php
    $services = [
        ['title' => 'Deck Construction', 'slug' => 'deck'],
        ['title' => 'Kitchen Remodeling', 'slug' => 'kitchen'],
        ['title' => 'Bathroom Remodeling', 'slug' => 'bathroom'],
        ['title' => 'Outdoor Living', 'slug' => 'outdoor-living'],
        ['title' => 'Home Renovations', 'slug' => 'renovation'],
        ['title' => 'Custom Projects', 'slug' => 'custom'],
    ];
    // Primary area cards list only the first few services.
    $primaryAreaServiceCount = 4;
    $localAdvantages = [
        'Familiar with local building codes',
        'Relationships with local suppliers',
        'Understanding of regional climate needs',
        'Quick response times',
        'Knowledge of HOA requirements',
    ];
    [$primaryAreas, $otherAreas] = $serviceAreas->partition(fn ($area) => $area->is_primary);
@endphp

<x-layouts.site title="Service Areas">
    <x-page-hero
        image="/images/unsplash/photo-1449824913935-59a10b8d2000.jpg"
        image-alt="North Atlanta"
        eyebrow="Where We Work"
        title="Service Areas"
    >
        <p class="mx-auto max-w-3xl text-xl text-gray-300">
            Proudly serving North Atlanta and surrounding communities with premium construction services.
        </p>
    </x-page-hero>

    @if ($serviceAreas->isEmpty())
        <section class="py-20">
            <div class="mx-auto max-w-7xl px-4 text-center sm:px-6 lg:px-8">
                <p class="text-lg text-gray-500">No service areas listed yet. Contact us to ask about your area.</p>
            </div>
        </section>
    @endif

    {{-- Primary Service Areas --}}
    @if ($primaryAreas->isNotEmpty())
        <section class="py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-12 text-center">
                    <span class="mb-4 block font-semibold uppercase tracking-widest text-orange-500">
                        Primary Service Areas
                    </span>
                    <h2 class="text-3xl font-bold text-gray-900 md:text-4xl">
                        Communities We Serve
                    </h2>
                </div>

                <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($primaryAreas as $area)
                        <div class="rounded-2xl border border-gray-100 bg-white p-8 shadow-sm transition-shadow hover:shadow-lg">
                            <div class="mb-4 flex items-center gap-3">
                                <div class="flex size-12 items-center justify-center rounded-xl bg-orange-100">
                                    <x-icon name="map-pin" class="size-6 text-orange-500" />
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">{{ $area->city }}</h3>
                                    @if ($area->county)
                                        <p class="text-gray-500">{{ $area->county }} County</p>
                                    @endif
                                </div>
                            </div>
                            <div class="space-y-2">
                                @foreach (array_slice($services, 0, $primaryAreaServiceCount) as $service)
                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                        <x-icon name="circle-check" class="size-4 text-green-500" />
                                        <span>{{ $service['title'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <a
                                href="{{ route('contact') }}"
                                class="mt-4 inline-flex items-center text-sm font-semibold text-orange-500 transition-colors hover:text-orange-600"
                            >
                                Get Estimate in {{ $area->city }}
                                <x-icon name="arrow-right" class="ml-2 size-4" />
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Additional Areas --}}
    @if ($otherAreas->isNotEmpty())
        <section class="bg-gray-50 py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-12 text-center">
                    <h2 class="mb-4 text-3xl font-bold text-gray-900">
                        Additional Service Areas
                    </h2>
                    <p class="text-gray-600">
                        We also serve these surrounding communities throughout the metro Atlanta area.
                    </p>
                </div>

                <div class="flex flex-wrap justify-center gap-4">
                    @foreach ($otherAreas as $area)
                        <div class="flex items-center gap-2 rounded-full border border-gray-100 bg-white px-6 py-3 shadow-sm">
                            <x-icon name="map-pin" class="size-4 text-orange-500" />
                            <span class="font-medium text-gray-700">{{ $area->city }}</span>
                            @if ($area->county)
                                <span class="text-sm text-gray-400">({{ $area->county }})</span>
                            @endif
                        </div>
                    @endforeach
                </div>

                <p class="mt-8 text-center text-gray-500">
                    Don't see your area? Contact us—we may still be able to serve you!
                </p>
            </div>
        </section>
    @endif

    {{-- Services by Area --}}
    <section class="py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid items-center gap-16 lg:grid-cols-2">
                <div>
                    <span class="mb-4 block font-semibold uppercase tracking-widest text-orange-500">
                        Local Expertise
                    </span>
                    <h2 class="mb-6 text-3xl font-bold text-gray-900 md:text-4xl">
                        Why Choose a Local Contractor?
                    </h2>
                    <div class="space-y-4 text-gray-600">
                        <p>
                            As a North Atlanta-based contractor, we understand the unique characteristics of homes in our communities—from Cumming's newer developments to Roswell's established neighborhoods.
                        </p>
                        <p>
                            Our local knowledge means we're familiar with HOA requirements, local building codes, and the best materials for Georgia's climate. We've built relationships with local suppliers and inspectors, ensuring your project runs smoothly.
                        </p>
                    </div>

                    <div class="mt-8 space-y-3">
                        @foreach ($localAdvantages as $advantage)
                            <div class="flex items-center gap-3">
                                <x-icon name="circle-check" class="size-5 text-green-500" />
                                <span class="text-gray-700">{{ $advantage }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-2xl bg-gray-900 p-10 text-white">
                    <h3 class="mb-6 text-2xl font-bold">Our Services</h3>
                    <div class="space-y-4">
                        @foreach ($services as $service)
                            <a
                                href="{{ route('services') }}#{{ $service['slug'] }}"
                                class="flex items-center justify-between rounded-xl bg-white/10 p-4 transition-colors hover:bg-white/20"
                            >
                                <span>{{ $service['title'] }}</span>
                                <x-icon name="arrow-right" class="size-4" />
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="bg-orange-500 py-20">
        <div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
            <h2 class="mb-6 text-3xl font-bold text-white md:text-4xl">
                Ready to Start Your Project?
            </h2>
            <p class="mb-8 text-xl text-white/90">
                Contact us today for a free estimate in your area.
            </p>
            <div class="flex flex-col justify-center gap-4 sm:flex-row">
                <x-button-link :href="route('contact')" variant="white" size="lg">
                    Request Free Estimate
                    <x-icon name="arrow-right" class="size-4" />
                </x-button-link>
                <x-button-link href="tel:{{ config('site.phone_dial') }}" variant="outline-white" size="lg">
                    <x-icon name="phone" class="size-4" />
                    {{ config('site.phone_short') }}
                </x-button-link>
            </div>
        </div>
    </section>
</x-layouts.site>
