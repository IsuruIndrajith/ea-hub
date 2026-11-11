<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SetParameter extends Model
{
    use HasFactory;

    protected $fillable = [
        'set_file_id', 'name', 'value', 'group', 'classification',
        'explanation', 'practical_importance', 'risk_impact',
        'interacts_with', 'modifiable', 'sort_order',
    ];

    protected $casts = [
        'interacts_with' => 'array',
    ];

    public function setFile(): BelongsTo
    {
        return $this->belongsTo(SetFile::class);
    }
}