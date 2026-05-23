<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Charging extends Model
{
    use HasFactory;
    protected $table = "charging_details";

    protected $fillable = ['history_id', 'fuel_id', 'charging_place_id', 'liter', 'price', 'note'];

    protected $hidden = ['created_at', 'updated_at'];

    public function history(): BelongsTo
    {
        return $this->belongsTo(History::class);
    }

    public function fuel(): BelongsTo
    {
        return $this->belongsTo(Fuel::class);
    }

    public function chargingPlace(): BelongsTo
    {
        return $this->belongsTo(ChargingPlace::class);
    }
}
