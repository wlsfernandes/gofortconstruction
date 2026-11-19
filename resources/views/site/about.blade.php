@php
    $companyValues = [
        ['icon' => 'award', 'title' => 'Excellence', 'description' => 'We never compromise on quality. Every project reflects our commitment to excellence.'],
        ['icon' => 'users', 'title' => 'Integrity', 'description' => 'Honesty and transparency guide every interaction with our clients.'],
        ['icon' => 'clock', 'title' => 'Reliability', 'description' => 'We show up on time, every time, and deliver what we promise.'],
        ['icon' => 'shield', 'title' => 'Trust', 'description' => 'Building lasting relationships through dependable service and results.'],
    ];
    $certifications = [
        'Georgia Registered General Contractor',
        'Fully Insured & Bonded',
        'EPA Lead-Safe Certified',
        'OSHA Safety Certified',
    ];
    $guarantees = [
        ['title' => 'Quality Workmanship', 'description' => 'We stand behind every project we complete'],
        ['title' => 'On-Time Completion', 'description' => 'We respect your time and schedule'],
        ['title' => 'Transparent Pricing', 'description' => 'No hidden fees or surprise costs'],
    ];
@endphp

<x-layouts.site title="About">
    <x-page-hero
        image="/images/unsplash/photo-1504307651254-35680f356dfd.jpg"
        image-alt="Construction team"
        eyebrow="About Us"
        title="Building Dreams Since 2001"
    >
        <p class="mb-3 text-lg font-semibold tracking-wide text-orange-400 md:text-xl">
            Over 20 Years of Experience
        </p>
        <p class="mx-auto max-w-3xl text-xl text-gray-300">
            Fort Construction has been transforming homes across North Atlanta with premium craftsmanship and unwavering dedication to quality.
        </p>
    </x-page-hero>

    {{-- Story --}}
    <section class="py-20 md:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid items-center gap-16 lg:grid-cols-2">
                <div>
                    <span class="mb-4 block font-semibold uppercase tracking-widest text-orange-500">Our Story</span>
                    <h2 class="mb-6 text-3xl font-bold text-gray-900 md:text-4xl">A Passion for Craftsmanship</h2>
                    <div class="space-y-4 leading-relaxed text-gray-600">
                        <p>
                            Fort Construction was founded with a simple vision: to bring exceptional craftsmanship and honest service to every home improvement project in North Atlanta.
                        </p>
                        <p>
                            Over the years, we've had the privilege of transforming hundreds of homes&mdash;from stunning custom decks that extend living spaces to complete kitchen and bathroom renovations that elevate daily life.
                        </p>
                        <p>
                            What sets us apart is our unwavering commitment to quality. We treat every home as if it were our own, using premium materials and time-tested techniques to ensure lasting results.
                        </p>
                        <p>
                            Our team of skilled craftsmen takes pride in their work, and it shows in every detail&mdash;from perfectly aligned tiles to flawlessly finished trim.
                        </p>
                    </div>
                </div>

                <div class="relative">
                    <img
                        src="/images/unsplash/photo-1600585154340-be6161a56a0c.jpg"
                        alt="Fort Construction work"
                        class="rounded-2xl shadow-2xl"
                    >
                    <div class="absolute -bottom-8 -left-8 rounded-2xl bg-orange-500 p-8 text-white shadow-xl">
                        <div class="text-4xl font-bold">20+</div>
                        <div class="text-orange-100">Years of Excellence</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Values --}}
    <section class="bg-gray-50 py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-section-header
                subtitle="Our Values"
                title="What We Stand For"
                description="These core values guide everything we do and every project we undertake."
            />

            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-4">
                @foreach ($companyValues as $value)
                    <div class="rounded-2xl bg-white p-8 text-center shadow-sm transition-shadow hover:shadow-lg">
                        <div class="mb-4 inline-flex size-14 items-center justify-center rounded-xl bg-orange-100">
                            <x-icon :name="$value['icon']" class="size-7 text-orange-500" />
                        </div>
                        <h3 class="mb-2 text-xl font-bold text-gray-900">{{ $value['title'] }}</h3>
                        <p class="text-gray-600">{{ $value['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Certifications --}}
    <section class="py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid items-center gap-16 lg:grid-cols-2">
                <div>
                    <span class="mb-4 block font-semibold uppercase tracking-widest text-orange-500">Credentials</span>
                    <h2 class="mb-6 text-3xl font-bold text-gray-900 md:text-4xl">Registered and Insured</h2>
                    <p class="mb-8 text-gray-600">
                        Your peace of mind matters to us. We maintain all necessary licenses, insurance, and certifications to ensure your project is protected.
                    </p>

                    <div class="space-y-4">
                        @foreach ($certifications as $certification)
                            <div class="flex items-center gap-3">
                                <x-icon name="circle-check" class="size-6 text-green-500" />
                                <span class="font-medium text-gray-700">{{ $certification }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-2xl bg-gray-900 p-10 text-center text-white">
                    <h3 class="mb-6 text-2xl font-bold">Our Guarantees</h3>
                    <div class="space-y-4">
                        @foreach ($guarantees as $guarantee)
                            <div class="rounded-xl bg-white/10 p-4">
                                <div class="mb-1 font-semibold">{{ $guarantee['title'] }}</div>
                                <div class="text-sm text-gray-400">{{ $guarantee['description'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Call to action --}}
    <section class="bg-orange-500 py-20">
        <div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
            <h2 class="mb-6 text-3xl font-bold text-white md:text-4xl">Ready to Work With Us?</h2>
            <p class="mb-8 text-xl text-white/90">Let's discuss your project and bring your vision to life.</p>
            <x-button-link :href="route('contact')" variant="white" size="lg">
                Get Started Today
                <x-icon name="arrow-right" class="ml-2 size-4" />
            </x-button-link>
        </div>
    </section>
</x-layouts.site>
