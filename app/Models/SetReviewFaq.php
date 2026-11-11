<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SetReviewFaq extends Model
{
    use HasFactory;

    protected $fillable = ['set_review_id', 'question', 'answer', 'sort_order'];

    public function setReview(): BelongsTo
    {
        return $this->belongsTo(SetReview::class);
    }
}