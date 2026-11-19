@php
    $isEditing = $serviceArea->exists;
@endphp

<x-layouts.admin :title="$isEditing ? 'Edit '.$serviceArea->city : 'Add service area'">
    <form
        method="POST"
        action="{{ $isEditing ? route('admin.service-areas.update', $serviceArea) : route('admin.service-areas.store') }}"
        class="max-w-2xl rounded-xl bg-white p-6 shadow-sm ring-1 ring-stone-200"
    >
        @csrf
        @if ($isEditing)
            @method('PUT')
        @endif

        <x-form-field name="city" label="City" :value="$serviceArea->city" required />
        <x-form-field name="county" label="County" :value="$serviceArea->county" />
        <x-form-field name="state" label="State" :value="$serviceArea->state" required />
        <x-admin.textarea name="description" label="Description" :value="$serviceArea->description" />
        <x-admin.checkbox name="is_primary" label="Primary service area" :checked="$serviceArea->is_primary" />

        <div class="flex items-center gap-4">
            <button type="submit" class="rounded-lg bg-orange-500 px-4 py-2 text-sm font-semibold text-white hover:bg-orange-600">
                {{ $isEditing ? 'Save changes' : 'Add service area' }}
            </button>
            <a href="{{ route('admin.service-areas.index') }}" class="text-sm text-stone-600 hover:underline">Cancel</a>
        </div>
    </form>
</x-layouts.admin>
