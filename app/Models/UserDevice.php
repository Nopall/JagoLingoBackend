<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'device_id',
        'device_type',
        'device_brand',
        'device_modal',
        'os_version',
        'device_build_number',
    ];

    /**
     * Get the user that owns the device.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
