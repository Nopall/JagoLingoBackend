<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarBrand extends Model
{
    use HasFactory;
    protected $table = "car_brands";

    protected $fillable = ['name', 'logo'];

    protected $hidden = ['created_at', 'updated_at'];
}
