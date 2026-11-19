@props(['name', 'label', 'checked' => false])

<div class="mb-4">
    {{-- Hidden field so an unticked box submits false instead of nothing. --}}
    <input type="hidden" name="{{ $name }}" value="0">
    <label class="flex items-center gap-2 text-sm font-medium text-stone-700">
        <input type="checkbox" name="{{ $name }}" value="1" @checked(old($name, $checked)) class="rounded border-stone-300 text-orange-500 focus:ring-orange-500">
        {{ $label }}
    </label>
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
