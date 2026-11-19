<x-layouts.auth title="Sign in">
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <x-form-field name="email" label="Email" type="email" autocomplete="username" required autofocus />
        <x-form-field name="password" label="Password" type="password" autocomplete="current-password" required />

        <div class="mb-6 flex items-center justify-between text-sm">
            <label class="flex items-center gap-2 text-stone-700">
                <input type="checkbox" name="remember" class="rounded border-stone-300 text-orange-500 focus:ring-orange-500">
                Remember me
            </label>
            <a href="{{ route('password.request') }}" class="font-medium text-orange-600 hover:underline">Forgot password?</a>
        </div>

        <x-primary-button>Sign in</x-primary-button>
    </form>
</x-layouts.auth>
