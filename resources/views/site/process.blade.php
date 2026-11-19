@php
    $processSteps = [
        [
            'number' => '01',
            'icon' => 'message-square',
            'title' => 'Initial Consultation',
            'description' => "We start with a free, no-obligation consultation at your home. We'll discuss your vision, assess the space, and understand your goals, timeline, and budget.",
            'details' => ['Free on-site assessment', 'Discuss your vision and goals', 'Review material options', 'Answer all your questions'],
        ],
        [
            'number' => '02',
            'icon' => 'ruler',
            'title' => 'Design & Planning',
            'description' => "Our team creates detailed designs and plans tailored to your needs. We'll work with you to refine every detail until you're completely satisfied.",
            'details' => ['Custom design concepts', 'Material selection guidance', '3D renderings when applicable', 'Detailed project timeline'],
        ],
        [
            'number' => '03',
            'icon' => 'file-text',
            'title' => 'Proposal & Agreement',
            'description' => 'We provide a comprehensive, transparent proposal with detailed pricing. No hidden fees, no surprises—just honest, upfront pricing.',
            'details' => ['Detailed written estimate', 'Clear scope of work', 'Transparent pricing', 'Flexible payment options'],
        ],
        [
            'number' => '04',
            'icon' => 'hammer',
            'title' => 'Construction',
            'description' => 'Our skilled craftsmen bring your project to life with precision and care. We maintain a clean worksite and communicate progress regularly.',
            'details' => ['Professional crew', 'Quality materials', 'Daily progress updates', 'Clean worksite maintained'],
        ],
        [
            'number' => '05',
            'icon' => 'circle-check',
            'title' => 'Quality Inspection',
            'description' => 'Before completion, we conduct thorough quality checks to ensure every detail meets our high standards and your expectations.',
            'details' => ['Detailed walkthrough', 'Quality assurance checks', 'Address any concerns', 'Ensure complete satisfaction'],
        ],
        [
            'number' => '06',
            'icon' => 'key',
            'title' => 'Project Completion',
            'description' => 'We hand over your completed project and provide all warranty information. Our commitment to your satisfaction extends well beyond project completion.',
            'details' => ['Final walkthrough', 'Warranty documentation', 'Care & maintenance tips', 'Ongoing support'],
        ],
    ];
    $clientExpectations = [
        ['title' => 'Clear Communication', 'description' => 'We keep you informed at every stage with regular updates and open dialogue.'],
        ['title' => 'On-Time Delivery', 'description' => 'We respect your schedule and work efficiently to complete projects on time.'],
        ['title' => 'No Hidden Costs', 'description' => 'Our detailed estimates ensure there are no surprises—what we quote is what you pay.'],
    ];
@endphp

<x-layouts.site title="Process">
    <x-page-hero
        image="/images/unsplash/photo-1504307651254-35680f356dfd.jpg"
        image-alt="Our process"
        eyebrow="How We Work"
        title="Our Process"
    >
        <p class="mx-auto max-w-3xl text-xl text-gray-300">
            A seamless, stress-free experience from first consultation to project completion.
        </p>
    </x-page-hero>

    {{-- Process steps --}}
    <section class="py-20">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <div class="relative">
                {{-- Vertical line --}}
                <div class="absolute bottom-0 left-8 top-0 hidden w-0.5 bg-gray-200 md:block"></div>

                <div class="space-y-16">
                    @foreach ($processSteps as $step)
                        <div class="relative flex gap-8">
                            <div class="relative z-10 hidden size-16 flex-shrink-0 items-center justify-center rounded-full bg-orange-500 md:flex">
                                <x-icon :name="$step['icon']" class="size-8 text-white" />
                            </div>

                            <div class="flex-grow rounded-2xl border border-gray-100 bg-white p-8 shadow-sm transition-shadow hover:shadow-lg">
                                <div class="mb-4 flex items-center gap-4">
                                    <div class="flex size-12 items-center justify-center rounded-full bg-orange-500 md:hidden">
                                        <x-icon :name="$step['icon']" class="size-6 text-white" />
                                    </div>
                                    <div>
                                        <span class="text-sm font-semibold text-orange-500">Step {{ $step['number'] }}</span>
                                        <h3 class="text-2xl font-bold text-gray-900">{{ $step['title'] }}</h3>
                                    </div>
                                </div>
                                <p class="mb-6 text-gray-600">{{ $step['description'] }}</p>
                                <ul class="grid gap-3 sm:grid-cols-2">
                                    @foreach ($step['details'] as $detail)
                                        <li class="flex items-center gap-2 text-gray-700">
                                            <x-icon name="circle-check" class="size-5 flex-shrink-0 text-green-500" />
                                            <span>{{ $detail }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Guarantees --}}
    <section class="bg-gray-50 py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-12 text-center">
                <span class="mb-4 block font-semibold uppercase tracking-widest text-orange-500">Our Commitment</span>
                <h2 class="mb-4 text-3xl font-bold text-gray-900 md:text-4xl">What You Can Expect</h2>
            </div>

            <div class="grid gap-8 md:grid-cols-3">
                @foreach ($clientExpectations as $expectation)
                    <div class="rounded-2xl bg-white p-8 text-center shadow-sm">
                        <h3 class="mb-3 text-xl font-bold text-gray-900">{{ $expectation['title'] }}</h3>
                        <p class="text-gray-600">{{ $expectation['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Call to action --}}
    <section class="bg-orange-500 py-20">
        <div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
            <h2 class="mb-6 text-3xl font-bold text-white md:text-4xl">Ready to Get Started?</h2>
            <p class="mb-8 text-xl text-white/90">
                Schedule your free consultation today and take the first step toward your dream project.
            </p>
            <div class="flex flex-col justify-center gap-4 sm:flex-row">
                <x-button-link :href="route('contact')" variant="white" size="lg">
                    Schedule Consultation
                    <x-icon name="arrow-right" class="ml-2 size-4" />
                </x-button-link>
                <x-button-link href="tel:{{ config('site.phone_dial') }}" variant="outline-white" size="lg">
                    <x-icon name="phone" class="mr-2 size-4" />
                    {{ config('site.phone_short') }}
                </x-button-link>
            </div>
        </div>
    </section>
</x-layouts.site>
