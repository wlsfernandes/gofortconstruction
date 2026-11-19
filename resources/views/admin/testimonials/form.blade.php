@php
    $isEditing = $testimonial->exists;
    $ratingOptions = collect(range(1, 5))->mapWithKeys(fn (int $rating): array => [$rating => $rating.'/5'])->all();
    $sourceOptions = collect(\App\Enums\TestimonialSource::cases())
        ->mapWithKeys(fn (\App\Enums\TestimonialSource $source): array => [$source->value => ucfirst($source->value)])
        ->all();
@endphp

<x-layouts.admin :title="$isEditing ? 'Edit testimonial from '.$testimonial->client_name : 'Add testimonial'">
    <form
        method="POST"
        action="{{ $isEditing ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}"
        enctype="multipart/form-data"
        class="max-w-2xl rounded-xl bg-white p-6 shadow-sm ring-1 ring-stone-200"
    >
        @csrf
        @if ($isEditing)
            @method('PUT')
        @endif

        <x-form-field name="client_name" label="Client name" :value="$testimonial->client_name" required />
        <x-form-field name="location" label="Location" :value="$testimonial->location" />
        <x-admin.textarea name="content" label="Testimonial" :value="$testimonial->content" rows="6" required />
        <x-admin.select name="rating" label="Rating" :options="$ratingOptions" :value="$testimonial->rating" required />
        <x-form-field name="project_type" label="Project type" :value="$testimonial->project_type" />
        <x-admin.select name="source" label="Source" :options="$sourceOptions" :value="$testimonial->source?->value" required />
        <x-admin.image-upload name="image" label="Photo" :current-url="$testimonial->image" />
        @if ($testimonial->image)
            <x-admin.checkbox name="remove_image" label="Remove image" />
        @endif
        <x-admin.checkbox name="is_featured" label="Featured on the homepage" :checked="$testimonial->is_featured" />

        <div class="flex items-center gap-4">
            <button type="submit" class="rounded-lg bg-orange-500 px-4 py-2 text-sm font-semibold text-white hover:bg-orange-600">
                {{ $isEditing ? 'Save changes' : 'Add testimonial' }}
            </button>
            <a href="{{ route('admin.testimonials.index') }}" class="text-sm text-stone-600 hover:underline">Cancel</a>
        </div>
    </form>
</x-layouts.admin>
