@php
    $trustIndicators = [
        ['icon' => 'clock', 'value' => '20+', 'label' => 'Years of Experience', 'description' => 'Serving North Atlanta'],
        ['icon' => 'circle-check', 'value' => '500+', 'label' => 'Projects Completed', 'description' => 'On time & on budget'],
        ['icon' => 'star', 'value' => '5.0', 'label' => 'Star Rating', 'description' => 'On Google Reviews'],
        ['icon' => 'shield', 'value' => '100%', 'label' => 'Insured and Bonded', 'description' => 'Full coverage'],
    ];
@endphp

<section class="border-y border-gray-100 bg-gray-50 py-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 gap-8 lg:grid-cols-4">
            @foreach ($trustIndicators as $indicator)
                <div class="text-center">
                    <div class="mb-4 inline-flex size-14 items-center justify-center rounded-2xl bg-orange-100">
                        <x-icon :name="$indicator['icon']" class="size-7 text-orange-500" />
                    </div>
                    <div class="mb-1 text-3xl font-bold text-gray-900 md:text-4xl">{{ $indicator['value'] }}</div>
                    <div class="mb-1 font-semibold text-gray-900">{{ $indicator['label'] }}</div>
                    <div class="text-sm text-gray-500">{{ $indicator['description'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>
