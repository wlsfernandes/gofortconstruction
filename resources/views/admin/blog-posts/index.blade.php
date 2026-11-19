<x-layouts.admin title="Blog posts">
    <x-slot:actions>
        <x-admin.primary-link :href="route('admin.blog-posts.create')">Add blog post</x-admin.primary-link>
    </x-slot:actions>

    <div class="overflow-x-auto rounded-xl bg-white shadow-sm ring-1 ring-stone-200">
        <table class="min-w-full divide-y divide-stone-200 text-sm">
            <thead class="bg-stone-50 text-left text-xs font-semibold uppercase tracking-wide text-stone-500">
                <tr>
                    <th scope="col" class="px-4 py-3">Title</th>
                    <th scope="col" class="px-4 py-3">Category</th>
                    <th scope="col" class="px-4 py-3">Published</th>
                    <th scope="col" class="px-4 py-3">Publish date</th>
                    <th scope="col" class="px-4 py-3"><span class="sr-only">Actions</span></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-100">
                @forelse ($blogPosts as $blogPost)
                    <tr>
                        <td class="px-4 py-3 font-medium">
                            <a href="{{ route('admin.blog-posts.edit', $blogPost) }}" class="text-orange-600 hover:underline">{{ $blogPost->title }}</a>
                        </td>
                        <td class="px-4 py-3 text-stone-600">{{ $blogPost->category?->label() ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @if ($blogPost->is_published)
                                <span class="text-stone-600">Yes</span>
                            @else
                                <span class="rounded-full bg-stone-100 px-2 py-0.5 text-xs font-semibold text-stone-700">Draft</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 text-stone-600">{{ $blogPost->publish_date?->format('M j, Y') ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-4">
                                @if ($blogPost->is_published)
                                    <a href="{{ route('blog.show', $blogPost) }}" class="text-sm font-medium text-stone-600 hover:underline">View</a>
                                @endif
                                <a href="{{ route('admin.blog-posts.edit', $blogPost) }}" class="text-sm font-medium text-orange-600 hover:underline">Edit</a>
                                <x-admin.delete-button :action="route('admin.blog-posts.destroy', $blogPost)" :item-name="$blogPost->title" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-stone-500">No blog posts yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $blogPosts->links() }}</div>
</x-layouts.admin>
