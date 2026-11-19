<?php

namespace Database\Seeders;

use App\Enums\TestimonialSource;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /** The prototype homepage showed the first three reviews as featured. */
    private const FEATURED_TESTIMONIAL_COUNT = 3;

    private const FIVE_STARS = 5;

    public function run(): void
    {
        $testimonials = [
            ['Sarah L.', 'Cumming', 'Kitchen Remodel', TestimonialSource::Google, 'Fort Construction completely transformed my kitchen! The team was professional, communicative, and incredibly detail-oriented from start to finish. My new space feels bright, modern, and functional. The quartz countertops and white cabinetry look stunning with the white oak floors. They finished on time and stayed within budget, which made the whole experience stress-free. I couldn\'t be happier with the result!'],
            ['Michael R.', 'Alpharetta', 'Deck Construction', TestimonialSource::Google, 'Our new deck is absolutely stunning. The craftsmanship is impeccable—every board is perfectly aligned, and the cable railings give us an unobstructed view of our backyard. The team finished ahead of schedule despite some rainy weather. We\'ve already hosted several gatherings and received countless compliments. Highly recommend Fort Construction for any outdoor project.'],
            ['Jennifer K.', 'Johns Creek', 'Bathroom Remodel', TestimonialSource::Google, 'From the initial consultation to the final walkthrough, the experience was seamless. Our master bathroom renovation exceeded all expectations. The heated floors, walk-in shower with frameless glass, and double vanity are exactly what we envisioned. The team was respectful of our home and cleaned up every day. True professionals!'],
            ['David & Lisa M.', 'Milton', 'Outdoor Living', TestimonialSource::Google, 'We hired Fort Construction for a complete outdoor living renovation, including a covered patio, outdoor kitchen, and fire pit area. The design process was collaborative, and they brought ideas we hadn\'t even considered. The finished product is magazine-worthy. Our backyard is now our favorite place to be.'],
            ['Amanda T.', 'Roswell', 'Home Renovation', TestimonialSource::Google, 'After getting quotes from several contractors, we chose Fort Construction based on their transparent pricing and impressive portfolio. They didn\'t disappoint. Our whole-home renovation was a massive undertaking, but they coordinated everything flawlessly. Communication was excellent throughout the 3-month project.'],
            ['Robert H.', 'Suwanee', 'Basement Finishing', TestimonialSource::Website, 'The basement finishing project turned out better than we could have imagined. Fort Construction helped us maximize the space with a home theater, wet bar, and guest bedroom. The attention to detail in the trim work and built-ins is exceptional. Highly professional team.'],
        ];

        foreach ($testimonials as $position => [$clientName, $location, $projectType, $source, $content]) {
            Testimonial::updateOrCreate(
                ['client_name' => $clientName, 'project_type' => $projectType],
                [
                    'location' => $location,
                    'source' => $source,
                    'content' => $content,
                    'rating' => self::FIVE_STARS,
                    'is_featured' => $position < self::FEATURED_TESTIMONIAL_COUNT,
                ],
            );
        }
    }
}
