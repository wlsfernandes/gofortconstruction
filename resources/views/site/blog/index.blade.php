@php
    use App\Enums\BlogCategory;

    // Filter tabs in the prototype's order; "news" had no tab there, so its posts show under "All Posts" only.
    $categoryFilters = [
        ['value' => 'all', 'label' => 'All Posts'],
        ['value' => BlogCategory::Tips->value, 'label' => BlogCategory::Tips->label()],
        ['value' => BlogCategory::Trends->value, 'label' => BlogCategory::Trends->label()],
        ['value' => BlogCategory::Guides->value, 'label' => BlogCategory::Guides->label()],
        ['value' => BlogCategory::Projects->value, 'label' => BlogCategory::Projects->label()],
    ];

    // Alpine needs each post's id and category, in display order, to pick the featured post of the active filter.
    $postFilterData = $posts
        ->map(fn ($post) => ['id' => $post->id, 'category' => $post->category?->value ?? ''])
        ->values();

    $firstPostId = $posts->first()?->id;

    $formatPostDate = fn ($post): string => ($post->publish_date ?? $post->created_at)?->format('M j, Y') ?? '';
@endphp

<x-layouts.site title="Blog">
    {{-- Hero Section --}}
    <x-page-hero
        image="/images/unsplash/photo-1600585154340-be6161a56a0c.jpg"
        image-alt="Blog"
        eyebrow="Blog"
        title="Insights & Inspiration"
    >
        <p class="mx-auto max-w-3xl text-xl text-gray-300">
            Expert tips, trends, and inspiration for your next home improvement project.
        </p>
    </x-page-hero>

    <div
        x-data="{
            activeCategory: 'all',
            posts: @js($postFilterData),
            matchesFilter(category) {
                return this.activeCategory === 'all' || this.activeCategory === category;
            },
            get featuredPostId() {
                const featuredPost = this.posts.find((post) => this.matchesFilter(post.category));
                return featuredPost ? featuredPost.id : null;
            },
            get hasVisiblePosts() {
                return this.featuredPostId !== null;
            },
        }"
    >
        {{-- Category Filter --}}
        <section class="sticky top-[72px] z-40 border-b bg-white py-6">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex gap-2 overflow-x-auto pb-2 [scrollbar-width:none]">
                    @foreach ($categoryFilters as $categoryFilter)
                        <button
                            type="button"
                            x-on:click="activeCategory = @js($categoryFilter['value'])"
                            x-bind:aria-pressed="activeCategory === @js($categoryFilter['value'])"
                            x-bind:class="activeCategory === @js($categoryFilter['value'])
                                ? 'bg-orange-500 text-white'
                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                            @class([
                                'whitespace-nowrap rounded-full px-5 py-2 text-sm font-medium transition-all',
                                'bg-orange-500 text-white' => $loop->first,
                                'bg-gray-100 text-gray-700 hover:bg-gray-200' => ! $loop->first,
                            ])
                        >
                            {{ $categoryFilter['label'] }}
                        </button>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Blog Posts Grid --}}
        <section class="py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                @if ($posts->isEmpty())
                    <div class="py-16 text-center">
                        <p class="text-lg text-gray-500">No blog posts yet. Check back soon.</p>
                    </div>
                @else
                    {{-- Featured Post: the first post matching the active filter --}}
                    @foreach ($posts as $post)
                        <div
                            class="mb-12"
                            x-show="featuredPostId === @js($post->id)"
                            @if ($post->id !== $firstPostId) x-cloak @endif
                        >
                            <a
                                href="{{ route('blog.show', $post) }}"
                                class="group grid gap-8 overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition-all hover:shadow-xl md:grid-cols-2"
                            >
                                <div class="relative h-64 overflow-hidden bg-gray-100 md:h-full">
                                    @if ($post->featured_image)
                                        <img
                                            src="{{ $post->featured_image }}"
                                            alt="{{ $post->title }}"
                                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                        >
                                    @endif
                                </div>
                                <div class="flex flex-col justify-center p-8">
                                    @if ($post->category)
                                        <span class="mb-3 inline-flex items-center gap-1 text-sm font-semibold text-orange-500">
                                            <x-icon name="tag" class="size-4" />
                                            {{ $post->category->label() }}
                                        </span>
                                    @endif
                                    <h2 class="mb-4 text-2xl font-bold text-gray-900 transition-colors group-hover:text-orange-500 md:text-3xl">
                                        {{ $post->title }}
                                    </h2>
                                    <p class="mb-6 line-clamp-3 text-gray-600">
                                        {{ $post->excerpt }}
                                    </p>
                                    <div class="flex items-center gap-4 text-sm text-gray-500">
                                        <div class="flex items-center gap-2">
                                            <x-icon name="user" class="size-4" />
                                            <span>{{ $post->author }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <x-icon name="calendar" class="size-4" />
                                            <span>{{ $formatPostDate($post) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach

                    {{-- Other Posts --}}
                    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                        @foreach ($posts as $post)
                            <div
                                x-show="matchesFilter(@js($post->category?->value ?? '')) && featuredPostId !== @js($post->id)"
                                @if ($post->id === $firstPostId) x-cloak @endif
                            >
                                <a
                                    href="{{ route('blog.show', $post) }}"
                                    class="group block overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition-all hover:shadow-xl"
                                >
                                    <div class="relative h-48 overflow-hidden bg-gray-100">
                                        @if ($post->featured_image)
                                            <img
                                                src="{{ $post->featured_image }}"
                                                alt="{{ $post->title }}"
                                                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                            >
                                        @endif
                                        @if ($post->category)
                                            <span class="absolute left-4 top-4 rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-orange-500 backdrop-blur-sm">
                                                {{ $post->category->label() }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="p-6">
                                        <h3 class="mb-3 line-clamp-2 text-xl font-bold text-gray-900 transition-colors group-hover:text-orange-500">
                                            {{ $post->title }}
                                        </h3>
                                        <p class="mb-4 line-clamp-2 text-sm text-gray-600">
                                            {{ $post->excerpt }}
                                        </p>
                                        <div class="flex items-center gap-2 text-sm text-gray-500">
                                            <x-icon name="calendar" class="size-4" />
                                            <span>{{ $formatPostDate($post) }}</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>

                    <div class="py-16 text-center" x-show="! hasVisiblePosts" x-cloak>
                        <p class="text-lg text-gray-500">No posts found in this category.</p>
                    </div>
                @endif
            </div>
        </section>
    </div>

    {{-- Newsletter CTA --}}
    <section class="bg-gray-50 py-20">
        <div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
            <h2 class="mb-4 text-3xl font-bold text-gray-900">
                Ready to Start Your Project?
            </h2>
            <p class="mb-8 text-xl text-gray-600">
                Contact us today for a free consultation and estimate.
            </p>
            <x-button-link :href="route('contact')" size="lg">
                Get Free Estimate
                <x-icon name="arrow-right" class="size-4" />
            </x-button-link>
        </div>
    </section>
</x-layouts.site>
