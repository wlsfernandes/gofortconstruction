<?php

namespace Tests\Feature\Admin;

use App\Enums\TestimonialSource;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class TestimonialAdminTest extends TestCase
{
    use RefreshDatabase;

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
        return array_merge([
            'client_name' => 'Dana Whitfield',
            'location' => 'Marietta, GA',
            'content' => 'They finished our kitchen on time.',
            'rating' => '5',
            'project_type' => 'Kitchen remodel',
            'source' => 'google',
            'is_featured' => '0',
        ], $overrides);
    }

    private function storedPath(string $imageUrl): string
    {
        return Str::after($imageUrl, '/storage/');
    }

    public function test_index_lists_testimonials_with_rating_and_source(): void
    {
        Testimonial::factory()->create([
            'client_name' => 'Dana Whitfield',
            'rating' => 4,
            'source' => TestimonialSource::Houzz,
        ]);

        $this->get('/admin/testimonials')
            ->assertOk()
            ->assertSee('Dana Whitfield')
            ->assertSee('4/5')
            ->assertSee('Houzz');
    }

    public function test_admin_can_add_a_testimonial_with_an_image(): void
    {
        $this->post('/admin/testimonials', $this->validPayload([
            'is_featured' => '1',
            'image' => UploadedFile::fake()->image('dana.jpg'),
        ]))->assertRedirect('/admin/testimonials')->assertSessionHas('status');

        $testimonial = Testimonial::sole();
        $this->assertSame('Dana Whitfield', $testimonial->client_name);
        $this->assertSame(5, $testimonial->rating);
        $this->assertSame(TestimonialSource::Google, $testimonial->source);
        $this->assertTrue($testimonial->is_featured);
        $this->assertStringStartsWith('/storage/testimonials/', $testimonial->image);
        Storage::disk('public')->assertExists($this->storedPath($testimonial->image));
    }

    public function test_rating_outside_one_to_five_is_rejected(): void
    {
        $this->post('/admin/testimonials', $this->validPayload(['rating' => '0']))->assertSessionHasErrors('rating');
        $this->post('/admin/testimonials', $this->validPayload(['rating' => '6']))->assertSessionHasErrors('rating');

        $this->assertDatabaseCount('testimonials', 0);
    }

    public function test_unknown_source_is_rejected(): void
    {
        $this->post('/admin/testimonials', $this->validPayload(['source' => 'yelp']))->assertSessionHasErrors('source');

        $this->assertDatabaseCount('testimonials', 0);
    }

    public function test_replacing_an_image_deletes_the_old_upload(): void
    {
        $oldPath = UploadedFile::fake()->image('old.jpg')->store('testimonials', 'public');
        $testimonial = Testimonial::factory()->create(['image' => '/storage/'.$oldPath]);

        $this->put("/admin/testimonials/{$testimonial->id}", $this->validPayload([
            'image' => UploadedFile::fake()->image('new.jpg'),
        ]))->assertRedirect('/admin/testimonials');

        $testimonial->refresh();
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($this->storedPath($testimonial->image));
        $this->assertNotSame('/storage/'.$oldPath, $testimonial->image);
    }

    public function test_replacing_an_external_image_url_leaves_storage_untouched(): void
    {
        Storage::disk('public')->put('keep.jpg', 'contents');
        $testimonial = Testimonial::factory()->create(['image' => 'https://images.example.com/keep.jpg']);

        $this->put("/admin/testimonials/{$testimonial->id}", $this->validPayload([
            'image' => UploadedFile::fake()->image('new.jpg'),
        ]))->assertRedirect('/admin/testimonials');

        Storage::disk('public')->assertExists('keep.jpg');
        $this->assertStringStartsWith('/storage/testimonials/', $testimonial->refresh()->image);
    }

    public function test_saving_without_a_new_image_keeps_the_current_one(): void
    {
        $testimonial = Testimonial::factory()->create(['image' => 'https://images.example.com/keep.jpg']);

        $this->put("/admin/testimonials/{$testimonial->id}", $this->validPayload())->assertRedirect('/admin/testimonials');

        $this->assertSame('https://images.example.com/keep.jpg', $testimonial->refresh()->image);
    }

    public function test_remove_image_checkbox_clears_and_deletes_the_upload(): void
    {
        $path = UploadedFile::fake()->image('old.jpg')->store('testimonials', 'public');
        $testimonial = Testimonial::factory()->create(['image' => '/storage/'.$path]);

        $this->put("/admin/testimonials/{$testimonial->id}", $this->validPayload(['remove_image' => '1']))
            ->assertRedirect('/admin/testimonials');

        $this->assertNull($testimonial->refresh()->image);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_deleting_a_testimonial_removes_its_uploaded_image(): void
    {
        $path = UploadedFile::fake()->image('old.jpg')->store('testimonials', 'public');
        $testimonial = Testimonial::factory()->create(['image' => '/storage/'.$path]);

        $this->delete("/admin/testimonials/{$testimonial->id}")->assertRedirect('/admin/testimonials');

        $this->assertModelMissing($testimonial);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_create_form_renders(): void
    {
        $this->get('/admin/testimonials/create')->assertOk()->assertSee('Add testimonial');
    }

    public function test_edit_form_shows_the_testimonial(): void
    {
        $testimonial = Testimonial::factory()->create(['client_name' => 'Dana Whitfield']);

        $this->get("/admin/testimonials/{$testimonial->id}/edit")->assertOk()->assertSee('Dana Whitfield');
    }

    public function test_required_fields_are_enforced(): void
    {
        $this->post('/admin/testimonials', [])
            ->assertSessionHasErrors(['client_name', 'content', 'rating', 'source']);
    }
}
