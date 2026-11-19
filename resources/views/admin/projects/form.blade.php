@php
    $isEditing = $project->exists;
    $imageLists = [
        'before_images' => 'Before images',
        'after_images' => 'After images',
        'gallery_images' => 'Gallery images',
    ];
@endphp

<x-layouts.admin :title="$isEditing ? 'Edit '.$project->title : 'Add project'">
    <form
        method="POST"
        action="{{ $isEditing ? route('admin.projects.update', $project) : route('admin.projects.store') }}"
        enctype="multipart/form-data"
        class="max-w-2xl rounded-xl bg-white p-6 shadow-sm ring-1 ring-stone-200"
    >
        @csrf
        @if ($isEditing)
            @method('PUT')
        @endif

        <x-form-field name="title" label="Title" :value="$project->title" required />
        <x-form-field name="slug" label="Slug (leave blank to generate from the title)" :value="$project->slug" />
        <x-form-field name="location" label="Location" :value="$project->location" />
        <x-admin.select name="category" label="Category" :options="$categoryOptions" :value="$project->category?->value" placeholder="Choose a category" required />
        <x-admin.textarea name="short_description" label="Short description" :value="$project->short_description" :rows="2" hint="Up to 500 characters." />
        <x-admin.textarea name="description" label="Description" :value="$project->description" :rows="8" />
        <x-form-field name="completion_date" label="Completion date" type="date" :value="$project->completion_date?->format('Y-m-d')" />
        <x-form-field name="client_name" label="Client name" :value="$project->client_name" />
        <x-admin.textarea name="testimonial" label="Testimonial" :value="$project->testimonial" />
        <x-form-field name="tags" label="Tags (comma separated)" :value="implode(', ', $project->tags ?? [])" />
        <x-admin.checkbox name="is_featured" label="Featured project" :checked="$project->is_featured" />

        <x-admin.image-upload name="featured_image" label="Featured image" :current-url="$project->featured_image" />

        @foreach ($imageLists as $field => $label)
            @if (! empty($project->{$field}))
                <fieldset class="mb-2">
                    <legend class="mb-1 text-sm font-medium text-stone-700">Current {{ strtolower($label) }}</legend>
                    <div class="flex flex-wrap gap-3">
                        @foreach ($project->{$field} as $imageUrl)
                            <label class="flex flex-col items-start gap-1 text-xs text-stone-600">
                                <img src="{{ $imageUrl }}" alt="" class="h-24 w-36 rounded-md object-cover ring-1 ring-stone-200">
                                <span class="flex items-center gap-1">
                                    <input type="checkbox" name="remove_{{ $field }}[]" value="{{ $imageUrl }}" @checked(in_array($imageUrl, old('remove_'.$field, []), true)) class="rounded border-stone-300 text-orange-500 focus:ring-orange-500">
                                    Remove
                                </span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>
            @endif
            <x-admin.image-upload :name="$field" :label="'Add '.strtolower($label)" is-multiple />
        @endforeach

        <div class="flex items-center gap-4">
            <button type="submit" class="rounded-lg bg-orange-500 px-4 py-2 text-sm font-semibold text-white hover:bg-orange-600">
                {{ $isEditing ? 'Save changes' : 'Add project' }}
            </button>
            <a href="{{ route('admin.projects.index') }}" class="text-sm text-stone-600 hover:underline">Cancel</a>
        </div>
    </form>
</x-layouts.admin>
