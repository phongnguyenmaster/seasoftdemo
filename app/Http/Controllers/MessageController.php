<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use Validator;
class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function getMessageChatRoom($lastIdHistory)
    {
        $validator = Validator::make(array('lastIdHistory' => $lastIdHistory), [
            'lastIdHistory' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return abort(404);
        }
        $message = new Message();
        return $message->getMessageChatRoom($lastIdHistory);
    }
        function getMessageChatPrivate($start)
    {
        return Message::with('user')->get();
    }
}
