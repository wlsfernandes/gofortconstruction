<?php

namespace App\Enums;

enum ContactStatus: string
{
    case New = 'new';
    case Contacted = 'contacted';
    case Quoted = 'quoted';
    case Scheduled = 'scheduled';
    case Completed = 'completed';
}
