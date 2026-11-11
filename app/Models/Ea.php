<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Ea extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, LogsActivity, SoftDeletes;

    protected $table = 'eas';

    protected $fillable = [
        'name', 'version', 'slug', 'platform', 'vendor_link', 'vendor_url', 'product_page_url',
        'supported_pairs', 'recommended_timeframe', 'strategy_type',
        'account_type', 'min_deposit', 'recommended_deposit',
        'recommended_leverage', 'vps_requirement', 'developer_name',
        'developer_background', 'myfxbook_link', 'myfxbook_gain', 'product_type',
        'primary_keywords', 'secondary_keywords', 'created_by', 'updated_by',
    ];

    protected $casts = [
        'supported_pairs' => 'array',
        'primary_keywords' => 'array',
        'secondary_keywords' => 'array',
    ];

    public function productPage(): HasOne
    {
        return $this->hasOne(ProductPage::class);
    }

    public function setReview(): HasOne
    {
        return $this->hasOne(SetReview::class);
    }

    public function generatedContents(): HasMany
    {
        return $this->hasMany(GeneratedContent::class);
    }

    public function generatedProductPage(): HasOne
    {
        return $this->hasOne(GeneratedContent::class)
            ->where('type', GeneratedContent::TYPE_PRODUCT_PAGE);
    }

    public function generatedSetReview(): HasOne
    {
        return $this->hasOne(GeneratedContent::class)
            ->where('type', GeneratedContent::TYPE_SET_REVIEW);
    }

    public function signals(): HasMany
    {
        return $this->hasMany(EaSignal::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function setVendorUrlAttribute(?string $value): void
    {
        $this->attributes['vendor_url'] = $value;
        $this->attributes['vendor_link'] = $value;
    }

    public function setVendorLinkAttribute(?string $value): void
    {
        $this->attributes['vendor_link'] = $value;
        $this->attributes['vendor_url'] = $value;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('live_signal_images')->useDisk('public');
        $this->addMediaCollection('myfxbook_review_images')->useDisk('public');
    }

    protected static function booted(): void
    {
        static::saving(function (Ea $ea): void {
            $base = Str::slug(trim($ea->name.' '.$ea->version));
            $base = $base !== '' ? $base : 'ea';
            $slug = $base;
            $suffix = 2;

            while (static::withTrashed()->where('slug', $slug)->when($ea->exists, fn ($query) => $query->whereKeyNot($ea->getKey()))->exists()) {
                $slug = $base.'-'.$suffix++;
            }

            $ea->slug = $slug;

            if (! $ea->exists && auth()->check()) {
                $ea->created_by = auth()->id();
            }

            if (auth()->check()) {
                $ea->updated_by = auth()->id();
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'version', 'vendor_url', 'primary_keywords', 'secondary_keywords'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges();
    }
}
