<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function sendMessage(Request $request){
        $message = Message::create([
            "user_id"=>Auth::id(),
            "message"=>$request->message,
        ]);
        broadcast(new MessageSent($message))->toOthers();
        return response()->json(['success' => true]);

    }
  
}
