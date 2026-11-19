<?php

namespace App\Actions\Admin;

use App\Http\Requests\Admin\SaveProjectRequest;
use App\Models\Project;
use App\Services\PublicImageStorage;
use Illuminate\Http\UploadedFile;

class SaveProject
{
    private const UPLOAD_DIRECTORY = 'projects';

    public function __construct(private readonly PublicImageStorage $imageStorage) {}

    /**
     * Create or update a project from the admin form, storing new uploads and
     * deleting uploads the admin removed or replaced.
     */
    public function handle(Project $project, SaveProjectRequest $request): Project
    {
        $project->fill($request->safe()->except([
            'tags', 'featured_image',
            ...SaveProjectRequest::IMAGE_LIST_FIELDS,
            ...array_map(fn (string $field): string => 'remove_'.$field, SaveProjectRequest::IMAGE_LIST_FIELDS),
        ]));
        $project->tags = $this->parseTags($request->validated('tags'));

        $replacedFeaturedImage = $this->applyFeaturedImage($project, $request->file('featured_image'));
        $removedImages = [];

        foreach (SaveProjectRequest::IMAGE_LIST_FIELDS as $field) {
            $removedImages = [...$removedImages, ...$this->applyImageList($project, $request, $field)];
        }

        $project->save();

        foreach ([$replacedFeaturedImage, ...$removedImages] as $imageUrl) {
            $this->imageStorage->deleteIfUploaded($imageUrl);
        }

        return $project;
    }

    /**
     * Delete a project together with every image we uploaded for it.
     */
    public function delete(Project $project): void
    {
        $imageUrls = [$project->featured_image];

        foreach (SaveProjectRequest::IMAGE_LIST_FIELDS as $field) {
            $imageUrls = [...$imageUrls, ...($project->{$field} ?? [])];
        }

        $project->delete();

        foreach ($imageUrls as $imageUrl) {
            $this->imageStorage->deleteIfUploaded($imageUrl);
        }
    }

    /**
     * @return list<string>
     */
    private function parseTags(?string $tagsInput): array
    {
        $tags = array_map('trim', explode(',', $tagsInput ?? ''));

        return array_values(array_filter($tags, fn (string $tag): bool => $tag !== ''));
    }

    /**
     * @return string|null the previous image URL when it was replaced
     */
    private function applyFeaturedImage(Project $project, ?UploadedFile $featuredImage): ?string
    {
        if ($featuredImage === null) {
            return null;
        }

        $previousImageUrl = $project->featured_image;
        $project->featured_image = $this->imageStorage->store($featuredImage, self::UPLOAD_DIRECTORY);

        return $previousImageUrl;
    }

    /**
     * Drop ticked images that really belong to the project and append new uploads.
     *
     * @return list<string> the removed image URLs
     */
    private function applyImageList(Project $project, SaveProjectRequest $request, string $field): array
    {
        $currentImages = $project->{$field} ?? [];
        $removalRequests = $request->validated('remove_'.$field) ?? [];
        $removedImages = array_values(array_intersect($currentImages, $removalRequests));
        $keptImages = array_values(array_diff($currentImages, $removedImages));

        foreach ($request->file($field) ?? [] as $uploadedImage) {
            $keptImages[] = $this->imageStorage->store($uploadedImage, self::UPLOAD_DIRECTORY);
        }

        $project->{$field} = $keptImages;

        return $removedImages;
    }
}
