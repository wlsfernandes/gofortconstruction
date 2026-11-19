@php
    $homeServices = [
        ['title' => 'Deck Construction', 'description' => 'Custom-designed decks using premium materials for lasting beauty and durability.', 'image' => '/images/unsplash/photo-1591825729269-caeb344f6df2.jpg', 'slug' => 'deck'],
        ['title' => 'Kitchen Remodeling', 'description' => 'Transform your kitchen into a stunning, functional space that elevates your home.', 'image' => '/images/unsplash/photo-1556909114-f6e7ad7d3136.jpg', 'slug' => 'kitchen'],
        ['title' => 'Bathroom Remodeling', 'description' => 'Create your personal spa retreat with modern fixtures and elegant finishes.', 'image' => '/images/unsplash/photo-1552321554-5fefe8c9ef14.jpg', 'slug' => 'bathroom'],
        ['title' => 'Outdoor Living', 'description' => 'Expand your living space with patios, pergolas, and outdoor entertainment areas.', 'image' => '/images/unsplash/photo-1600566753190-17f0baa2a6c3.jpg', 'slug' => 'outdoor-living'],
        ['title' => 'Home Renovations', 'description' => 'Complete home transformations that increase value and improve daily living.', 'image' => '/images/unsplash/photo-1600607687939-ce8a6c25118c.jpg', 'slug' => 'renovation'],
        ['title' => 'Custom Projects', 'description' => 'Unique solutions tailored to your specific needs and vision.', 'image' => '/images/unsplash/photo-1600585154526-990dced4db0d.jpg', 'slug' => 'custom'],
    ];
@endphp

<section class="bg-white py-20 md:py-28">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <x-section-header
            subtitle="Our Services"
            title="Expert Craftsmanship for Every Project"
            description="From custom decks to complete home renovations, we deliver exceptional quality and attention to detail."
        />

        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($homeServices as $service)
                <a
                    href="{{ route('services') }}#{{ $service['slug'] }}"
                    class="group block overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition-all duration-300 hover:shadow-xl"
                >
                    <div class="relative h-56 overflow-hidden">
                        <img
                            src="{{ $service['image'] }}"
                            alt="{{ $service['title'] }}"
                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-4 left-4 right-4">
                            <h3 class="text-xl font-bold text-white">{{ $service['title'] }}</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="mb-4 text-gray-600">{{ $service['description'] }}</p>
                        <span class="inline-flex items-center font-semibold text-orange-500 transition-colors group-hover:text-orange-600">
                            Learn More
                            <x-icon name="arrow-right" class="ml-2 size-4 transition-transform group-hover:translate-x-1" />
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
