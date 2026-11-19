<?php

namespace Database\Seeders;

use App\Enums\FaqCategory;
use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [FaqCategory::General, 'How do I get started with a project?', 'Getting started is easy! Simply contact us to schedule a free consultation. We\'ll meet at your home to discuss your vision, assess the space, answer your questions, and provide a detailed estimate—all at no cost to you.'],
            [FaqCategory::Pricing, 'How much does a typical project cost?', 'Every project is unique, so costs vary based on scope, materials, and complexity. During your free consultation, we\'ll provide a detailed, transparent estimate. We never have hidden fees, and we\'ll work with you to find solutions that fit your budget.'],
            [FaqCategory::Process, 'How long will my project take?', 'Project timelines depend on the scope of work. A deck typically takes 1-2 weeks, while a kitchen or bathroom remodel might take 3-6 weeks. We\'ll provide a detailed timeline during the planning phase and keep you updated throughout the project.'],
            [FaqCategory::Process, 'Do you handle permits?', 'Yes, we handle all necessary permits for your project. Permit requirements vary by city and project type, and we\'re familiar with local regulations throughout North Atlanta. We\'ll take care of the paperwork so you don\'t have to.'],
            [FaqCategory::Pricing, 'What payment options do you offer?', 'We offer flexible payment options including credit cards and checks. Typically, we require a deposit to begin work, with the remaining balance due upon completion. For larger projects, we can arrange a milestone-based payment schedule.'],
            [FaqCategory::Warranty, 'Do you offer warranties on your work?', 'Absolutely! We stand behind our craftsmanship with a comprehensive workmanship warranty. Additionally, many of the materials we use come with manufacturer warranties. We\'ll provide all warranty documentation upon project completion.'],
            [FaqCategory::Process, 'Will I need to be home during the project?', 'Not necessarily. Many clients continue their normal routines while we work. We\'ll establish a communication plan and can work out secure access to your home. However, we recommend being present for the initial walkthrough and final inspection.'],
            [FaqCategory::General, 'What areas do you serve?', 'We proudly serve North Atlanta and surrounding areas including Cumming, Alpharetta, Johns Creek, Milton, Roswell, Suwanee, Dunwoody, Sandy Springs, and more. Contact us to confirm service in your area.'],
            [FaqCategory::Services, 'What services do you offer?', 'We specialize in deck construction, kitchen remodeling, bathroom remodeling, outdoor living spaces, home renovations, and custom projects. Whether you\'re looking for a simple update or a complete transformation, we can help.'],
        ];

        foreach ($faqs as $position => [$category, $question, $answer]) {
            Faq::updateOrCreate(
                ['question' => $question],
                ['category' => $category, 'answer' => $answer, 'sort_order' => $position + 1],
            );
        }
    }
}
