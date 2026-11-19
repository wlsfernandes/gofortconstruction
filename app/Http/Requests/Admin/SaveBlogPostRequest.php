<?php

namespace App\Http\Requests\Admin;

use App\Enums\BlogCategory;
use App\Models\BlogPost;
use App\Services\PublicImageStorage;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SaveBlogPostRequest extends FormRequest
{
    public const DEFAULT_AUTHOR = 'Fort Construction Team';

    /**
     * Admin routes are already behind the manage-site gate; re-check here so
     * the request is safe if it is ever reused on another route.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('manage-site') ?? false;
    }

    /**
     * Fill the optional fields that have a sensible default before validating,
     * so the generated slug is checked by the unique rule too.
     */
    protected function prepareForValidation(): void
    {
        $slug = trim((string) $this->input('slug'));
        $author = trim((string) $this->input('author'));

        $this->merge([
            'slug' => $slug !== '' ? $slug : Str::slug((string) $this->input('title')),
            'author' => $author !== '' ? $author : self::DEFAULT_AUTHOR,
        ]);

        if ($this->boolean('is_published') && blank($this->input('publish_date'))) {
            $this->merge(['publish_date' => today()->toDateString()]);
        }
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var BlogPost|null $blogPost */
        $blogPost = $this->route('blog_post');

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('blog_posts')->ignore($blogPost)],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string', 'max:50000'],
            'category' => ['nullable', Rule::enum(BlogCategory::class)],
            'tags' => ['nullable', 'string', 'max:1000'],
            'author' => ['required', 'string', 'max:255'],
            'is_published' => ['required', 'boolean'],
            'publish_date' => ['nullable', 'date'],
            'featured_image' => ['nullable', ...PublicImageStorage::IMAGE_RULES],
            'remove_image' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'slug.unique' => 'Another post already uses this slug.',
            'slug.required' => 'Enter a slug, or a title that contains letters or numbers.',
        ];
    }

    /**
     * The validated values as BlogPost attributes: image fields are handled
     * separately and the comma-separated tags become a list.
     *
     * @return array<string, mixed>
     */
    public function postAttributes(): array
    {
        return [
            ...$this->safe()->except(['featured_image', 'remove_image', 'tags']),
            'tags' => $this->tagList(),
        ];
    }

    /**
     * @return list<string>
     */
    private function tagList(): array
    {
        $tags = array_map(trim(...), explode(',', (string) $this->validated('tags')));

        return array_values(array_filter($tags, fn (string $tag): bool => $tag !== ''));
    }
}
