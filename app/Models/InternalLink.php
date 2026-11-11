<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class InternalLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'linkable_type', 'linkable_id',
        'url', 'anchor_text', 'location', 'sort_order',
    ];

    public function linkable(): MorphTo
    {
        return $this->morphTo();
    }
}