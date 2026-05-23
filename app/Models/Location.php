<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Location extends Model
{
    use HasFactory;
    protected $table = "locations";

    protected $fillable = ['name', 'address', 'latitude', 'longitude', 'user_id'];

    protected $hidden = ['created_at', 'updated_at'];
}
