<x-layouts.admin title="Service areas">
    <x-slot:actions>
        <x-admin.primary-link :href="route('admin.service-areas.create')">Add service area</x-admin.primary-link>
    </x-slot:actions>

    <div class="overflow-x-auto rounded-xl bg-white shadow-sm ring-1 ring-stone-200">
        <table class="min-w-full divide-y divide-stone-200 text-sm">
            <thead class="bg-stone-50 text-left text-xs font-semibold uppercase tracking-wide text-stone-500">
                <tr>
                    <th scope="col" class="px-4 py-3">City</th>
                    <th scope="col" class="px-4 py-3">County</th>
                    <th scope="col" class="px-4 py-3">Primary</th>
                    <th scope="col" class="px-4 py-3"><span class="sr-only">Actions</span></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($serviceAreas as $serviceArea)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $serviceArea->city }}, {{ $serviceArea->state }}</td>
                        <td class="px-4 py-3 text-stone-600">{{ $serviceArea->county ?? '—' }}</td>
                        <td class="px-4 py-3 text-stone-600">{{ $serviceArea->is_primary ? 'Yes' : 'No' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-4">
                                <a href="{{ route('admin.service-areas.edit', $serviceArea) }}" class="text-sm font-medium text-orange-600 hover:underline">Edit</a>
                                <x-admin.delete-button :action="route('admin.service-areas.destroy', $serviceArea)" :item-name="$serviceArea->city" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-stone-500">No service areas yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $serviceAreas->links() }}</div>
</x-layouts.admin>
