<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeneratedContent extends Model
{
    use HasFactory;

    public const TYPE_PRODUCT_PAGE = 'product_page';
    public const TYPE_SET_REVIEW   = 'set_review';

    protected $fillable = ['ea_id', 'type', 'content'];

    protected $casts = [
        'content' => 'array',
    ];

    public function ea(): BelongsTo
    {
        return $this->belongsTo(Ea::class);
    }
}