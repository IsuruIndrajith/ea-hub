<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class SetReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'ea_id', 'vendor_docs', 'config_notes',
        'focus_keyword', 'secondary_keywords', 'search_intent',
        'meta_title_1', 'meta_title_2', 'meta_title_3',
        'recommended_meta_title', 'meta_description',
        'recommended_slug', 'suggested_h1',
        'seo_introduction', 'specs_table', 'set_comparison_table',
        'parameter_classification_summary', 'important_parameters',
        'requirements_table', 'load_set_steps',
        'customization_guidance', 'customization_cautions',
        'config_strengths_limitations', 'who_should_use',
        'final_verdict', 'product_page_cta',
        'review_methodology', 'risk_disclaimer',
        'editorial_notes', 'status',
    ];

    protected $casts = [
        'secondary_keywords'              => 'array',
        'specs_table'                     => 'array',
        'set_comparison_table'            => 'array',
        'parameter_classification_summary' => 'array',
        'important_parameters'            => 'array',
        'requirements_table'              => 'array',
        'load_set_steps'                  => 'array',
        'customization_guidance'          => 'array',
        'customization_cautions'          => 'array',
        'who_should_use'                  => 'array',
    ];

    public function ea(): BelongsTo
    {
        return $this->belongsTo(Ea::class);
    }

    public function setFiles(): HasMany
    {
        return $this->hasMany(SetFile::class)->orderBy('sort_order');
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(SetReviewFaq::class)->orderBy('sort_order');
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable')->orderBy('sort_order');
    }

    public function internalLinks(): MorphMany
    {
        return $this->morphMany(InternalLink::class, 'linkable')->orderBy('sort_order');
    }
}