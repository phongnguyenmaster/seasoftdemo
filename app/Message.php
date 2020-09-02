<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Message extends Model
{
    protected $fillable = ['user_id', 'private_key', 'message'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function loadMessageRoom($lastIdHistory)
    {
        $take = 20;
        $model = Message::with('user')->orderBy('id', 'desc');
        $model->where('private_key', 0);
        if ($lastIdHistory > 0) {
            $model->where('id', '<', $lastIdHistory);
        }
        return $model->take($take)->get();
    }
        public function loadMessagePrivate($lastIdHistory, $privateKey)
    {
        $take = 20;
        $model = Message::with('user')->orderBy('id', 'desc');
        $model->where('private_key', $privateKey);
        if ($lastIdHistory > 0) {
            $model->where('id', '<', $lastIdHistory);
        }
        return $model->take($take)->get();
    }
    public function createChatPrivateKey($receiver_id)
    {
        $user_id = Auth::user()->id;
        // Create simple key for private chat. Tạo simple hoy chứ run thật thì tạo key phực tạp hơn.
        $private_key = '';
        if ($user_id > $receiver_id) {
            $private_key = $receiver_id . $user_id;
        } else {
            $private_key =  $user_id . $receiver_id;
        }
        return $private_key;
    }
}
