<?php

namespace App\Enums;

enum ContactProjectType: string
{
    case Deck = 'deck';
    case Kitchen = 'kitchen';
    case Bathroom = 'bathroom';
    case OutdoorLiving = 'outdoor-living';
    case Renovation = 'renovation';
    case Other = 'other';

    /**
     * Display label used by the contact form and the notification email.
     */
    public function label(): string
    {
        return match ($this) {
            self::Deck => 'Deck Construction',
            self::Kitchen => 'Kitchen Remodeling',
            self::Bathroom => 'Bathroom Remodeling',
            self::OutdoorLiving => 'Outdoor Living',
            self::Renovation => 'Home Renovation',
            self::Other => 'Other Project',
        };
    }
}
