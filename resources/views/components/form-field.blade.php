@props(['name', 'label', 'type' => 'text', 'value' => null])

<div class="mb-4">
    <label for="{{ $name }}" class="mb-1 block text-sm font-medium text-stone-700">{{ $label }}</label>
    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        @if ($type !== 'password') value="{{ old($name, $value) }}" @endif
        {{ $attributes->class([
            'block w-full rounded-lg border px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500',
            'border-red-500' => $errors->has($name),
            'border-stone-300' => ! $errors->has($name),
        ]) }}
    >
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
