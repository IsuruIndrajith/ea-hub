<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EaSignal extends Model
{
    use HasFactory;

    public const TYPE_MQL5 = 'mql5';

    public const TYPE_MYFXBOOK = 'myfxbook';

    protected $fillable = ['type', 'url', 'notes'];

    public function ea(): BelongsTo
    {
        return $this->belongsTo(Ea::class);
    }
}
