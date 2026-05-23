<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;
    protected $table = "products";

    protected $fillable = ['name', 'description', 'price', 'weight', 'uom', 'contact', 'commodity_id'];

    protected $hidden = ['created_at', 'updated_at'];

    public function commodity(): BelongsTo
    {
        return $this->belongsTo(Commodity::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

}
