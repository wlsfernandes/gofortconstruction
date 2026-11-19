@props(['name', 'label', 'currentUrl' => null, 'isMultiple' => false])

<div class="mb-4">
    <label for="{{ $name }}" class="mb-1 block text-sm font-medium text-stone-700">{{ $label }}</label>
    @if ($currentUrl)
        <img src="{{ $currentUrl }}" alt="" class="mb-2 h-24 w-36 rounded-md object-cover ring-1 ring-stone-200">
    @endif
    <input
        id="{{ $name }}"
        name="{{ $isMultiple ? $name.'[]' : $name }}"
        type="file"
        accept="image/jpeg,image/png,image/webp"
        @if ($isMultiple) multiple @endif
        class="block w-full text-sm text-stone-600 file:mr-3 file:rounded-md file:border-0 file:bg-stone-200 file:px-3 file:py-2 file:text-sm file:font-medium hover:file:bg-stone-300"
    >
    <p class="mt-1 text-xs text-stone-500">JPEG, PNG or WebP, up to 5 MB{{ $isMultiple ? ' each' : '' }}.</p>
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
    @error($name.'.*')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
