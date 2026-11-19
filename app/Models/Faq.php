<?php

namespace App\Models;

use App\Enums\FaqCategory;
use Database\Factories\FaqFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['question', 'answer', 'category', 'sort_order', 'is_featured'])]
class Faq extends Model
{
    /** @use HasFactory<FaqFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'category' => FaqCategory::class,
            'sort_order' => 'integer',
            'is_featured' => 'boolean',
        ];
    }
}
