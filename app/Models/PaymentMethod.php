<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentMethod extends Model
{
    use HasFactory;
    protected $table = "payment_methods";

    protected $fillable = ['name', 'user_id'];

    protected $hidden = ['created_at', 'updated_at'];
}
