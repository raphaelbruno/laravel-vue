<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    function show($session)
    {
        $messages = Message::with('user')
            ->where('session', $session)
            ->orderBy('created_at')
            ->get();
        return response()->json($messages);
    }

    function send($session, Request $request)
    {
        if(!isset($session) || !isset($request->content) || empty($request->content)) return null;

        return response()->json(Message::create([
            'session' => $session,
            'content' => trim(htmlentities($request->content)),
            'user_id' => Auth::user()->id,
        ]));
    }
}