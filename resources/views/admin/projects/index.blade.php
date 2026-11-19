<x-layouts.admin title="Projects">
    <x-slot:actions>
        <x-admin.primary-link :href="route('admin.projects.create')">Add project</x-admin.primary-link>
    </x-slot:actions>

    <div class="overflow-x-auto rounded-xl bg-white shadow-sm ring-1 ring-stone-200">
        <table class="min-w-full divide-y divide-stone-200 text-sm">
            <thead class="bg-stone-50 text-left text-xs font-semibold uppercase tracking-wide text-stone-500">
                <tr>
                    <th scope="col" class="px-4 py-3">Title</th>
                    <th scope="col" class="px-4 py-3">Category</th>
                    <th scope="col" class="px-4 py-3">Location</th>
                    <th scope="col" class="px-4 py-3">Featured</th>
                    <th scope="col" class="px-4 py-3"><span class="sr-only">Actions</span></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($projects as $project)
                    <tr>
                        <td class="px-4 py-3 font-medium">
                            <a href="{{ route('admin.projects.edit', $project) }}" class="hover:underline">{{ $project->title }}</a>
                        </td>
                        <td class="px-4 py-3 text-stone-600">{{ $project->category->label() }}</td>
                        <td class="px-4 py-3 text-stone-600">{{ $project->location ?? '—' }}</td>
                        <td class="px-4 py-3 text-stone-600">{{ $project->is_featured ? 'Yes' : 'No' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-4">
                                <a href="{{ route('projects.show', $project) }}" target="_blank" rel="noopener" class="text-sm font-medium text-stone-600 hover:underline">View</a>
                                <a href="{{ route('admin.projects.edit', $project) }}" class="text-sm font-medium text-orange-600 hover:underline">Edit</a>
                                <x-admin.delete-button :action="route('admin.projects.destroy', $project)" :item-name="$project->title" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-stone-500">No projects yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $projects->links() }}</div>
</x-layouts.admin>
