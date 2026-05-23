<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Subscription extends Model
{
    use HasFactory;

    protected $table = 'subscriptions';

    protected $fillable = [
        'user_id',
        'package_id',
        'payment_id',
        'is_active',
        'status',
        'price',
        'purchase_date',
        'expiry_date'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'purchase_date' => 'datetime',
        'expiry_date' => 'datetime',
        'price' => 'decimal:2'
    ];

    // Relasi ke user dengan pengecekan soft delete
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Deleted User',
            'email' => 'deleted@example.com'
        ]);
    }
    
    public function package()
    {
        return $this->belongsTo(Package::class)->withDefault([
            'name' => 'Package Not Found'
        ]);
    }
    
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    // Method untuk cek apakah langganan aktif
    public function isActive()
    {
        return $this->is_active && $this->status === 'active';
    }

    // Method untuk aktivasi subscription
    public function activate()
    {
        $this->update([
            'is_active' => true,
            'status' => 'active'
        ]);

        // Update user is_premium jika perlu
        if ($this->user) {
            $this->user->update(['is_premium' => 1]);
        }
    }

    // Method untuk deaktivasi subscription
    public function deactivate()
    {
        $this->update([
            'is_active' => false,
            'status' => 'cancelled'
        ]);

        // Update user is_premium jika perlu
        if ($this->user) {
            $this->user->update(['is_premium' => 0]);
        }
    }

    // Scope untuk filter subscription yang valid (user tidak dihapus)
    public function scopeWithValidUser($query)
    {
        return $query->whereHas('user', function($q) {
            $q->where('is_deleted', 0);
        });
    }

    // Scope untuk subscription yang sudah dibayar
    public function scopePaid($query)
    {
        return $query->whereHas('payment', function($q) {
            $q->where('status', 1);
        });
    }
}