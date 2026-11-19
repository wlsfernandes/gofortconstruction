@props(['title' => null, 'description' => 'Premium deck construction, kitchen & bathroom remodeling, and home renovations in North Atlanta.'])

@php
    $navigation = [
        ['label' => 'Home', 'route' => 'home'],
        ['label' => 'About', 'route' => 'about'],
        ['label' => 'Services', 'route' => 'services'],
        ['label' => 'Projects', 'route' => 'projects.index', 'active' => 'projects.*'],
        ['label' => 'Process', 'route' => 'process'],
        ['label' => 'Reviews', 'route' => 'reviews'],
        ['label' => 'FAQ', 'route' => 'faq'],
        ['label' => 'Service Areas', 'route' => 'service-areas'],
        ['label' => 'Blog', 'route' => 'blog.index', 'active' => 'blog.*'],
        ['label' => 'Contact', 'route' => 'contact'],
    ];
    // The desktop bar has room for the first seven links; the mobile menu shows all.
    $desktopNavigationCount = 7;
    $footerServices = ['Deck Construction', 'Kitchen Remodeling', 'Bathroom Remodeling', 'Outdoor Living', 'Home Renovations', 'Custom Projects'];
    $footerLinks = [
        ['label' => 'About Us', 'route' => 'about'],
        ['label' => 'Projects', 'route' => 'projects.index'],
        ['label' => 'Process', 'route' => 'process'],
        ['label' => 'Reviews', 'route' => 'reviews'],
        ['label' => 'FAQ', 'route' => 'faq'],
        ['label' => 'Contact', 'route' => 'contact'],
    ];
    $isActive = fn (array $item): bool => request()->routeIs($item['active'] ?? $item['route']);
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ? $title.' · ' : '' }}{{ config('app.name') }}</title>
    <meta name="description" content="{{ $description }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-gray-900 antialiased">
<div class="flex min-h-screen flex-col">
    {{-- Top bar --}}
    <div class="hidden bg-[#1a1a1a] px-4 py-2 text-sm text-white md:block">
        <div class="mx-auto flex max-w-7xl items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="tel:{{ config('site.phone_dial') }}" class="flex items-center gap-2 transition-colors hover:text-orange-400">
                    <x-icon name="phone" class="size-4" />
                    <span>{{ config('site.phone_display') }}</span>
                </a>
                <a href="mailto:{{ config('site.email') }}" class="flex items-center gap-2 transition-colors hover:text-orange-400">
                    <x-icon name="mail" class="size-4" />
                    <span>{{ config('site.email') }}</span>
                </a>
            </div>
            <div class="flex items-center gap-2">
                <x-icon name="map-pin" class="size-4 text-orange-500" />
                <span>Serving North Atlanta &amp; Surrounding Areas</span>
            </div>
        </div>
    </div>

    {{-- Main navigation --}}
    <header
        x-data="{ isScrolled: false, isMenuOpen: false }"
        x-on:scroll.window="isScrolled = window.scrollY > 20"
        x-bind:class="isScrolled ? 'bg-white/95 backdrop-blur-md shadow-lg py-2' : 'bg-white py-4'"
        class="sticky top-0 z-50 bg-white py-4 transition-all duration-300"
    >
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex-shrink-0">
                    <div class="flex h-12 items-center">
                        <img src="{{ config('site.logo_url') }}" alt="Fort Construction Logo" class="h-full object-contain">
                    </div>
                </a>

                <nav class="hidden items-center gap-1 lg:flex" aria-label="Main">
                    @foreach (array_slice($navigation, 0, $desktopNavigationCount) as $item)
                        <a
                            href="{{ route($item['route']) }}"
                            @class([
                                'rounded-md px-3 py-2 text-sm font-medium transition-colors',
                                'bg-orange-50 text-orange-600' => $isActive($item),
                                'text-gray-700 hover:bg-orange-50/50 hover:text-orange-600' => ! $isActive($item),
                            ])
                            @if ($isActive($item)) aria-current="page" @endif
                        >{{ $item['label'] }}</a>
                    @endforeach
                </nav>

                <div class="hidden items-center gap-4 lg:flex">
                    <x-button-link :href="route('contact')" class="px-6">Get Free Estimate</x-button-link>
                </div>

                <button
                    type="button"
                    x-on:click="isMenuOpen = ! isMenuOpen"
                    x-bind:aria-expanded="isMenuOpen.toString()"
                    aria-controls="mobile-menu"
                    class="rounded-md p-2 text-gray-700 hover:bg-gray-100 lg:hidden"
                >
                    <span class="sr-only">Toggle menu</span>
                    <x-icon name="menu" class="size-6" x-show="! isMenuOpen" />
                    <x-icon name="x" class="size-6" x-show="isMenuOpen" x-cloak />
                </button>
            </div>
        </div>

        <div id="mobile-menu" x-show="isMenuOpen" x-transition x-cloak class="overflow-hidden border-t bg-white lg:hidden">
            <div class="space-y-1 px-4 py-4">
                @foreach ($navigation as $item)
                    <a
                        href="{{ route($item['route']) }}"
                        @class([
                            'block rounded-lg px-4 py-3 text-base font-medium transition-colors',
                            'bg-orange-50 text-orange-600' => $isActive($item),
                            'text-gray-700 hover:bg-gray-50' => ! $isActive($item),
                        ])
                    >{{ $item['label'] }}</a>
                @endforeach
                <div class="pt-4">
                    <x-button-link :href="route('contact')" class="w-full py-6">Get Free Estimate</x-button-link>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-grow">
        {{ $slot }}
    </main>

    <footer class="bg-[#1a1a1a] text-white">
        {{-- Call to action --}}
        <div class="bg-orange-500 py-12">
            <div class="mx-auto max-w-7xl px-4 text-center sm:px-6 lg:px-8">
                <h3 class="mb-4 text-2xl font-bold text-white md:text-3xl">Ready to Transform Your Home?</h3>
                <p class="mx-auto mb-6 max-w-2xl text-white/90">Get a free consultation and estimate for your next project</p>
                <div class="flex flex-col justify-center gap-4 sm:flex-row">
                    <x-button-link :href="route('contact')" variant="white" size="lg">Request Estimate</x-button-link>
                    <x-button-link href="tel:{{ config('site.phone_dial') }}" variant="outline-white" size="lg">
                        <x-icon name="phone" class="size-4" />
                        Call Now
                    </x-button-link>
                </div>
            </div>
        </div>

        <div class="py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid gap-12 md:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <div class="mb-6 flex h-10 items-center">
                            <img src="{{ config('site.logo_url') }}" alt="Fort Construction Logo" class="h-full object-contain">
                        </div>
                        <p class="mb-6 text-sm leading-relaxed text-gray-400">
                            Premium deck construction, kitchen &amp; bathroom remodeling, and home renovations in North Atlanta.
                        </p>
                        <div class="flex gap-4">
                            @foreach (config('site.social_links') as $network => $url)
                                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="flex size-10 items-center justify-center rounded-full bg-white/10 transition-colors hover:bg-orange-500">
                                    <span class="sr-only">{{ ucfirst($network) }}</span>
                                    <x-icon :name="$network" class="size-5" />
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <h4 class="mb-6 text-lg font-semibold">Services</h4>
                        <ul class="space-y-3">
                            @foreach ($footerServices as $service)
                                <li>
                                    <a href="{{ route('services') }}" class="flex items-center gap-2 text-sm text-gray-400 transition-colors hover:text-orange-400">
                                        <x-icon name="chevron-right" class="size-4" />
                                        {{ $service }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div>
                        <h4 class="mb-6 text-lg font-semibold">Quick Links</h4>
                        <ul class="space-y-3">
                            @foreach ($footerLinks as $link)
                                <li>
                                    <a href="{{ route($link['route']) }}" class="flex items-center gap-2 text-sm text-gray-400 transition-colors hover:text-orange-400">
                                        <x-icon name="chevron-right" class="size-4" />
                                        {{ $link['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div>
                        <h4 class="mb-6 text-lg font-semibold">Contact</h4>
                        <ul class="space-y-4">
                            <li>
                                <a href="tel:{{ config('site.phone_dial') }}" class="flex items-center gap-3 text-gray-400 transition-colors hover:text-orange-400">
                                    <div class="flex size-10 items-center justify-center rounded-full bg-orange-500/20">
                                        <x-icon name="phone" class="size-5 text-orange-500" />
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500">Call Us</div>
                                        <div class="text-white">{{ config('site.phone_display') }}</div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="mailto:{{ config('site.email') }}" class="flex items-center gap-3 text-gray-400 transition-colors hover:text-orange-400">
                                    <div class="flex size-10 items-center justify-center rounded-full bg-orange-500/20">
                                        <x-icon name="mail" class="size-5 text-orange-500" />
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-xs text-gray-500">Email Us</div>
                                        <div class="text-sm break-all text-white">{{ config('site.email') }}</div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center gap-3">
                                    <div class="flex size-10 items-center justify-center rounded-full bg-orange-500/20">
                                        <x-icon name="map-pin" class="size-5 text-orange-500" />
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-500">Service Area</div>
                                        <div class="text-sm text-white">{{ config('site.service_area') }}</div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t border-white/10 py-6">
            <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-4 px-4 sm:px-6 md:flex-row lg:px-8">
                <p class="text-sm text-gray-500">&copy; {{ now()->year }} Fort Construction. All rights reserved.</p>
                {{-- The prototype linked Privacy Policy and Terms of Service to "#"; those pages do not exist yet. --}}
            </div>
        </div>
    </footer>
</div>
</body>
</html>
