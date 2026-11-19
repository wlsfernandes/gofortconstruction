@php
    $isEditing = $faq->exists;
    $categoryOptions = collect(\App\Enums\FaqCategory::cases())
        ->mapWithKeys(fn (\App\Enums\FaqCategory $category): array => [$category->value => $category->label()])
        ->all();
@endphp

<x-layouts.admin :title="$isEditing ? 'Edit FAQ' : 'Add FAQ'">
    <form
        method="POST"
        action="{{ $isEditing ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}"
        class="max-w-2xl rounded-xl bg-white p-6 shadow-sm ring-1 ring-stone-200"
    >
        @csrf
        @if ($isEditing)
            @method('PUT')
        @endif

        <x-form-field name="question" label="Question" :value="$faq->question" required />
        <x-admin.textarea name="answer" label="Answer" :value="$faq->answer" rows="6" required />
        <x-admin.select name="category" label="Category" :options="$categoryOptions" :value="$faq->category?->value" placeholder="Choose a category" required />
        <x-form-field name="sort_order" label="Sort order" type="number" min="0" :value="$faq->sort_order" placeholder="Leave blank to add at the end" />
        <x-admin.checkbox name="is_featured" label="Featured" :checked="$faq->is_featured" />

        <div class="flex items-center gap-4">
            <button type="submit" class="rounded-lg bg-orange-500 px-4 py-2 text-sm font-semibold text-white hover:bg-orange-600">
                {{ $isEditing ? 'Save changes' : 'Add FAQ' }}
            </button>
            <a href="{{ route('admin.faqs.index') }}" class="text-sm text-stone-600 hover:underline">Cancel</a>
        </div>
    </form>
</x-layouts.admin>
