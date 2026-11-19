@php
    $postDate = ($post->publish_date ?? $post->created_at)?->format('F j, Y');
@endphp

<x-layouts.site :title="$post->title" :description="$post->excerpt">
    {{-- Hero Image --}}
    <section class="relative h-[50vh] min-h-[400px] bg-gray-900">
        @if ($post->featured_image)
            <img
                src="{{ $post->featured_image }}"
                alt="{{ $post->title }}"
                class="h-full w-full object-cover"
            >
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>

        <div class="absolute bottom-0 left-0 right-0 p-8 md:p-16">
            <div class="mx-auto max-w-4xl">
                <a
                    href="{{ route('blog.index') }}"
                    class="mb-4 inline-flex items-center text-white/80 transition-colors hover:text-white"
                >
                    <x-icon name="arrow-left" class="mr-2 size-4" />
                    Back to Blog
                </a>
                @if ($post->category)
                    <span class="mb-4 flex items-center gap-1 text-sm font-semibold text-orange-400">
                        <x-icon name="tag" class="size-4" />
                        {{ $post->category->label() }}
                    </span>
                @endif
                <h1 class="mb-6 text-3xl font-bold text-white md:text-5xl">
                    {{ $post->title }}
                </h1>
                <div class="flex flex-wrap items-center gap-6 text-white/80">
                    <div class="flex items-center gap-2">
                        <x-icon name="user" class="size-5" />
                        <span>{{ $post->author }}</span>
                    </div>
                    @if ($postDate)
                        <div class="flex items-center gap-2">
                            <x-icon name="calendar" class="size-5" />
                            <span>{{ $postDate }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Content --}}
    <section class="py-16">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <article class="max-w-none text-lg text-gray-600 [&_a]:text-orange-500 [&_a]:underline [&_a:hover]:text-orange-600 [&_blockquote]:mb-4 [&_blockquote]:border-l-4 [&_blockquote]:border-orange-200 [&_blockquote]:pl-4 [&_blockquote]:italic [&_code]:rounded [&_code]:bg-gray-100 [&_code]:px-1 [&_code]:text-base [&_h2]:mb-4 [&_h2]:mt-10 [&_h2]:text-2xl [&_h2]:font-bold [&_h2]:text-gray-900 [&_h3]:mb-3 [&_h3]:mt-8 [&_h3]:text-xl [&_h3]:font-bold [&_h3]:text-gray-900 [&_img]:my-6 [&_img]:rounded-2xl [&_li]:leading-relaxed [&_ol]:mb-4 [&_ol]:list-decimal [&_ol]:space-y-2 [&_ol]:pl-6 [&_p]:mb-4 [&_p]:leading-relaxed [&_strong]:font-semibold [&_strong]:text-gray-900 [&_ul]:mb-4 [&_ul]:list-disc [&_ul]:space-y-2 [&_ul]:pl-6">
                {!! Str::markdown($post->content, ['html_input' => 'escape', 'allow_unsafe_links' => false]) !!}
            </article>

            {{-- CTA Box --}}
            <div class="mt-12 rounded-2xl bg-gray-900 p-8 text-center text-white">
                <h3 class="mb-4 text-2xl font-bold">Ready to Start Your Project?</h3>
                <p class="mb-6 text-gray-300">
                    Contact us today for a free consultation and estimate.
                </p>
                <x-button-link :href="route('contact')" class="px-8">
                    Get Free Estimate
                    <x-icon name="arrow-right" class="size-4" />
                </x-button-link>
            </div>
        </div>
    </section>
</x-layouts.site>
