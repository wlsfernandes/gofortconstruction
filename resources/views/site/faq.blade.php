@php
    use App\Enums\FaqCategory;

    // Filter tabs in the prototype's order.
    $categoryFilters = [
        ['value' => 'all', 'label' => 'All Questions'],
        ['value' => FaqCategory::General->value, 'label' => FaqCategory::General->label()],
        ['value' => FaqCategory::Pricing->value, 'label' => FaqCategory::Pricing->label()],
        ['value' => FaqCategory::Process->value, 'label' => FaqCategory::Process->label()],
        ['value' => FaqCategory::Warranty->value, 'label' => FaqCategory::Warranty->label()],
        ['value' => FaqCategory::Services->value, 'label' => FaqCategory::Services->label()],
    ];

    $faqCategoryValues = $faqs->map(fn ($faq) => $faq->category?->value ?? '')->values();
@endphp

<x-layouts.site title="FAQ">
    {{-- Hero Section --}}
    <x-page-hero
        image="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1920&q=80"
        image-alt="FAQ"
        eyebrow="FAQ"
        title="Frequently Asked Questions"
    >
        <p class="mx-auto max-w-3xl text-xl text-gray-300">
            Find answers to common questions about our services, process, and pricing.
        </p>
    </x-page-hero>

    <div
        x-data="{
            activeCategory: 'all',
            openFaqId: null,
            faqCategories: @js($faqCategoryValues),
            matchesFilter(category) {
                return this.activeCategory === 'all' || this.activeCategory === category;
            },
            get hasVisibleFaqs() {
                return this.faqCategories.some((category) => this.matchesFilter(category));
            },
            toggleFaq(faqId) {
                this.openFaqId = this.openFaqId === faqId ? null : faqId;
            },
        }"
    >
        {{-- Category Filter --}}
        <section class="sticky top-[72px] z-40 border-b bg-white py-6">
            <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
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

        {{-- FAQ List --}}
        <section class="py-16">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
                @if ($faqs->isEmpty())
                    <p class="text-center text-lg text-gray-500">No questions yet. Contact us and we'll be happy to help.</p>
                @else
                    <div class="space-y-4">
                        @foreach ($faqs as $faq)
                            <div
                                x-show="matchesFilter(@js($faq->category?->value ?? ''))"
                                class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm transition-shadow hover:shadow-md"
                            >
                                <button
                                    type="button"
                                    id="faq-question-{{ $faq->id }}"
                                    x-on:click="toggleFaq(@js($faq->id))"
                                    x-bind:aria-expanded="openFaqId === @js($faq->id)"
                                    aria-expanded="false"
                                    aria-controls="faq-answer-{{ $faq->id }}"
                                    class="w-full text-left"
                                >
                                    <div class="flex items-start justify-between gap-4">
                                        <h3 class="pr-4 text-lg font-semibold text-gray-900">
                                            {{ $faq->question }}
                                        </h3>
                                        <x-icon
                                            name="chevron-down"
                                            class="size-5 flex-shrink-0 text-gray-500 transition-transform"
                                            x-bind:class="openFaqId === {{ $faq->id }} && 'rotate-180'"
                                        />
                                    </div>
                                </button>
                                <div
                                    id="faq-answer-{{ $faq->id }}"
                                    role="region"
                                    aria-labelledby="faq-question-{{ $faq->id }}"
                                    x-show="openFaqId === @js($faq->id)"
                                    x-cloak
                                    class="overflow-hidden"
                                >
                                    <p class="mt-4 border-t border-gray-100 pt-4 text-gray-600">
                                        {{ $faq->answer }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <p class="text-center text-lg text-gray-500" x-show="! hasVisibleFaqs" x-cloak>
                        No questions found in this category.
                    </p>
                @endif
            </div>
        </section>
    </div>

    {{-- Contact CTA --}}
    <section class="bg-gray-50 py-20">
        <div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
            <h2 class="mb-4 text-3xl font-bold text-gray-900">
                Still Have Questions?
            </h2>
            <p class="mb-8 text-xl text-gray-600">
                We're here to help. Contact us anytime and we'll get back to you promptly.
            </p>
            <div class="flex flex-col justify-center gap-4 sm:flex-row">
                <x-button-link :href="route('contact')" size="lg">
                    Contact Us
                    <x-icon name="arrow-right" class="size-4" />
                </x-button-link>
                <x-button-link :href="'tel:'.config('site.phone_dial')" variant="outline" size="lg">
                    <x-icon name="phone" class="size-4" />
                    {{ config('site.phone_short') }}
                </x-button-link>
            </div>
        </div>
    </section>
</x-layouts.site>
