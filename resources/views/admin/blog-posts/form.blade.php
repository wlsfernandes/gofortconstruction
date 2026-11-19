@php
    $isEditing = $blogPost->exists;
    $categoryOptions = collect(\App\Enums\BlogCategory::cases())
        ->mapWithKeys(fn (\App\Enums\BlogCategory $category): array => [$category->value => $category->label()])
        ->all();
@endphp

<x-layouts.admin :title="$isEditing ? 'Edit '.$blogPost->title : 'Add blog post'">
    <form
        method="POST"
        action="{{ $isEditing ? route('admin.blog-posts.update', $blogPost) : route('admin.blog-posts.store') }}"
        enctype="multipart/form-data"
        class="max-w-3xl rounded-xl bg-white p-6 shadow-sm ring-1 ring-stone-200"
    >
        @csrf
        @if ($isEditing)
            @method('PUT')
        @endif

        <x-form-field name="title" label="Title" :value="$blogPost->title" required />
        <x-form-field name="slug" label="Slug (leave blank to generate from the title)" :value="$blogPost->slug" />
        <x-admin.textarea name="excerpt" label="Excerpt" :value="$blogPost->excerpt" />
        <x-admin.textarea name="content" label="Content" :value="$blogPost->content" rows="16" hint="Markdown supported. HTML is shown as plain text." required />
        <x-admin.select name="category" label="Category" :options="$categoryOptions" :value="$blogPost->category?->value" placeholder="No category" />
        <x-form-field name="tags" label="Tags (comma-separated)" :value="implode(', ', $blogPost->tags ?? [])" />
        <x-form-field name="author" label="Author (leave blank for Fort Construction Team)" :value="$blogPost->author" />
        <x-admin.checkbox name="is_published" label="Published" :checked="$blogPost->is_published" />
        <x-form-field name="publish_date" label="Publish date (defaults to today when published)" type="date" :value="$blogPost->publish_date?->toDateString()" />
        <x-admin.image-upload name="featured_image" label="Featured image" :current-url="$blogPost->featured_image" />
        @if ($blogPost->featured_image)
            <x-admin.checkbox name="remove_image" label="Remove image" />
        @endif

        <div class="flex items-center gap-4">
            <button type="submit" class="rounded-lg bg-orange-500 px-4 py-2 text-sm font-semibold text-white hover:bg-orange-600">
                {{ $isEditing ? 'Save changes' : 'Add blog post' }}
            </button>
            <a href="{{ route('admin.blog-posts.index') }}" class="text-sm text-stone-600 hover:underline">Cancel</a>
        </div>
    </form>
</x-layouts.admin>
