<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Budidaya extends Model
{
    use HasFactory;
    protected $table = "budidaya_horti";

    protected $fillable = ['name', 'description', 'pdf', 'image'];

    protected $hidden = ['created_at', 'updated_at'];
}
