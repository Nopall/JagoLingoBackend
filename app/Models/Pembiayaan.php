<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembiayaan extends Model
{
    use HasFactory;
    protected $table = "pembiayaan";

    protected $fillable = ['name', 'image', 'cp_1', 'cp_2', 'wa_1', 'wa_2'];

    protected $hidden = ['created_at', 'updated_at'];
}
