<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatDetail extends Model
{
    protected $table = "chat_detail";

    protected $fillable = [
        'chat_room_id', 'sender_id', 'message', 'type'
    ];

    // Relasi dengan ruang obrolan
    public function chatRoom()
    {
        return $this->belongsTo(ChatRoom::class);
    }

    // Relasi dengan pengirim pesan
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
