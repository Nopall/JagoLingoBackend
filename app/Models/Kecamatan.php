<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kecamatan extends Model
{
    use HasFactory;
    protected $table = "subdistrict";

    protected $fillable = ['name'];

    protected $hidden = ['created_at', 'updated_at'];
}
