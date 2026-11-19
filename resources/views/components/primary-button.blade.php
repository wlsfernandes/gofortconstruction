<button {{ $attributes->merge(['type' => 'submit', 'class' => 'w-full rounded-lg bg-orange-500 px-4 py-2.5 text-sm font-semibold text-white hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2']) }}>
    {{ $slot }}
</button>
