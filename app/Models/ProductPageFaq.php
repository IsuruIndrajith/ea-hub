<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductPageFaq extends Model
{
    use HasFactory;

    protected $fillable = ['product_page_id', 'question', 'answer', 'sort_order'];

    public function productPage(): BelongsTo
    {
        return $this->belongsTo(ProductPage::class);
    }
}