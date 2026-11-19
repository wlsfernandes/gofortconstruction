<?php

namespace Tests\Feature\Admin;

use App\Enums\ProjectCategory;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProjectAdminTest extends TestCase
{
    use RefreshDatabase;

    private const EXTERNAL_IMAGE_URL = 'https://images.unsplash.com/photo-123';

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
    private function validInput(array $overrides = []): array
    {
        return [
            'title' => 'Cedar Deck Rebuild',
            'slug' => '',
            'location' => 'Roswell',
            'category' => ProjectCategory::Deck->value,
            'short_description' => 'A new cedar deck.',
            'description' => 'Full rebuild.',
            'completion_date' => '2026-05-01',
            'client_name' => 'Jane',
            'testimonial' => 'Great work.',
            'is_featured' => '1',
            'tags' => '',
            ...$overrides,
        ];
    }

    /**
     * Store a fake image on the public disk the same way an upload would be.
     */
    private function uploadedImageUrl(string $name): string
    {
        $path = UploadedFile::fake()->image($name)->store('projects', 'public');

        return '/storage/'.$path;
    }

    private function assertUploadExists(string $imageUrl): void
    {
        Storage::disk('public')->assertExists(Str::after($imageUrl, '/storage/'));
    }

    private function assertUploadMissing(string $imageUrl): void
    {
        Storage::disk('public')->assertMissing(Str::after($imageUrl, '/storage/'));
    }

    public function test_index_lists_projects_newest_first(): void
    {
        Project::factory()->create(['title' => 'Older Kitchen', 'created_at' => now()->subDay()]);
        Project::factory()->create(['title' => 'Newer Deck', 'category' => ProjectCategory::Deck]);

        $this->get('/admin/projects')->assertOk()->assertSeeInOrder(['Newer Deck', 'Older Kitchen'])->assertSee('Decks');
    }

    public function test_create_and_edit_forms_render(): void
    {
        $project = Project::factory()->create([
            'title' => 'Spa Bathroom',
            'gallery_images' => [self::EXTERNAL_IMAGE_URL],
            'tags' => ['tile', 'shower'],
        ]);

        $this->get('/admin/projects/create')->assertOk();
        $this->get("/admin/projects/{$project->id}/edit")
            ->assertOk()
            ->assertSee('Spa Bathroom')
            ->assertSee('tile, shower')
            ->assertSee('name="remove_gallery_images[]"', false);
    }

    public function test_admin_can_add_a_project_with_featured_and_gallery_uploads(): void
    {
        $this->post('/admin/projects', $this->validInput([
            'featured_image' => UploadedFile::fake()->image('front.jpg'),
            'gallery_images' => [UploadedFile::fake()->image('one.jpg'), UploadedFile::fake()->image('two.png')],
        ]))->assertRedirect('/admin/projects')->assertSessionHas('status');

        $project = Project::sole();
        $this->assertStringStartsWith('/storage/projects/', $project->featured_image);
        $this->assertUploadExists($project->featured_image);
        $this->assertCount(2, $project->gallery_images);
        foreach ($project->gallery_images as $imageUrl) {
            $this->assertStringStartsWith('/storage/projects/', $imageUrl);
            $this->assertUploadExists($imageUrl);
        }
        $this->assertTrue($project->is_featured);
        $this->assertSame(ProjectCategory::Deck, $project->category);
    }

    public function test_blank_slug_is_generated_from_the_title(): void
    {
        $this->post('/admin/projects', $this->validInput(['title' => 'Cedar Deck Rebuild']))->assertSessionHasNoErrors();

        $this->assertSame('cedar-deck-rebuild', Project::sole()->slug);
    }

    public function test_duplicate_slug_is_rejected(): void
    {
        Project::factory()->create(['slug' => 'cedar-deck-rebuild']);

        $this->post('/admin/projects', $this->validInput(['title' => 'Cedar Deck Rebuild']))
            ->assertSessionHasErrors(['slug' => 'Another project already uses this slug.']);
    }

    public function test_slug_with_invalid_characters_is_rejected(): void
    {
        $this->post('/admin/projects', $this->validInput(['slug' => 'not a slug!']))->assertSessionHasErrors('slug');
    }

    public function test_saving_unchanged_slug_does_not_trip_the_unique_rule(): void
    {
        $project = Project::factory()->create(['slug' => 'cedar-deck-rebuild']);

        $this->put("/admin/projects/{$project->id}", $this->validInput(['slug' => 'cedar-deck-rebuild', 'title' => 'Renamed']))
            ->assertSessionHasNoErrors()
            ->assertRedirect('/admin/projects');

        $this->assertSame('Renamed', $project->refresh()->title);
    }

    public function test_removing_a_gallery_image_deletes_its_file_and_keeps_the_others(): void
    {
        $removedImage = $this->uploadedImageUrl('removed.jpg');
        $keptImage = $this->uploadedImageUrl('kept.jpg');
        $project = Project::factory()->create(['gallery_images' => [$removedImage, $keptImage]]);

        $this->put("/admin/projects/{$project->id}", $this->validInput([
            'slug' => $project->slug,
            'remove_gallery_images' => [$removedImage],
            'gallery_images' => [UploadedFile::fake()->image('new.jpg')],
        ]))->assertSessionHasNoErrors();

        $galleryImages = $project->refresh()->gallery_images;
        $this->assertCount(2, $galleryImages);
        $this->assertSame($keptImage, $galleryImages[0]);
        $this->assertNotContains($removedImage, $galleryImages);
        $this->assertUploadMissing($removedImage);
        $this->assertUploadExists($keptImage);
        $this->assertUploadExists($galleryImages[1]);
    }

    public function test_removing_a_url_that_is_not_on_the_project_is_ignored(): void
    {
        $otherProjectImage = $this->uploadedImageUrl('other.jpg');
        $keptImage = $this->uploadedImageUrl('kept.jpg');
        $project = Project::factory()->create(['gallery_images' => [$keptImage]]);

        $this->put("/admin/projects/{$project->id}", $this->validInput([
            'slug' => $project->slug,
            'remove_gallery_images' => [$otherProjectImage],
            'remove_before_images' => [$keptImage],
        ]))->assertSessionHasNoErrors();

        $this->assertSame([$keptImage], $project->refresh()->gallery_images);
        $this->assertSame([], $project->before_images);
        $this->assertUploadExists($otherProjectImage);
        $this->assertUploadExists($keptImage);
    }

    public function test_replacing_the_featured_image_deletes_the_old_upload(): void
    {
        $oldImage = $this->uploadedImageUrl('old.jpg');
        $project = Project::factory()->create(['featured_image' => $oldImage]);

        $this->put("/admin/projects/{$project->id}", $this->validInput([
            'slug' => $project->slug,
            'featured_image' => UploadedFile::fake()->image('new.jpg'),
        ]))->assertSessionHasNoErrors();

        $newImage = $project->refresh()->featured_image;
        $this->assertNotSame($oldImage, $newImage);
        $this->assertUploadMissing($oldImage);
        $this->assertUploadExists($newImage);
    }

    public function test_replacing_an_external_featured_image_leaves_storage_untouched(): void
    {
        $unrelatedUpload = $this->uploadedImageUrl('unrelated.jpg');
        $project = Project::factory()->create(['featured_image' => self::EXTERNAL_IMAGE_URL]);

        $this->put("/admin/projects/{$project->id}", $this->validInput([
            'slug' => $project->slug,
            'featured_image' => UploadedFile::fake()->image('new.jpg'),
        ]))->assertSessionHasNoErrors();

        $this->assertStringStartsWith('/storage/projects/', $project->refresh()->featured_image);
        $this->assertUploadExists($unrelatedUpload);
        $this->assertCount(2, Storage::disk('public')->allFiles('projects'));
    }

    public function test_saving_without_a_new_featured_image_keeps_the_current_one(): void
    {
        $project = Project::factory()->create(['featured_image' => self::EXTERNAL_IMAGE_URL]);

        $this->put("/admin/projects/{$project->id}", $this->validInput(['slug' => $project->slug]))->assertSessionHasNoErrors();

        $this->assertSame(self::EXTERNAL_IMAGE_URL, $project->refresh()->featured_image);
    }

    public function test_admin_can_delete_a_project_and_its_uploaded_images(): void
    {
        $featuredImage = $this->uploadedImageUrl('featured.jpg');
        $beforeImage = $this->uploadedImageUrl('before.jpg');
        $galleryImage = $this->uploadedImageUrl('gallery.jpg');
        $project = Project::factory()->create([
            'featured_image' => $featuredImage,
            'before_images' => [$beforeImage],
            'after_images' => [self::EXTERNAL_IMAGE_URL],
            'gallery_images' => [$galleryImage],
        ]);

        $this->delete("/admin/projects/{$project->id}")->assertRedirect('/admin/projects')->assertSessionHas('status');

        $this->assertModelMissing($project);
        $this->assertUploadMissing($featuredImage);
        $this->assertUploadMissing($beforeImage);
        $this->assertUploadMissing($galleryImage);
    }

    public function test_title_is_required(): void
    {
        $this->post('/admin/projects', $this->validInput(['title' => '']))->assertSessionHasErrors('title');

        $this->assertSame(0, Project::count());
    }

    public function test_unknown_category_is_rejected(): void
    {
        $this->post('/admin/projects', $this->validInput(['category' => 'spaceship']))->assertSessionHasErrors('category');
    }

    public function test_non_image_uploads_are_rejected(): void
    {
        $this->post('/admin/projects', $this->validInput([
            'featured_image' => UploadedFile::fake()->create('notes.pdf', 10, 'application/pdf'),
            'gallery_images' => [UploadedFile::fake()->create('virus.exe', 10)],
        ]))->assertSessionHasErrors(['featured_image', 'gallery_images.0']);

        $this->assertSame([], Storage::disk('public')->allFiles());
    }

    public function test_more_than_twenty_files_in_one_list_is_rejected(): void
    {
        $tooManyImages = array_map(fn (int $index): UploadedFile => UploadedFile::fake()->image("{$index}.jpg", 1, 1), range(1, 21));

        $this->post('/admin/projects', $this->validInput(['after_images' => $tooManyImages]))->assertSessionHasErrors('after_images');
    }

    public function test_tags_string_is_stored_as_a_trimmed_array_without_blanks(): void
    {
        $this->post('/admin/projects', $this->validInput(['tags' => ' cedar,  deck , ,railing,']))->assertSessionHasNoErrors();

        $this->assertSame(['cedar', 'deck', 'railing'], Project::sole()->tags);
    }

    public function test_short_description_over_500_characters_is_rejected(): void
    {
        $this->post('/admin/projects', $this->validInput(['short_description' => str_repeat('a', 501)]))
            ->assertSessionHasErrors('short_description');
    }
}
