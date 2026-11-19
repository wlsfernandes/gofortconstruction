<?php

namespace Tests\Feature\Site;

use Database\Seeders\SampleContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SelfHostedImagesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, array{string}>
     */
    public static function pagesWithImages(): array
    {
        return [
            'home' => ['/'],
            'about' => ['/about'],
            'services' => ['/services'],
            'projects' => ['/projects'],
            'project detail' => ['/projects/modern-composite-deck'],
            'process' => ['/process'],
            'reviews' => ['/reviews'],
            'faq' => ['/faq'],
            'service areas' => ['/service-areas'],
            'blog' => ['/blog'],
            'blog post' => ['/blog/deck-design-trends-2024'],
            'contact' => ['/contact'],
        ];
    }

    #[DataProvider('pagesWithImages')]
    public function test_every_image_is_served_from_this_site_and_exists(string $path): void
    {
        $this->seed(SampleContentSeeder::class);

        $html = $this->get($path)->assertOk()->getContent();
        // \s before src skips Alpine bindings such as :src="lightboxImages[...]".
        preg_match_all('/<img[^>]*\ssrc="([^"]+)"/', $html, $matches);

        $this->assertNotEmpty($matches[1], "{$path} should show at least the logo");

        foreach ($matches[1] as $imageSource) {
            $this->assertStringStartsWith('/images/', $imageSource, "{$path} loads an external image: {$imageSource}");
            $this->assertFileExists(public_path($imageSource), "{$path} references a missing file: {$imageSource}");
        }
    }
}
