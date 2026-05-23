<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'package_id',
        'amount',
        'status', // 1 = paid/subscribed, 0 = pending, 2 = failed
        'transaction_id',
        'payment_date',
        'expiry_date', // Tambahkan expiry_date langsung di payment
        'payment_method',
        'currency',
        'gateway_response'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
        'expiry_date' => 'datetime',
        'status' => 'integer',
        'gateway_response' => 'array'
    ];

    // Status constants
    const STATUS_PAID = 1;
    const STATUS_PENDING = 0;
    const STATUS_FAILED = -2;
    const STATUS_EXPIRED = 3;

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke package
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    // Scope untuk payment yang sudah dibayar (subscribe)
    public function scopeSubscribed($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    // Scope untuk payment aktif (belum expired)
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_PAID)
                    ->where(function($q) {
                        $q->where('expiry_date', '>', now())
                          ->orWhereNull('expiry_date');
                    });
    }

    // Method untuk mengecek apakah sudah subscribe
    public function isSubscribed()
    {
        return $this->status === self::STATUS_PAID;
    }

    // Method untuk mengecek apakah masih aktif (belum expired)
    public function isActive()
    {
        return $this->isSubscribed() && 
               ($this->expiry_date === null || $this->expiry_date->gt(now()));
    }

    // Method untuk mengaktifkan payment (mark as paid)
    public function markAsPaid()
    {
        $this->update([
            'status' => self::STATUS_PAID,
            'payment_date' => now()
        ]);
    }

    // Method untuk menonaktifkan payment (cancel subscription)
    public function cancel()
    {
        $this->update([
            'status' => self::STATUS_FAILED
        ]);
    }

    // Method untuk menandai sebagai expired
    public function markAsExpired()
    {
        $this->update([
            'status' => self::STATUS_EXPIRED
        ]);
    }
}