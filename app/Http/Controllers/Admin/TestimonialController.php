<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TestimonialSource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveTestimonialRequest;
use App\Models\Testimonial;
use App\Services\PublicImageStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    private const PER_PAGE = 25;

    private const IMAGE_DIRECTORY = 'testimonials';

    public function __construct(private readonly PublicImageStorage $imageStorage) {}

    public function index(): View
    {
        return view('admin.testimonials.index', [
            'testimonials' => Testimonial::latest()->latest('id')->paginate(self::PER_PAGE),
        ]);
    }

    public function create(): View
    {
        return view('admin.testimonials.form', [
            'testimonial' => new Testimonial(['rating' => 5, 'source' => TestimonialSource::Website]),
        ]);
    }

    public function store(SaveTestimonialRequest $request): RedirectResponse
    {
        $testimonial = new Testimonial($request->safe()->except(['image', 'remove_image']));
        $this->applyImageChange($request, $testimonial);
        $testimonial->save();

        return redirect()->route('admin.testimonials.index')->with('status', "Added testimonial from {$testimonial->client_name}.");
    }

    public function edit(Testimonial $testimonial): View
    {
        return view('admin.testimonials.form', ['testimonial' => $testimonial]);
    }

    public function update(SaveTestimonialRequest $request, Testimonial $testimonial): RedirectResponse
    {
        $testimonial->fill($request->safe()->except(['image', 'remove_image']));
        $this->applyImageChange($request, $testimonial);
        $testimonial->save();

        return redirect()->route('admin.testimonials.index')->with('status', "Updated testimonial from {$testimonial->client_name}.");
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->delete();
        $this->imageStorage->deleteIfUploaded($testimonial->image);

        return redirect()->route('admin.testimonials.index')->with('status', "Deleted testimonial from {$testimonial->client_name}.");
    }

    /**
     * Store a newly uploaded image or clear the current one, deleting the
     * replaced file when it was one of our uploads.
     */
    private function applyImageChange(SaveTestimonialRequest $request, Testimonial $testimonial): void
    {
        $uploadedImage = $request->file('image');

        if ($uploadedImage === null && ! $request->boolean('remove_image')) {
            return;
        }

        $this->imageStorage->deleteIfUploaded($testimonial->image);
        $testimonial->image = $uploadedImage === null
            ? null
            : $this->imageStorage->store($uploadedImage, self::IMAGE_DIRECTORY);
    }
}
