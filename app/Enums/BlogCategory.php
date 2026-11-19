<?php

namespace App\Enums;

enum BlogCategory: string
{
    case Tips = 'tips';
    case Trends = 'trends';
    case Projects = 'projects';
    case News = 'news';
    case Guides = 'guides';
}
