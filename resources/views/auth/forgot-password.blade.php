<x-layouts.auth title="Reset your password">
    <p class="mb-4 text-sm text-stone-600">
        Enter your email address and we will send you a link to choose a new password.
    </p>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <x-form-field name="email" label="Email" type="email" autocomplete="username" required autofocus />

        <x-primary-button>Email reset link</x-primary-button>
    </form>

    <p class="mt-6 text-center text-sm">
        <a href="{{ route('login') }}" class="font-medium text-orange-600 hover:underline">Back to sign in</a>
    </p>
</x-layouts.auth>
