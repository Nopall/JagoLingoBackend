<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommodityPrice extends Model
{
    use HasFactory;
    protected $table = "commodity_price";

    protected $fillable = ['commodity_item_id','farmer_price', 'seller_price', 'date_input'];

    protected $hidden = ['created_at', 'updated_at'];

    public function commodityItem(): BelongsTo
    {
        return $this->belongsTo(CommodityItem::class);
    }
}
