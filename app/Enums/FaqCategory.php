<?php

namespace App\Enums;

enum FaqCategory: string
{
    case General = 'general';
    case Pricing = 'pricing';
    case Process = 'process';
    case Services = 'services';
    case Warranty = 'warranty';

    public function label(): string
    {
        return match ($this) {
            self::General => 'General',
            self::Pricing => 'Pricing',
            self::Process => 'Process',
            self::Services => 'Services',
            self::Warranty => 'Warranty',
        };
    }
}
