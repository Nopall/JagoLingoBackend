<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    use HasFactory;
    protected $table = "service_details";

    protected $fillable = ['history_id', 'service_type_id', 'location_id', 'payment_method_id', 'note'];

    protected $hidden = ['created_at', 'updated_at'];

    public function history(): BelongsTo
    {
        return $this->belongsTo(History::class);
    }

    public function serviceType(): BelongsTo
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
