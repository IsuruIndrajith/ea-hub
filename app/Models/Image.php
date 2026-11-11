<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'imageable_type', 'imageable_id',
        'filename', 'url', 'alt_text', 'caption',
        'section', 'placement', 'sort_order',
    ];

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }
}