<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Car extends Model
{
    use HasFactory;
    protected $table = "cars";

    protected $fillable = [
        'user_id', 'car_name', 'car_brand_id', 'car_model', 'police_number', 'police_number_year',
        'tank_type', 'fuel_type_id', 'capacity','fuel_type_id_secondary', 'capacity_secondary',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function carBrand(): BelongsTo
    {
        return $this->belongsTo(CarBrand::class);
    }
    
    public function fuelType(): BelongsTo
    {
        return $this->belongsTo(FuelType::class, 'fuel_type_id', 'id');
    }

    public function fuelTypeSecondary(): BelongsTo
    {
        return $this->belongsTo(FuelType::class, 'fuel_type_id_secondary', 'id');
    }


}
