<?php

namespace App\Enums;

enum BlogCategory: string
{
    case Tips = 'tips';
    case Trends = 'trends';
    case Projects = 'projects';
    case News = 'news';
    case Guides = 'guides';

    public function label(): string
    {
        return match ($this) {
            self::Tips => 'Tips & Advice',
            self::Trends => 'Trends',
            self::Projects => 'Project Spotlights',
            self::News => 'News',
            self::Guides => 'Guides',
        };
    }
}
