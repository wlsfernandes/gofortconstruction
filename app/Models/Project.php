<?php

namespace App\Models;

use App\Enums\ProjectCategory;
use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'slug', 'location', 'category', 'short_description', 'description', 'featured_image', 'before_images', 'after_images', 'gallery_images', 'tags', 'is_featured', 'completion_date', 'testimonial', 'client_name'])]
class Project extends Model
{
    /** @use HasFactory<ProjectFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'category' => ProjectCategory::class,
            'before_images' => 'array',
            'after_images' => 'array',
            'gallery_images' => 'array',
            'tags' => 'array',
            'is_featured' => 'boolean',
            'completion_date' => 'date',
        ];
    }
}
