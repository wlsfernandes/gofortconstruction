<?php

namespace App\Models;

use App\Enums\BlogCategory;
use Database\Factories\BlogPostFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'slug', 'excerpt', 'content', 'featured_image', 'category', 'tags', 'author', 'is_published', 'publish_date'])]
class BlogPost extends Model
{
    /** @use HasFactory<BlogPostFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'category' => BlogCategory::class,
            'tags' => 'array',
            'is_published' => 'boolean',
            'publish_date' => 'date',
        ];
    }
}
