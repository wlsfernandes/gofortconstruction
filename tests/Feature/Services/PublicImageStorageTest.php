<?php

namespace Tests\Feature\Services;

use App\Services\PublicImageStorage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class PublicImageStorageTest extends TestCase
{
    private PublicImageStorage $imageStorage;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->imageStorage = new PublicImageStorage;
    }

    public function test_stored_image_gets_a_site_relative_storage_url(): void
    {
        $imageUrl = $this->imageStorage->store(UploadedFile::fake()->image('deck.jpg'), 'projects');

        $this->assertStringStartsWith('/storage/projects/', $imageUrl);
        Storage::disk('public')->assertExists(Str::after($imageUrl, '/storage/'));
    }

    public function test_uploaded_image_can_be_deleted(): void
    {
        $imageUrl = $this->imageStorage->store(UploadedFile::fake()->image('deck.jpg'), 'projects');

        $this->imageStorage->deleteIfUploaded($imageUrl);

        Storage::disk('public')->assertMissing(Str::after($imageUrl, '/storage/'));
    }

    public function test_external_urls_and_null_are_left_alone(): void
    {
        Storage::disk('public')->put('photo-1591825729269-caeb344f6df2', 'keep');

        $this->imageStorage->deleteIfUploaded('https://images.unsplash.com/photo-1591825729269-caeb344f6df2');
        $this->imageStorage->deleteIfUploaded(null);

        Storage::disk('public')->assertExists('photo-1591825729269-caeb344f6df2');
    }
}
