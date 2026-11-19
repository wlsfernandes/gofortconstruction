@props(['image', 'imageAlt', 'eyebrow', 'title'])

<section class="relative bg-gray-900 py-24 md:py-32">
    <div class="absolute inset-0">
        <img src="{{ $image }}" alt="{{ $imageAlt }}" class="h-full w-full object-cover opacity-30">
    </div>
    <div class="animate-fade-up relative mx-auto max-w-7xl px-4 text-center sm:px-6 lg:px-8">
        <span class="mb-4 inline-block font-semibold uppercase tracking-widest text-orange-500">{{ $eyebrow }}</span>
        <h1 class="mb-6 text-4xl font-bold text-white md:text-5xl lg:text-6xl">{{ $title }}</h1>
        {{ $slot }}
    </div>
</section>
