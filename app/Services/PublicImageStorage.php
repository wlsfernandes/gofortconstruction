<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Stores admin-uploaded images on the public disk.
 *
 * Image columns hold either an external URL (seeded content) or a
 * site-relative /storage/... path for uploads; only the latter are ours
 * to delete.
 */
class PublicImageStorage
{
    /** Validation rules for one uploaded image (5 MB maximum). */
    public const IMAGE_RULES = ['image', 'mimes:jpeg,png,webp', 'max:5120'];

    private const DISK = 'public';

    private const URL_PREFIX = '/storage/';

    public function store(UploadedFile $image, string $directory): string
    {
        return self::URL_PREFIX.$image->store($directory, self::DISK);
    }

    public function deleteIfUploaded(?string $imageUrl): void
    {
        if ($imageUrl === null || ! Str::startsWith($imageUrl, self::URL_PREFIX)) {
            return;
        }

        Storage::disk(self::DISK)->delete(Str::after($imageUrl, self::URL_PREFIX));
    }
}
