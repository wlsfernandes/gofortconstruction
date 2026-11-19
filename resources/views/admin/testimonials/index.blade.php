<x-layouts.admin title="Testimonials">
    <x-slot:actions>
        <x-admin.primary-link :href="route('admin.testimonials.create')">Add testimonial</x-admin.primary-link>
    </x-slot:actions>

    <div class="overflow-x-auto rounded-xl bg-white shadow-sm ring-1 ring-stone-200">
        <table class="min-w-full divide-y divide-stone-200 text-sm">
            <thead class="bg-stone-50 text-left text-xs font-semibold uppercase tracking-wide text-stone-500">
                <tr>
                    <th scope="col" class="px-4 py-3">Client</th>
                    <th scope="col" class="px-4 py-3">Rating</th>
                    <th scope="col" class="px-4 py-3">Source</th>
                    <th scope="col" class="px-4 py-3">Featured</th>
                    <th scope="col" class="px-4 py-3"><span class="sr-only">Actions</span></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($testimonials as $testimonial)
                    <tr>
                        <td class="px-4 py-3 font-medium">
                            <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="text-orange-600 hover:underline">{{ $testimonial->client_name }}</a>
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 text-stone-600">{{ $testimonial->rating }}/5</td>
                        <td class="px-4 py-3 text-stone-600">{{ ucfirst($testimonial->source->value) }}</td>
                        <td class="px-4 py-3 text-stone-600">{{ $testimonial->is_featured ? 'Yes' : 'No' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-4">
                                <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="text-sm font-medium text-orange-600 hover:underline">Edit</a>
                                <x-admin.delete-button :action="route('admin.testimonials.destroy', $testimonial)" :item-name="'the testimonial from '.$testimonial->client_name" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-stone-500">No testimonials yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $testimonials->links() }}</div>
</x-layouts.admin>
