<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurfaceArea extends Model
{
    use HasFactory;
    protected $table = "surface_area";

    protected $fillable = ['commodity_item_id', 'subdistrict_id', 'plant', 'harvest', 'production', 'productivity'];

    protected $hidden = ['created_at', 'updated_at'];

    public function subdistrict(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class);
    }
    
    public function commodityItem(): BelongsTo
    {
        return $this->belongsTo(CommodityItem::class);
    }
}
