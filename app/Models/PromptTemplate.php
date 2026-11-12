<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PromptTemplate extends Model
{
    use HasFactory;

    public const TYPE_PRODUCT_PAGE = 'product_page';
    public const TYPE_SET_REVIEW   = 'set_review';

    protected $fillable = ['type', 'name', 'body', 'version', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /** Latest active template for a given type. Pass $name to narrow to a specific template name. */
    public static function activeFor(string $type, ?string $name = null): ?self
    {
        return static::query()
            ->where('type', $type)
            ->when($name, fn ($q) => $q->where('name', $name))
            ->where('is_active', true)
            ->latest('id')
            ->first();
    }

    /** Activate this version and deactivate every other version with the same type + name. */
    public function activate(): void
    {
        DB::transaction(function () {
            static::query()
                ->where('type', $this->type)
                ->where('name', $this->name)
                ->where('id', '!=', $this->id)
                ->update(['is_active' => false]);

            $this->update(['is_active' => true]);
        });
    }
}