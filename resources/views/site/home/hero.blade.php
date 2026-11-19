@php
    $heroStats = [
        ['value' => '20+', 'label' => 'Years Experience'],
        ['value' => '500+', 'label' => 'Projects Completed'],
        ['value' => '100%', 'label' => 'Satisfaction Rate'],
    ];
@endphp

<section class="relative flex min-h-[90vh] items-center overflow-hidden">
    {{-- Background image --}}
    <div class="absolute inset-0">
        <img
            src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1920&q=80"
            alt="Luxury home exterior"
            class="h-full w-full object-cover"
        >
        <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/50 to-transparent"></div>
    </div>

    {{-- Content --}}
    <div class="relative z-10 mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
        <div class="animate-fade-up max-w-2xl">
            <span class="mb-6 inline-flex items-center gap-2 rounded-full border border-orange-500/30 bg-orange-500/20 px-4 py-2 text-sm font-medium text-orange-400 backdrop-blur-sm">
                <span class="size-2 animate-pulse rounded-full bg-orange-500"></span>
                North Atlanta's Trusted Contractor
            </span>

            <h1 class="mb-6 text-4xl font-bold leading-tight text-white md:text-5xl lg:text-6xl">
                Premium Decks, Renovations &amp;
                <span class="text-orange-500"> Outdoor Living</span>
            </h1>

            <p class="mb-8 text-xl leading-relaxed text-gray-300">
                Transform your home with expert craftsmanship. From stunning custom decks to complete kitchen and bathroom renovations, we bring your vision to life.
            </p>

            <div class="flex flex-col gap-4 sm:flex-row">
                <a
                    href="{{ route('contact') }}"
                    class="group inline-flex h-10 items-center justify-center gap-2 whitespace-nowrap rounded-md bg-orange-500 px-8 py-6 text-lg font-semibold text-white shadow-sm transition-colors hover:bg-orange-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-500 focus-visible:ring-offset-2"
                >
                    Request Free Estimate
                    <x-icon name="arrow-right" class="ml-2 size-4 transition-transform group-hover:translate-x-1" />
                </a>
                <a
                    href="{{ route('projects.index') }}"
                    class="inline-flex h-10 items-center justify-center gap-2 whitespace-nowrap rounded-md border border-white/50 bg-white/10 px-8 py-6 text-lg font-semibold text-white shadow-sm backdrop-blur-sm transition-colors hover:bg-white/20 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-orange-500 focus-visible:ring-offset-2"
                >
                    View Our Work
                </a>
            </div>

            {{-- Trust badges --}}
            <div class="mt-12 border-t border-white/20 pt-8">
                <div class="flex flex-wrap gap-8">
                    @foreach ($heroStats as $stat)
                        <div>
                            <div class="text-3xl font-bold text-orange-500">{{ $stat['value'] }}</div>
                            <div class="text-sm text-gray-400">{{ $stat['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Floating contact --}}
    <div class="absolute right-8 top-1/2 hidden -translate-y-1/2 lg:flex">
        <a
            href="tel:{{ config('site.phone_dial') }}"
            class="flex items-center gap-3 rounded-full border border-white/30 bg-black/50 px-6 py-4 backdrop-blur-md transition-colors hover:bg-black/60"
        >
            <div class="flex size-12 items-center justify-center rounded-full bg-orange-500">
                <x-icon name="phone" class="size-6 text-white" />
            </div>
            <div>
                <div class="text-xs text-gray-200">Call us now</div>
                <div class="font-semibold text-white">{{ config('site.phone_short') }}</div>
            </div>
        </a>
    </div>
</section>
