<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommodityItem extends Model
{
    use HasFactory;
    protected $table = "commodity_item";

    protected $fillable = ['name', 'image'];

    protected $hidden = ['created_at', 'updated_at'];
}
