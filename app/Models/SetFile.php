<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SetFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'set_review_id', 'name', 'purpose', 'values', 'groups',
        'risk_params', 'differences', 'limitations', 'sort_order',
    ];

    protected $casts = [
        'values'      => 'array',
        'groups'      => 'array',
        'risk_params' => 'array',
    ];

    public function setReview(): BelongsTo
    {
        return $this->belongsTo(SetReview::class);
    }

    public function parameters(): HasMany
    {
        return $this->hasMany(SetParameter::class)->orderBy('sort_order');
    }
}