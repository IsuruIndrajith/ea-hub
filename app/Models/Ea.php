<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ea extends Model
{
    use HasFactory;

    protected $table = 'eas';

    protected $fillable = [
        'name', 'version', 'platform', 'vendor_link', 'product_page_url',
        'supported_pairs', 'recommended_timeframe', 'strategy_type',
        'account_type', 'min_deposit', 'recommended_deposit',
        'recommended_leverage', 'vps_requirement', 'developer_name',
        'developer_background', 'myfxbook_link', 'myfxbook_gain', 'product_type',
    ];

    protected $casts = [
        'supported_pairs' => 'array',
    ];

    public function productPage(): HasOne
    {
        return $this->hasOne(ProductPage::class);
    }

    public function setReview(): HasOne
    {
        return $this->hasOne(SetReview::class);
    }
}