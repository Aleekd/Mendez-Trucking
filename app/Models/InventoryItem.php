<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryItem extends Model
{
    protected $fillable = [
        'nombre',
        'tipo',
        'stock_actual',
        'description',
    ];

    //Relación con los préstamos (solo para tipo 'herramienta'
    public function loans(): HasMany{
        return $this->hasMany(ToollLoan::class, 'item_id');
    }

    //Relación con los logs de consumo (refacciones, limpieza, papeleria)
    public function consumptionLogs(): HasMany
    {
        return $this->hasMany(ConsumptionLog::class, 'item_id');
    }
}
