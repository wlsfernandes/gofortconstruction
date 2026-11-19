@props(['title'])

@php
    $adminNavigation = [
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'active' => 'admin.dashboard'],
        ['label' => 'Estimate requests', 'route' => 'admin.contact-submissions.index', 'active' => 'admin.contact-submissions.*'],
        ['label' => 'Projects', 'route' => 'admin.projects.index', 'active' => 'admin.projects.*'],
        ['label' => 'Blog posts', 'route' => 'admin.blog-posts.index', 'active' => 'admin.blog-posts.*'],
        ['label' => 'Testimonials', 'route' => 'admin.testimonials.index', 'active' => 'admin.testimonials.*'],
        ['label' => 'FAQs', 'route' => 'admin.faqs.index', 'active' => 'admin.faqs.*'],
        ['label' => 'Service areas', 'route' => 'admin.service-areas.index', 'active' => 'admin.service-areas.*'],
        ['label' => 'Settings', 'route' => 'admin.settings.edit', 'active' => 'admin.settings.*'],
    ];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>{{ $title }} · Admin · {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-stone-100 text-stone-900 antialiased">
    <header class="bg-stone-900 text-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4">
            <a href="{{ route('admin.dashboard') }}" class="font-bold">Fort <span class="text-orange-400">Construction</span> admin</a>
            <div class="flex items-center gap-4 text-sm">
                <a href="{{ route('home') }}" class="hidden hover:text-orange-300 sm:inline" target="_blank" rel="noopener">View site</a>
                <span class="hidden sm:inline">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded-lg bg-white/10 px-3 py-1.5 hover:bg-white/20">Sign out</button>
                </form>
            </div>
        </div>
        <nav class="mx-auto flex max-w-7xl gap-1 overflow-x-auto px-4 pb-2 text-sm" aria-label="Admin">
            @foreach ($adminNavigation as $item)
                <a
                    href="{{ route($item['route']) }}"
                    @class([
                        'whitespace-nowrap rounded-md px-3 py-1.5',
                        'bg-orange-500 text-white' => request()->routeIs($item['active']),
                        'text-stone-300 hover:bg-white/10 hover:text-white' => ! request()->routeIs($item['active']),
                    ])
                    @if (request()->routeIs($item['active'])) aria-current="page" @endif
                >{{ $item['label'] }}</a>
            @endforeach
        </nav>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8">
        @if (session('status'))
            <div class="mb-6 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-800" role="status">{{ session('status') }}</div>
        @endif

        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <h1 class="text-2xl font-semibold">{{ $title }}</h1>
            {{ $actions ?? '' }}
        </div>

        {{ $slot }}
    </main>
</body>
</html>
