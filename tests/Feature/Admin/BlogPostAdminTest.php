<?php

namespace Tests\Feature\Admin;

use App\Enums\BlogCategory;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BlogPostAdminTest extends TestCase
{
    use RefreshDatabase;

    /** An external URL whose path mirrors a real file on our disk, to catch over-eager deletes. */
    private const EXTERNAL_IMAGE_URL = 'https://images.example.com/storage/blog/seeded.jpg';

    private const LOOKALIKE_PATH = 'blog/seeded.jpg';

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->actingAs(User::factory()->admin()->create());
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function validPayload(array $overrides = []): array
    {
        return [
            'title' => 'Choosing Countertops',
            'slug' => '',
            'excerpt' => 'A short guide.',
            'content' => 'Granite or **quartz**?',
            'category' => 'guides',
            'tags' => ' kitchen , , countertops ',
            'author' => '',
            'is_published' => '0',
            'publish_date' => '',
            ...$overrides,
        ];
    }

    private function storedPath(string $imageUrl): string
    {
        return str_replace('/storage/', '', $imageUrl);
    }

    public function test_index_lists_posts_and_marks_drafts(): void
    {
        BlogPost::factory()->create(['title' => 'Published Post', 'is_published' => true]);
        BlogPost::factory()->create(['title' => 'Unfinished Post', 'is_published' => false, 'publish_date' => null]);

        $this->get('/admin/blog-posts')
            ->assertOk()
            ->assertSee('Published Post')
            ->assertSee('Unfinished Post')
            ->assertSee('Draft');
    }

    public function test_admin_can_create_a_post_with_an_image(): void
    {
        $this->get('/admin/blog-posts/create')->assertOk()->assertSee('Markdown supported. HTML is shown as plain text.');

        $this->post('/admin/blog-posts', $this->validPayload([
            'featured_image' => UploadedFile::fake()->image('kitchen.jpg'),
        ]))->assertRedirect('/admin/blog-posts')->assertSessionHas('status');

        $blogPost = BlogPost::sole();
        $this->assertStringStartsWith('/storage/blog/', $blogPost->featured_image);
        Storage::disk('public')->assertExists($this->storedPath($blogPost->featured_image));
        $this->assertSame(BlogCategory::Guides, $blogPost->category);
        $this->assertSame(['kitchen', 'countertops'], $blogPost->tags);
    }

    public function test_slug_is_generated_from_the_title_when_blank(): void
    {
        $this->post('/admin/blog-posts', $this->validPayload())->assertSessionHasNoErrors();

        $this->assertSame('choosing-countertops', BlogPost::sole()->slug);
    }

    public function test_duplicate_slug_is_rejected(): void
    {
        BlogPost::factory()->create(['slug' => 'choosing-countertops']);

        $this->post('/admin/blog-posts', $this->validPayload())
            ->assertSessionHasErrors(['slug' => 'Another post already uses this slug.']);
    }

    public function test_saving_a_post_with_its_own_slug_passes_the_unique_rule(): void
    {
        $blogPost = BlogPost::factory()->create(['slug' => 'choosing-countertops']);

        $this->get("/admin/blog-posts/{$blogPost->id}/edit")->assertOk();

        $this->put("/admin/blog-posts/{$blogPost->id}", $this->validPayload(['slug' => 'choosing-countertops', 'title' => 'Renamed']))
            ->assertSessionHasNoErrors()
            ->assertRedirect('/admin/blog-posts');

        $this->assertSame('Renamed', $blogPost->refresh()->title);
    }

    public function test_publishing_without_a_date_sets_publish_date_to_today(): void
    {
        $this->post('/admin/blog-posts', $this->validPayload(['is_published' => '1']))->assertSessionHasNoErrors();

        $blogPost = BlogPost::sole();
        $this->assertTrue($blogPost->is_published);
        $this->assertTrue($blogPost->publish_date->isToday());
    }

    public function test_draft_without_a_date_keeps_publish_date_empty(): void
    {
        $this->post('/admin/blog-posts', $this->validPayload())->assertSessionHasNoErrors();

        $this->assertNull(BlogPost::sole()->publish_date);
    }

    public function test_blank_author_defaults_to_the_team_name(): void
    {
        $this->post('/admin/blog-posts', $this->validPayload())->assertSessionHasNoErrors();

        $this->assertSame('Fort Construction Team', BlogPost::sole()->author);
    }

    public function test_replacing_the_image_deletes_the_old_upload(): void
    {
        $oldPath = UploadedFile::fake()->image('old.jpg')->store('blog', 'public');
        $blogPost = BlogPost::factory()->create(['featured_image' => "/storage/{$oldPath}"]);

        $this->put("/admin/blog-posts/{$blogPost->id}", $this->validPayload([
            'featured_image' => UploadedFile::fake()->image('new.jpg'),
        ]))->assertSessionHasNoErrors();

        Storage::disk('public')->assertMissing($oldPath);
        $blogPost->refresh();
        $this->assertNotSame("/storage/{$oldPath}", $blogPost->featured_image);
        Storage::disk('public')->assertExists($this->storedPath($blogPost->featured_image));
    }

    public function test_replacing_an_external_image_never_deletes_a_stored_file(): void
    {
        Storage::disk('public')->put(self::LOOKALIKE_PATH, 'keep me');
        $blogPost = BlogPost::factory()->create(['featured_image' => self::EXTERNAL_IMAGE_URL]);

        $this->put("/admin/blog-posts/{$blogPost->id}", $this->validPayload([
            'featured_image' => UploadedFile::fake()->image('new.jpg'),
        ]))->assertSessionHasNoErrors();

        Storage::disk('public')->assertExists(self::LOOKALIKE_PATH);
        $this->assertStringStartsWith('/storage/blog/', $blogPost->refresh()->featured_image);
    }

    public function test_remove_image_checkbox_clears_and_deletes_the_upload(): void
    {
        $path = UploadedFile::fake()->image('old.jpg')->store('blog', 'public');
        $blogPost = BlogPost::factory()->create(['featured_image' => "/storage/{$path}"]);

        $this->put("/admin/blog-posts/{$blogPost->id}", $this->validPayload(['remove_image' => '1']))
            ->assertSessionHasNoErrors();

        $this->assertNull($blogPost->refresh()->featured_image);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_remove_image_checkbox_clears_an_external_image_without_deleting_files(): void
    {
        Storage::disk('public')->put(self::LOOKALIKE_PATH, 'keep me');
        $blogPost = BlogPost::factory()->create(['featured_image' => self::EXTERNAL_IMAGE_URL]);

        $this->put("/admin/blog-posts/{$blogPost->id}", $this->validPayload(['remove_image' => '1']))
            ->assertSessionHasNoErrors();

        $this->assertNull($blogPost->refresh()->featured_image);
        Storage::disk('public')->assertExists(self::LOOKALIKE_PATH);
    }

    public function test_admin_can_delete_a_post_and_its_uploaded_image(): void
    {
        $path = UploadedFile::fake()->image('old.jpg')->store('blog', 'public');
        $blogPost = BlogPost::factory()->create(['featured_image' => "/storage/{$path}"]);

        $this->delete("/admin/blog-posts/{$blogPost->id}")->assertRedirect('/admin/blog-posts');

        $this->assertModelMissing($blogPost);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_title_and_content_are_required(): void
    {
        $this->post('/admin/blog-posts', $this->validPayload(['title' => '', 'slug' => 'some-slug', 'content' => '']))
            ->assertSessionHasErrors(['title', 'content']);

        $this->assertDatabaseCount('blog_posts', 0);
    }

    public function test_unknown_category_is_rejected(): void
    {
        $this->post('/admin/blog-posts', $this->validPayload(['category' => 'gossip']))
            ->assertSessionHasErrors('category');
    }

    public function test_non_image_upload_is_rejected(): void
    {
        $this->post('/admin/blog-posts', $this->validPayload([
            'featured_image' => UploadedFile::fake()->create('notes.pdf', 10, 'application/pdf'),
        ]))->assertSessionHasErrors('featured_image');
    }

    public function test_slug_with_spaces_is_rejected(): void
    {
        $this->post('/admin/blog-posts', $this->validPayload(['slug' => 'not a slug']))
            ->assertSessionHasErrors('slug');
    }
}
