<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveBlogPostRequest;
use App\Models\BlogPost;
use App\Services\PublicImageStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BlogPostController extends Controller
{
    private const PER_PAGE = 25;

    private const IMAGE_DIRECTORY = 'blog';

    public function __construct(private readonly PublicImageStorage $imageStorage) {}

    public function index(): View
    {
        return view('admin.blog-posts.index', [
            'blogPosts' => BlogPost::query()
                ->orderByRaw('publish_date is null')
                ->orderByDesc('publish_date')
                ->orderByDesc('id')
                ->paginate(self::PER_PAGE),
        ]);
    }

    public function create(): View
    {
        return view('admin.blog-posts.form', ['blogPost' => new BlogPost]);
    }

    public function store(SaveBlogPostRequest $request): RedirectResponse
    {
        $blogPost = new BlogPost($request->postAttributes());
        $this->applyFeaturedImage($request, $blogPost);
        $blogPost->save();

        return redirect()->route('admin.blog-posts.index')->with('status', "Added {$blogPost->title}.");
    }

    public function edit(BlogPost $blogPost): View
    {
        return view('admin.blog-posts.form', ['blogPost' => $blogPost]);
    }

    public function update(SaveBlogPostRequest $request, BlogPost $blogPost): RedirectResponse
    {
        $blogPost->fill($request->postAttributes());
        $this->applyFeaturedImage($request, $blogPost);
        $blogPost->save();

        return redirect()->route('admin.blog-posts.index')->with('status', "Updated {$blogPost->title}.");
    }

    public function destroy(BlogPost $blogPost): RedirectResponse
    {
        $blogPost->delete();
        $this->imageStorage->deleteIfUploaded($blogPost->featured_image);

        return redirect()->route('admin.blog-posts.index')->with('status', "Deleted {$blogPost->title}.");
    }

    /**
     * Store a newly uploaded image or clear the current one when asked,
     * deleting the replaced file if it was one of our uploads.
     */
    private function applyFeaturedImage(SaveBlogPostRequest $request, BlogPost $blogPost): void
    {
        $uploadedImage = $request->file('featured_image');

        if ($uploadedImage === null && ! $request->boolean('remove_image')) {
            return;
        }

        $this->imageStorage->deleteIfUploaded($blogPost->featured_image);
        $blogPost->featured_image = $uploadedImage === null
            ? null
            : $this->imageStorage->store($uploadedImage, self::IMAGE_DIRECTORY);
    }
}
