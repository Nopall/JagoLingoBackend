<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pestisida extends Model
{
    use HasFactory;
    protected $table = "pestisida";

    protected $fillable = ['name', 'photo', 'location', 'contact_no', 'seller'];

    protected $hidden = ['created_at', 'updated_at'];
}
