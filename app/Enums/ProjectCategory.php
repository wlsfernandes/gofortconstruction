<?php

namespace App\Enums;

enum ProjectCategory: string
{
    case Deck = 'deck';
    case Kitchen = 'kitchen';
    case Bathroom = 'bathroom';
    case OutdoorLiving = 'outdoor-living';
    case Renovation = 'renovation';
    case Custom = 'custom';

    /**
     * Display label used by the project category filter tabs.
     */
    public function label(): string
    {
        return match ($this) {
            self::Deck => 'Decks',
            self::Kitchen => 'Kitchens',
            self::Bathroom => 'Bathrooms',
            self::OutdoorLiving => 'Outdoor Living',
            self::Renovation => 'Renovations',
            self::Custom => 'Custom',
        };
    }
}
