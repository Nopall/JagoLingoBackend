<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    protected $table = "chat_room";

    protected $fillable = [
        'user_id', 'admin_id'
    ];

    // Relasi dengan user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi dengan admin
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Relasi dengan detail obrolan
    public function chatDetails()
    {
        return $this->hasMany(ChatDetail::class);
    }
    
    public function getLastMessage()
    {
        // Mengambil detail percakapan terakhir dalam ruangan chat
        $lastMessage = $this->chatDetails()->orderBy('created_at', 'desc')->first();

        // Mengembalikan isi dari percakapan terakhir
        return $lastMessage ? $lastMessage->message : null;
    }
    
    public function getLastMessageDate()
    {
        // Mengambil detail percakapan terakhir dalam ruangan chat
        $lastMessage = $this->chatDetails()->orderBy('created_at', 'desc')->first();

        // Mengembalikan isi dari percakapan terakhir
        return $lastMessage ? $lastMessage->created_at : null;
    }
    
    public function getLastTypeMessage()
    {
        // Mengambil detail percakapan terakhir dalam ruangan chat
        $lastMessage = $this->chatDetails()->orderBy('created_at', 'desc')->first();

        // Mengembalikan isi dari percakapan terakhir
        return $lastMessage ? $lastMessage->type : null;
    }
    
    public function getLastSender()
    {
        // Mengambil detail percakapan terakhir dalam ruangan chat
        $lastMessage = $this->chatDetails()->orderBy('created_at', 'desc')->first();

        // Mengembalikan isi dari percakapan terakhir
        return $lastMessage ? $lastMessage->sender->name : null;
    }
    
    public function getOpposite()
    {
        // Mengambil detail percakapan terakhir dalam ruangan chat
        $lastMessage = $this->admin();

        // Mengembalikan isi dari percakapan terakhir
        return $lastMessage ? $lastMessage : null;
    }
}
