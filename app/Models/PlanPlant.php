<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;
    protected $table = "subscriptions";

    protected $fillable = ['user_id', 'payment_id', 'is_active'];

    protected $hidden = ['created_at', 'updated_at'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}
