<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    // Nama tabel jika berbeda dari konvensi Laravel
    protected $table = 'settings';
    
    // Field yang dapat diisi
    protected $fillable = [
        'teks', 
        'content'
    ];
    
    // Format timestamps secara otomatis
    public $timestamps = true;
    
    // Jika ingin menggunakan nama kolom yang berbeda untuk timestamps:
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
