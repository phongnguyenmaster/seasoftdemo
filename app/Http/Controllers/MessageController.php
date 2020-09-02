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
        return $message->loadMessageRoom($lastIdHistory);
    }
    function loadMessagePrivate(Request $request)
    {
        $privateKey =  $request->get('privateKey');
        $lastIdHistory = $request->get('lastIdHistory');
        $message = new Message();
        return $message->loadMessagePrivate($lastIdHistory, $privateKey);
    }
    function getPrivateKey($receiver_id)
    {
        $dataResult = array('status' => 1, 'message' => '');
        $validator = Validator::make(array('receiver_id' => $receiver_id), [
            'receiver_id' => 'required|numeric',
        ]);
        // Check user is exist
        // TO DO LOGIC HERE (Nếu kịp thời gian thì viết)
        if ($validator->fails()) {
            $dataResult['status'] = 0;
            $dataResult['message'] = 'Invalid data';
            return $dataResult;
        }
        $message = new Message();
        $private_key = $message->createChatPrivateKey($receiver_id);
        $dataResult['private_key'] = $private_key;
        return $dataResult;
    }
}
