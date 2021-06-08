<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use \App\Models\ShippingInfo;
use \App\AlertHelper;

Route::post('/send-message', function(Request $request) {
    $to_user_id = $request['to_user_id'];
    $message = $request['message'];

    $message_obj = \App\Models\Message::create([
        'from_user_id' => auth()->user()->id,
        'to_user_id' => $to_user_id,
        'message' => $message
    ]);

    event(new \App\Events\NewMessage($message_obj));
})->name('send-message');

Route::post('/get-conversation', function(Request $request) {
    $other_user_id = $request['other_user_id'];
    $messages = [];

    foreach(\App\Models\Message::where('from_user_id', auth()->user()->id)->where('to_user_id', $other_user_id)->get() as $message) {
        $message_data = [
            'user_id' => $message->from_user_id,
            'message' => $message->message,
            'created_at' => $message->created_at
        ];

        if(! $message->read) {
            $message->read = true;
            $message->save();
        }

        array_push($messages, $message_data);
    }

    foreach(\App\Models\Message::where('to_user_id', auth()->user()->id)->where('from_user_id', $other_user_id)->get() as $message) {
        $message_data = [
            'user_id' => $message->from_user_id,
            'message' => $message->message,
            'created_at' => $message->created_at
        ];

        if(! $message->read) {
            $message->read = true;
            $message->save();
        }

        array_push($messages, $message_data);
    }

    array_sort_by_column($messages, 'created_at');

    $unread_messages = \App\Models\Message::where('to_user_id', auth()->user()->id)->where('read', false)->count();

    return response()->json(['unread_messages' => $unread_messages, 'other_user_username' => \App\Models\User::where('id', $other_user_id)->first()->username, 'messages' => $messages]);
})->name('get-conversation');

Route::post('/mark-message-read/{message_id}', function($message_id) {
    if(\App\Models\Message::where('id', $message_id)->where('to_user_id', auth()->user()->id)->exists()) {
        $message = \App\Models\Message::where('id', $message_id)->where('to_user_id', auth()->user()->id)->first();
        $message->read = true;
        $message->save();
    }
})->name('mark-message-read');

Route::post('/get-conversations', function() {
    $conversations = [];
    foreach(\App\Models\Message::where('from_user_id', auth()->user()->id)->orWhere('to_user_id', auth()->user()->id)->orderBy('created_at', 'ASC')->get() as $message) {
        $other_user = $message->from_user_id == auth()->user()->id ? \App\Models\User::where('id', $message->to_user_id)->first() : \App\Models\User::where('id', $message->from_user_id)->first();

        if(! in_array($other_user->id, $conversations)) {
            $conversations[$other_user->id] = [
                'avatar' => $other_user->getAvatar(),
                'username' => $other_user->username,
                'message' => $message->message
            ];
        }
    }

    return response()->json($conversations);
})->name('get-conversations');

function array_sort_by_column(&$array, $column, $direction = SORT_ASC) {
    $reference_array = array();

    foreach($array as $key => $row) {
        $reference_array[$key] = $row[$column];
    }

    array_multisort($reference_array, $direction, $array);
}