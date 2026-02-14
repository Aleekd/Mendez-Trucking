<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsumptionLog extends Model
{
    protected $fillable = [
        'item_id',
        'user_id',
        'cantidad',
        'fecha_consumo',
    ];
    
    protected $casts = [
        'fecha_consumo' => 'datetime',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class, 'item_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
