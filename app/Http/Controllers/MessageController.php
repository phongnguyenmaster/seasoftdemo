<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use Validator;
use Auth;
use App\User;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    function newMessages(Request $request)
    {
        $dataResult = array('status' => 1, 'message' => '');
        $validator = Validator::make($request->all(), [
            'message' => 'required',
        ]);
        if ($validator->fails()) {
            $dataResult['status'] = 0;
            $dataResult['message'] = __('validation.invalid_data');
            return $dataResult;
        }
        $user = Auth::user();
        $message = new Message();
        $message->message = request()->get('message', '');
        $message->private_key = request()->get('privateKey', 0);;
        $message->user_id = $user->id;
        $message->save();
        return ['message' => $message->load('user')];
    }
    function getMessageContent(Request $request)
    {
        $dataResult = array('status' => 1, 'message' => '');
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return abort(404);
        }
        $id = $request->get('id');
        $message = Message::with('user')->where('id', $id)->where('private_key', 0)->first();
        if ($message) {
            return $message;
        } else {
            return abort(404);
        }
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
        if ($validator->fails()) {
            $dataResult['status'] = 0;
            $dataResult['message'] = __('validation.invalid_data');;
            return $dataResult;
        }
        // Check user is exist
        $user = User::where('id', $receiver_id)->first();
        if (!$user) {
            $dataResult['status'] = 0;
            $dataResult['message'] = __('validation.invalid_data');
            return $dataResult;
        }
        $message = new Message();
        $private_key = $message->createChatPrivateKey($receiver_id);
        $dataResult['private_key'] = $private_key;
        return $dataResult;
    }
}
