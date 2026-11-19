<?php

namespace Tests\Feature\Site;

use App\Models\BlogPost;
use App\Models\Faq;
use App\Models\Project;
use App\Models\ServiceArea;
use App\Models\Testimonial;
use Database\Seeders\SampleContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, array{string}>
     */
    public static function publicPaths(): array
    {
        return [
            'home' => ['/'],
            'about' => ['/about'],
            'services' => ['/services'],
            'projects' => ['/projects'],
            'process' => ['/process'],
            'reviews' => ['/reviews'],
            'faq' => ['/faq'],
            'service areas' => ['/service-areas'],
            'blog' => ['/blog'],
            'contact' => ['/contact'],
        ];
    }

    #[DataProvider('publicPaths')]
    public function test_public_page_renders_with_sample_content(string $path): void
    {
        $this->seed(SampleContentSeeder::class);

        $this->get($path)
            ->assertOk()
            ->assertSee(config('site.phone_display'));
    }

    #[DataProvider('publicPaths')]
    public function test_public_page_renders_with_an_empty_database(string $path): void
    {
        $this->get($path)->assertOk();
    }

    public function test_home_page_shows_featured_projects_and_testimonials_only(): void
    {
        Project::factory()->create(['title' => 'Featured Deck', 'is_featured' => true]);
        Project::factory()->create(['title' => 'Hidden Kitchen', 'is_featured' => false]);
        Testimonial::factory()->create(['client_name' => 'Featured Client', 'is_featured' => true]);
        Testimonial::factory()->create(['client_name' => 'Other Client', 'is_featured' => false]);

        $this->get('/')
            ->assertSee('Featured Deck')
            ->assertDontSee('Hidden Kitchen')
            ->assertSee('Featured Client')
            ->assertDontSee('Other Client');
    }

    public function test_projects_page_links_each_project_by_slug(): void
    {
        $project = Project::factory()->create(['title' => 'Modern Composite Deck', 'slug' => 'modern-composite-deck']);

        $this->get('/projects')
            ->assertSee('Modern Composite Deck')
            ->assertSee(route('projects.show', $project), false);
    }

    public function test_project_detail_page_is_found_by_slug(): void
    {
        Project::factory()->create([
            'title' => 'Spa-Inspired Bathroom',
            'slug' => 'spa-inspired-bathroom',
            'location' => 'Johns Creek',
            'testimonial' => 'Loved the heated floors.',
            'client_name' => 'Jennifer K.',
        ]);

        $this->get('/projects/spa-inspired-bathroom')
            ->assertOk()
            ->assertSee('Spa-Inspired Bathroom')
            ->assertSee('Johns Creek')
            ->assertSee('Loved the heated floors.');
    }

    public function test_unknown_project_slug_returns_not_found(): void
    {
        $this->get('/projects/does-not-exist')->assertNotFound();
    }

    public function test_blog_lists_only_published_posts(): void
    {
        BlogPost::factory()->create(['title' => 'Published Guide', 'is_published' => true]);
        BlogPost::factory()->create(['title' => 'Draft Guide', 'is_published' => false]);

        $this->get('/blog')
            ->assertSee('Published Guide')
            ->assertDontSee('Draft Guide');
    }

    public function test_published_blog_post_renders_markdown_content(): void
    {
        BlogPost::factory()->create([
            'slug' => 'deck-tips',
            'title' => 'Deck Tips',
            'content' => "## Pick composite\n\nIt lasts **longer**.",
        ]);

        $this->get('/blog/deck-tips')
            ->assertOk()
            ->assertSee('Deck Tips')
            ->assertSee('<h2>Pick composite</h2>', false)
            ->assertSee('<strong>longer</strong>', false);
    }

    public function test_unpublished_blog_post_returns_not_found(): void
    {
        BlogPost::factory()->create(['slug' => 'secret-draft', 'is_published' => false]);

        $this->get('/blog/secret-draft')->assertNotFound();
    }

    public function test_blog_post_content_cannot_inject_html(): void
    {
        BlogPost::factory()->create([
            'slug' => 'unsafe',
            'content' => "Hello <script>alert('x')</script> [click](javascript:alert(1))",
        ]);

        $this->get('/blog/unsafe')
            ->assertOk()
            ->assertDontSee("<script>alert('x')</script>", false)
            ->assertDontSee('href="javascript:', false);
    }

    public function test_database_text_is_escaped_on_listing_pages(): void
    {
        Project::factory()->create(['title' => '<script>alert(1)</script>', 'is_featured' => true]);
        Testimonial::factory()->create(['content' => '<img src=x onerror=alert(2)>']);

        $this->get('/projects')->assertDontSee('<script>alert(1)</script>', false);
        $this->get('/reviews')->assertDontSee('<img src=x onerror=alert(2)>', false);
    }

    public function test_faq_page_lists_questions_in_display_order(): void
    {
        Faq::factory()->create(['question' => 'Second question?', 'sort_order' => 2]);
        Faq::factory()->create(['question' => 'First question?', 'sort_order' => 1]);

        $this->get('/faq')->assertSeeInOrder(['First question?', 'Second question?']);
    }

    public function test_service_areas_page_lists_cities(): void
    {
        ServiceArea::factory()->create(['city' => 'Alpharetta', 'county' => 'Fulton', 'is_primary' => true]);
        ServiceArea::factory()->create(['city' => 'Kennesaw', 'county' => 'Cobb']);

        $this->get('/service-areas')
            ->assertSee('Alpharetta')
            ->assertSee('Kennesaw');
    }

    public function test_navigation_marks_the_current_page(): void
    {
        $this->get('/about')->assertSee('aria-current="page"', false);
    }
}
