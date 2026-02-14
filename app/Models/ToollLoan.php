<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ToollLoan extends Model
{
    protected $fillable = [
        'borrower_id',
        'admin_id',
        'item_id',
        'cantidad',
        'fecha_salida',
        'fecha_devolucion',
        'estado',
    ];

    protected $casts = [
        'fecha_salida' => 'datetime',
        'fecha_devolucion' => 'datetime',
    ];

    //quien pidio la herramienta?
    public function borrower(): BelongsTo
    {
        return $this->belongsTo(User::class, 'borrower_id');    
    }

    //¿Qué administador autorizó?
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    //¿Qué herramienta es?
    public function item(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class, 'item_id');
    }

}

