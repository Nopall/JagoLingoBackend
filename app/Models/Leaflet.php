<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Leaflet extends Model
{
    use HasFactory;
    protected $table = "leaflet";

    protected $fillable = ['name', 'description', 'pdf', 'image'];

    protected $hidden = ['created_at', 'updated_at'];
}
