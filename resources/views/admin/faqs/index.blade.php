<x-layouts.admin title="FAQs">
    <x-slot:actions>
        <x-admin.primary-link :href="route('admin.faqs.create')">Add FAQ</x-admin.primary-link>
    </x-slot:actions>

    <div class="overflow-x-auto rounded-xl bg-white shadow-sm ring-1 ring-stone-200">
        <table class="min-w-full divide-y divide-stone-200 text-sm">
            <thead class="bg-stone-50 text-left text-xs font-semibold uppercase tracking-wide text-stone-500">
                <tr>
                    <th scope="col" class="px-4 py-3">Order</th>
                    <th scope="col" class="px-4 py-3">Question</th>
                    <th scope="col" class="px-4 py-3">Category</th>
                    <th scope="col" class="px-4 py-3">Featured</th>
                    <th scope="col" class="px-4 py-3"><span class="sr-only">Actions</span></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($faqs as $faq)
                    <tr>
                        <td class="px-4 py-3 text-stone-600">{{ $faq->sort_order }}</td>
                        <td class="px-4 py-3 font-medium">
                            <a href="{{ route('admin.faqs.edit', $faq) }}" class="text-orange-600 hover:underline">{{ Str::limit($faq->question, 80) }}</a>
                        </td>
                        <td class="px-4 py-3 text-stone-600">{{ $faq->category->label() }}</td>
                        <td class="px-4 py-3 text-stone-600">{{ $faq->is_featured ? 'Yes' : 'No' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-4">
                                <a href="{{ route('admin.faqs.edit', $faq) }}" class="text-sm font-medium text-orange-600 hover:underline">Edit</a>
                                <x-admin.delete-button :action="route('admin.faqs.destroy', $faq)" :item-name="Str::limit($faq->question, 80)" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-stone-500">No FAQs yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $faqs->links() }}</div>
</x-layouts.admin>
