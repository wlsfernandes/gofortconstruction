<?php

/*
|--------------------------------------------------------------------------
| Public Site Details
|--------------------------------------------------------------------------
|
| Business contact details shown in the header, footer and pages.
|
*/

return [
    'phone_display' => '470-887-FORT (3678)',
    'phone_dial' => '4708873678',
    'phone_short' => '470-887-3678',
    'email' => 'Support@GoFortConstruction.com',
    // Inbox that receives new estimate requests from the contact form.
    'contact_recipient' => env('CONTACT_RECIPIENT_EMAIL', 'Support@GoFortConstruction.com'),
    'service_area' => 'North Atlanta, GA',
    'service_area_tagline' => 'Serving North Atlanta & Surrounding Areas',
    'logo_url' => '/images/fort-construction-logo.png',
    'social_links' => [
        'facebook' => 'https://facebook.com/',
        'instagram' => 'http://instagram.com/fort_construction',
        'linkedin' => 'http://linkedin.com/',
    ],
];
