@php
    $homeProcessSteps = [
        ['number' => '01', 'icon' => 'message-square', 'title' => 'Consultation', 'description' => 'We start with a free consultation to understand your vision, needs, and budget.'],
        ['number' => '02', 'icon' => 'ruler', 'title' => 'Design & Planning', 'description' => 'Our team creates detailed plans and provides a comprehensive project estimate.'],
        ['number' => '03', 'icon' => 'hammer', 'title' => 'Build & Deliver', 'description' => 'We bring your project to life with expert craftsmanship and quality materials.'],
    ];
@endphp

<section class="bg-gray-50 py-20 md:py-28">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-section-header
            subtitle="Our Process"
            title="Simple Steps to Your Dream Project"
            description="We make the renovation process easy and stress-free from start to finish."
        />

        <div class="relative grid gap-8 md:grid-cols-3">
            @foreach ($homeProcessSteps as $step)
                <div class="relative text-center">
                    <div class="relative z-10 mb-6 inline-flex size-20 items-center justify-center rounded-full border-4 border-orange-100 bg-white shadow-lg">
                        <x-icon :name="$step['icon']" class="size-8 text-orange-500" />
                    </div>
                    <div class="absolute left-1/2 top-6 -translate-x-1/2 -translate-y-full text-5xl font-bold text-orange-100">
                        {{ $step['number'] }}
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-gray-900">{{ $step['title'] }}</h3>
                    <p class="text-gray-600">{{ $step['description'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-12 text-center">
            <x-button-link :href="route('process')" size="lg">
                Learn More About Our Process
                <x-icon name="arrow-right" class="ml-2 size-4" />
            </x-button-link>
        </div>
    </div>
</section>
