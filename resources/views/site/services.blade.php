@php
    // Each id matches the slug the home page links to, so /services#<id> lands on the section.
    $services = [
        [
            'id' => 'deck',
            'title' => 'Deck Construction',
            'description' => 'Custom-designed decks that extend your living space and enhance your outdoor lifestyle.',
            'longDescription' => 'Transform your backyard into an outdoor oasis with a custom-designed deck from Fort Construction. We specialize in creating beautiful, durable decks using premium materials like composite decking, pressure-treated lumber, and exotic hardwoods. Whether you envision a multi-level entertainment space or a cozy retreat, we bring your vision to life with expert craftsmanship.',
            'image' => 'https://images.unsplash.com/photo-1591825729269-caeb344f6df2?w=1200&q=80',
            'features' => [
                'Custom design consultation',
                'Premium material selection',
                'Built-in seating and planters',
                'Lighting and electrical options',
                'Stairs and railings',
            ],
            'process' => [
                'Initial consultation and site assessment',
                'Custom design and material selection',
                'Foundation and framing',
                'Decking installation',
                'Railings, stairs, and finishing touches',
            ],
        ],
        [
            'id' => 'kitchen',
            'title' => 'Kitchen Remodeling',
            'description' => 'Create the heart of your home with stunning kitchen renovations that combine style and function.',
            'longDescription' => 'The kitchen is where families gather, memories are made, and culinary dreams come true. Our kitchen remodeling services transform outdated spaces into stunning, functional kitchens that you\'ll love for years to come. From cabinet installation to countertop selection, we handle every detail with precision and care.',
            'image' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=1200&q=80',
            'features' => [
                'Custom cabinetry',
                'Countertop installation',
                'Backsplash design',
                'Appliance integration',
                'Lighting design',
                'Flooring installation',
            ],
            'process' => [
                'Design consultation and planning',
                'Material and finish selection',
                'Demolition and preparation',
                'Electrical and plumbing updates',
                'Cabinet and countertop installation',
                'Final finishes and walkthrough',
            ],
        ],
        [
            'id' => 'bathroom',
            'title' => 'Bathroom Remodeling',
            'description' => 'Transform your bathroom into a personal spa retreat with elegant fixtures and finishes.',
            'longDescription' => 'Create your own private sanctuary with a bathroom renovation from Fort Construction. We specialize in transforming ordinary bathrooms into luxurious retreats featuring modern fixtures, elegant tile work, and spa-like amenities. Whether you\'re updating a powder room or creating a master suite, we deliver exceptional results.',
            'image' => 'https://images.unsplash.com/photo-1552321554-5fefe8c9ef14?w=1200&q=80',
            'features' => [
                'Shower and tub installation',
                'Custom tile work',
                'Vanity and cabinet installation',
                'Lighting and ventilation',
                'Heated flooring',
                'Accessibility features',
            ],
            'process' => [
                'Design consultation',
                'Material selection',
                'Demolition',
                'Plumbing and electrical work',
                'Tile and fixture installation',
                'Final touches and inspection',
            ],
        ],
        [
            'id' => 'outdoor-living',
            'title' => 'Outdoor Living',
            'description' => 'Extend your living space with patios, pergolas, outdoor kitchens, and more.',
            'longDescription' => 'Make the most of Georgia\'s beautiful weather with custom outdoor living spaces. From covered patios and pergolas to fully equipped outdoor kitchens and fire features, we create outdoor environments perfect for relaxation and entertainment. Let us help you design the outdoor space of your dreams.',
            'image' => 'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=1200&q=80',
            'features' => [
                'Covered patios and pergolas',
                'Outdoor kitchens',
                'Fire pits and fireplaces',
                'Screen porches',
                'Landscape integration',
                'Outdoor lighting',
            ],
            'process' => [
                'Site assessment and design',
                'Material and feature selection',
                'Foundation work',
                'Structure construction',
                'Utility installation',
                'Finishing and landscaping',
            ],
        ],
        [
            'id' => 'renovation',
            'title' => 'Home Renovations',
            'description' => 'Complete home transformations that increase value and improve daily living.',
            'longDescription' => 'Whether you\'re updating a single room or renovating your entire home, Fort Construction delivers exceptional results. Our comprehensive renovation services include everything from structural modifications to finish work. We work closely with you to understand your vision and bring it to life while staying on budget and schedule.',
            'image' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=1200&q=80',
            'features' => [
                'Whole-home renovations',
                'Room additions',
                'Basement finishing',
                'Interior remodeling',
                'Structural modifications',
                'Energy efficiency upgrades',
            ],
            'process' => [
                'Initial consultation',
                'Design and planning',
                'Demolition and prep',
                'Construction phase',
                'Final inspection and completion',
            ],
        ],
        [
            'id' => 'custom',
            'title' => 'Custom Projects',
            'description' => 'Unique solutions tailored to your specific needs and vision.',
            'longDescription' => 'Have a unique project in mind? We love tackling custom projects that challenge our creativity and showcase our craftsmanship. From built-in furniture and specialty trim work to unique architectural features, our team has the skills and experience to bring your custom vision to life.',
            'image' => 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?w=1200&q=80',
            'features' => [
                'Custom built-ins',
                'Specialty millwork',
                'Unique architectural features',
                'Custom storage solutions',
                'Specialty finishes',
                'One-of-a-kind projects',
            ],
            'process' => [
                'Concept discussion',
                'Design development',
                'Material sourcing',
                'Fabrication',
                'Installation',
                'Final detailing',
            ],
        ],
    ];
    // The process card on each image shows only the first few steps.
    $processPreviewStepCount = 3;
@endphp

<x-layouts.site title="Services">
    <x-page-hero
        image="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1920&q=80"
        image-alt="Services"
        eyebrow="Our Services"
        title="Premium Construction Services"
    >
        <p class="mx-auto max-w-3xl text-xl text-gray-300">
            From custom decks to complete home renovations, we deliver exceptional craftsmanship for every project.
        </p>
    </x-page-hero>

    {{-- Services List --}}
    @foreach ($services as $service)
        @php($isReversed = $loop->even)
        <section
            id="{{ $service['id'] }}"
            @class(['scroll-mt-24 py-20 md:py-28', 'bg-white' => ! $isReversed, 'bg-gray-50' => $isReversed])
        >
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid items-center gap-16 lg:grid-cols-2">
                    <div @class(['lg:order-2' => $isReversed])>
                        <span class="mb-4 block font-semibold uppercase tracking-widest text-orange-500">
                            {{ $service['title'] }}
                        </span>
                        <h2 class="mb-6 text-3xl font-bold text-gray-900 md:text-4xl">
                            {{ $service['description'] }}
                        </h2>
                        <p class="mb-8 leading-relaxed text-gray-600">
                            {{ $service['longDescription'] }}
                        </p>

                        <div class="mb-8 grid gap-3 sm:grid-cols-2">
                            @foreach ($service['features'] as $feature)
                                <div class="flex items-center gap-2">
                                    <x-icon name="circle-check" class="size-5 flex-shrink-0 text-green-500" />
                                    <span class="text-gray-700">{{ $feature }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex flex-col gap-4 sm:flex-row">
                            <x-button-link :href="route('contact')" variant="primary">
                                Get Free Estimate
                                <x-icon name="arrow-right" class="size-4" />
                            </x-button-link>
                            <x-button-link href="tel:{{ config('site.phone_dial') }}" variant="outline">
                                <x-icon name="phone" class="size-4" />
                                {{ config('site.phone_short') }}
                            </x-button-link>
                        </div>
                    </div>

                    <div @class(['lg:order-1' => $isReversed])>
                        <div class="relative">
                            <img
                                src="{{ $service['image'] }}"
                                alt="{{ $service['title'] }}"
                                class="w-full rounded-2xl shadow-2xl"
                            >

                            {{-- Process Card --}}
                            <div class="absolute -bottom-8 -right-8 hidden max-w-xs rounded-xl bg-white p-6 shadow-xl md:block">
                                <div class="mb-3 font-bold text-gray-900">Our Process</div>
                                <ol class="space-y-2">
                                    @foreach (array_slice($service['process'], 0, $processPreviewStepCount) as $step)
                                        <li class="flex items-start gap-2 text-sm">
                                            <span class="flex size-5 flex-shrink-0 items-center justify-center rounded-full bg-orange-100 text-xs font-bold text-orange-600">
                                                {{ $loop->iteration }}
                                            </span>
                                            <span class="text-gray-600">{{ $step }}</span>
                                        </li>
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endforeach

    {{-- CTA Section --}}
    <section class="bg-gray-900 py-20">
        <div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
            <h2 class="mb-6 text-3xl font-bold text-white md:text-4xl">
                Ready to Start Your Project?
            </h2>
            <p class="mb-8 text-xl text-gray-300">
                Contact us today for a free consultation and estimate.
            </p>
            <div class="flex flex-col justify-center gap-4 sm:flex-row">
                <x-button-link :href="route('contact')" variant="primary" size="lg">
                    Request Free Estimate
                </x-button-link>
                <x-button-link :href="route('projects.index')" variant="outline-white" size="lg">
                    View Our Work
                </x-button-link>
            </div>
        </div>
    </section>
</x-layouts.site>
