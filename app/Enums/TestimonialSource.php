<?php

namespace App\Enums;

enum TestimonialSource: string
{
    case Google = 'google';
    case Website = 'website';
    case Houzz = 'houzz';
    case Other = 'other';
}
