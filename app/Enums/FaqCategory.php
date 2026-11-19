<?php

namespace App\Enums;

enum FaqCategory: string
{
    case General = 'general';
    case Pricing = 'pricing';
    case Process = 'process';
    case Services = 'services';
    case Warranty = 'warranty';
}
