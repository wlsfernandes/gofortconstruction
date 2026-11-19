@props(['title'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} · {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-stone-100 text-stone-900 antialiased">
    <main class="flex min-h-screen items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            <a href="{{ url('/') }}" class="mb-8 block text-center text-2xl font-bold tracking-tight">
                Fort <span class="text-orange-500">Construction</span>
            </a>
            <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-stone-200">
                <h1 class="mb-6 text-xl font-semibold">{{ $title }}</h1>

                @if (session('status'))
                    <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-800" role="status">
                        {{ session('status') }}
                    </div>
                @endif

                {{ $slot }}
            </div>
        </div>
    </main>
</body>
</html>
