<?php

namespace App\Models;

use Database\Factories\ServiceAreaFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['city', 'county', 'state', 'description', 'is_primary'])]
class ServiceArea extends Model
{
    /** @use HasFactory<ServiceAreaFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
        ];
    }
}
