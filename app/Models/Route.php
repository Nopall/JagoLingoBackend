<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Route extends Model
{
    use HasFactory;
    protected $table = "route_details";

    protected $fillable = ['history_id', 'first_location_id', 'last_location_id', 'first_date', 'last_date',
    'first_odometer', 'last_odometer', 'note'];

    protected $hidden = ['created_at', 'updated_at'];

    public function history(): BelongsTo
    {
        return $this->belongsTo(History::class);
    }

    public function firstLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function lastLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
