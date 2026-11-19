@php
    $reasonsToChooseUs = [
        ['icon' => 'hammer', 'title' => 'Expert Craftsmanship', 'description' => 'Meticulous attention to detail and premium materials ensure lasting quality in every project.'],
        ['icon' => 'message-square', 'title' => 'Clear Communication', 'description' => 'Regular updates and transparent communication keep you informed every step of the way.'],
        ['icon' => 'clock', 'title' => 'On-Time Delivery', 'description' => 'We respect your time with efficient project management and reliable scheduling.'],
        ['icon' => 'shield', 'title' => 'Insured and Bonded', 'description' => 'Full coverage and proper licensing give you peace of mind throughout your project.'],
        ['icon' => 'award', 'title' => 'Quality Materials', 'description' => 'We source only the finest materials from trusted suppliers for superior results.'],
        ['icon' => 'heart', 'title' => 'Customer First', 'description' => 'Your satisfaction drives everything we do, from design to final walkthrough.'],
    ];
@endphp

<section class="bg-[#1a1a1a] py-20 text-white md:py-28">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-16 lg:grid-cols-2">
            <div>
                <x-section-header
                    subtitle="Why Fort Construction"
                    title="Building Excellence, One Project at a Time"
                    description="We combine old-world craftsmanship with modern techniques to deliver results that exceed expectations."
                    align="left"
                    :is-light="true"
                />

                <div class="mt-8 grid gap-6 sm:grid-cols-2">
                    @foreach ($reasonsToChooseUs as $reason)
                        <div class="flex gap-4">
                            <div class="flex size-12 flex-shrink-0 items-center justify-center rounded-xl bg-orange-500/20">
                                <x-icon :name="$reason['icon']" class="size-6 text-orange-500" />
                            </div>
                            <div>
                                <h3 class="mb-1 font-semibold">{{ $reason['title'] }}</h3>
                                <p class="text-sm text-gray-400">{{ $reason['description'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="relative">
                <div class="relative overflow-hidden rounded-2xl">
                    <img
                        src="/images/unsplash/photo-1600585154340-be6161a56a0c.jpg"
                        alt="Quality craftsmanship"
                        class="h-[500px] w-full object-cover"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                </div>

                {{-- Floating stats card --}}
                <div class="absolute -bottom-8 -left-8 max-w-xs rounded-2xl bg-white p-6 text-gray-900 shadow-xl">
                    <div class="mb-4 flex items-center gap-4">
                        <div class="flex size-12 items-center justify-center rounded-full bg-orange-100">
                            <x-icon name="award" class="size-6 text-orange-500" />
                        </div>
                        <div>
                            <div class="text-2xl font-bold">500+</div>
                            <div class="text-sm text-gray-500">Happy Clients</div>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600">
                        Trusted by homeowners across North Atlanta for quality renovations.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
