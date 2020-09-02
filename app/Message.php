<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['user_id', 'sender_id', 'receiver_id', 'message'];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function getMessageChatRoom($lastIdHistory) {
        $take = 20;
        $model = Message::with('user')->orderBy('id', 'desc');
        if ($lastIdHistory > 0) {
            $model->where('id', '<', $lastIdHistory);
        }
        return $model->take($take)->get();
    }
}
