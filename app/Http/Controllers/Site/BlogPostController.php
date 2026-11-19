<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\View\View;

class BlogPostController extends Controller
{
    public function index(): View
    {
        return view('site.blog.index', [
            'posts' => BlogPost::where('is_published', true)->orderByDesc('publish_date')->get(),
        ]);
    }

    public function show(BlogPost $post): View
    {
        abort_unless($post->is_published, 404);

        return view('site.blog.show', ['post' => $post]);
    }
}
