<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin · {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-stone-100 text-stone-900 antialiased">
    <header class="bg-stone-900 text-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4">
            <span class="font-bold">Fort <span class="text-orange-400">Construction</span> admin</span>
            <div class="flex items-center gap-4 text-sm">
                <span>{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded-lg bg-white/10 px-3 py-1.5 hover:bg-white/20">Sign out</button>
                </form>
            </div>
        </div>
    </header>
    <main class="mx-auto max-w-7xl px-4 py-10">
        <h1 class="text-2xl font-semibold">Dashboard</h1>
        <p class="mt-2 text-stone-600">You are signed in.</p>
    </main>
</body>
</html>
