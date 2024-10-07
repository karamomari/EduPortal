<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'message' => 'required',
            'receiver_id' => 'required|integer|exists:users,id',
            'sender_id' => 'required|integer|exists:users,id',
        ]);
    
        Message::create([
            'message' => $validatedData['message'],
            'receiver_id' => $validatedData['receiver_id'],
            'sender_id' => $validatedData['sender_id']
        ]);
    
        return response()->json(['success' => true, 'message' => 'Message stored successfully!']);
    }
    
    public function show($senderId, $receiverId)
    {
        $messages = Message::where(function ($query) use ($senderId, $receiverId) {
            $query->where('sender_id', $senderId)
                  ->where('receiver_id', $receiverId);
        })->orWhere(function ($query) use ($senderId, $receiverId) {
            $query->where('sender_id', $receiverId)
                  ->where('receiver_id', $senderId);
        })->orderBy('created_at', 'asc')->get();
    
        return response()->json($messages);
    }


}
