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
}
