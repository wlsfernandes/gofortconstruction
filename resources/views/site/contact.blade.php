@php
    $socialLinks = [
        ['icon' => 'facebook', 'label' => 'Facebook', 'url' => config('site.social_links.facebook')],
        ['icon' => 'instagram', 'label' => 'Instagram', 'url' => config('site.social_links.instagram')],
        ['icon' => 'linkedin', 'label' => 'LinkedIn', 'url' => config('site.social_links.linkedin')],
    ];
    $trustStats = [
        ['value' => '20+', 'label' => 'Years Experience'],
        ['value' => '500+', 'label' => 'Projects Completed'],
        ['value' => '100%', 'label' => 'Satisfaction Rate'],
        ['value' => '5.0', 'label' => 'Google Rating'],
    ];
@endphp

<x-layouts.site title="Contact">
    <x-page-hero
        image="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1920&q=80"
        image-alt="Contact"
        eyebrow="Get In Touch"
        title="Contact Us"
    >
        <p class="mx-auto max-w-3xl text-xl text-gray-300">
            Ready to start your project? Get in touch for a free consultation and estimate.
        </p>
    </x-page-hero>

    {{-- Contact Section --}}
    <section class="py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-16 lg:grid-cols-2">
                {{-- Contact Info --}}
                <div>
                    <h2 class="mb-6 text-3xl font-bold text-gray-900">
                        Let's Discuss Your Project
                    </h2>
                    <p class="mb-8 text-gray-600">
                        Whether you're dreaming of a new deck, planning a kitchen renovation, or ready to transform your outdoor space, we're here to help bring your vision to life.
                    </p>

                    <div class="mb-10 space-y-6">
                        <a
                            href="tel:{{ config('site.phone_dial') }}"
                            class="flex items-start gap-4 rounded-xl bg-gray-50 p-4 transition-colors hover:bg-gray-100"
                        >
                            <div class="flex size-12 flex-shrink-0 items-center justify-center rounded-xl bg-orange-100">
                                <x-icon name="phone" class="size-6 text-orange-500" />
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">Phone</div>
                                <div class="text-gray-600">{{ config('site.phone_display') }}</div>
                                <div class="mt-1 text-sm text-gray-500">Call or text anytime</div>
                            </div>
                        </a>

                        <a
                            href="mailto:{{ config('site.email') }}"
                            class="flex items-start gap-4 rounded-xl bg-gray-50 p-4 transition-colors hover:bg-gray-100"
                        >
                            <div class="flex size-12 flex-shrink-0 items-center justify-center rounded-xl bg-orange-100">
                                <x-icon name="mail" class="size-6 text-orange-500" />
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">Email</div>
                                <div class="text-gray-600">{{ config('site.email') }}</div>
                                <div class="mt-1 text-sm text-gray-500">We'll respond within 24 hours</div>
                            </div>
                        </a>

                        <div class="flex items-start gap-4 rounded-xl bg-gray-50 p-4">
                            <div class="flex size-12 flex-shrink-0 items-center justify-center rounded-xl bg-orange-100">
                                <x-icon name="map-pin" class="size-6 text-orange-500" />
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">Service Area</div>
                                <div class="text-gray-600">North Atlanta &amp; Surrounding Areas</div>
                                <div class="mt-1 text-sm text-gray-500">Cumming, Alpharetta, Johns Creek, Milton &amp; more</div>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 rounded-xl bg-gray-50 p-4">
                            <div class="flex size-12 flex-shrink-0 items-center justify-center rounded-xl bg-orange-100">
                                <x-icon name="clock" class="size-6 text-orange-500" />
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">Business Hours</div>
                                <div class="text-gray-600">Mon - Fri: 8am - 6pm</div>
                                <div class="mt-1 text-sm text-gray-500">Sat: By appointment</div>
                            </div>
                        </div>
                    </div>

                    {{-- Social Links --}}
                    <div>
                        <div class="mb-4 font-semibold text-gray-900">Follow Us</div>
                        <div class="flex gap-4">
                            @foreach ($socialLinks as $socialLink)
                                <a
                                    href="{{ $socialLink['url'] }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    aria-label="{{ $socialLink['label'] }}"
                                    class="flex size-12 items-center justify-center rounded-xl bg-gray-100 transition-colors hover:bg-orange-100 hover:text-orange-500"
                                >
                                    <x-icon :name="$socialLink['icon']" class="size-5" />
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Contact Form --}}
                <div>
                    <div id="request-estimate" class="scroll-mt-24 rounded-2xl border border-gray-100 bg-white p-8 shadow-xl">
                        <h3 class="mb-2 text-2xl font-bold text-gray-900">
                            Request Free Estimate
                        </h3>
                        <p class="mb-6 text-gray-600">
                            Fill out the form below and we'll get back to you within 24 hours.
                        </p>
                        <x-contact-form />
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Trust Badges --}}
    <section class="bg-gray-50 py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-10 text-center">
                <h3 class="mb-2 text-xl font-bold text-gray-900">Why Choose Fort Construction?</h3>
            </div>
            <div class="grid grid-cols-2 gap-8 text-center md:grid-cols-4">
                @foreach ($trustStats as $trustStat)
                    <div>
                        <div class="text-3xl font-bold text-orange-500">{{ $trustStat['value'] }}</div>
                        <div class="text-gray-600">{{ $trustStat['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</x-layouts.site>
