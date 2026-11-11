<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ProductPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'ea_id', 'input_keywords', 'input_contents',
        'reviewed_by_author_name', 'reviewed_by_author_profile_link',
        'focus_keyword', 'secondary_keywords',
        'meta_title_1', 'meta_title_2', 'meta_title_3',
        'meta_description', 'short_description', 'rank_math_focus_keyword',
        'intro', 'first_hand_test_results',
        'key_features', 'why_buy_this', 'honest_limitation', 'why_different',
        'strategy_logic', 'risk_controls', 'risk_disclaimer_line',
        'optimized_settings_table', 'install_steps', 'author_info',
        'is_it_worth_it', 'telegram_support_line', 'reviewed_by_closer',
        'related_categories_paragraph',
        'csv_name', 'csv_published', 'csv_short_description',
        'csv_description', 'csv_meta_tg_message', 'csv_meta_faq',
        'status',
    ];

    protected $casts = [
        'input_keywords'            => 'array',
        'secondary_keywords'        => 'array',
        'key_features'              => 'array',
        'why_buy_this'              => 'array',
        'strategy_logic'            => 'array',
        'risk_controls'             => 'array',
        'optimized_settings_table'  => 'array',
        'install_steps'             => 'array',
    ];

    public function ea(): BelongsTo
    {
        return $this->belongsTo(Ea::class);
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(ProductPageFaq::class)->orderBy('sort_order');
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
