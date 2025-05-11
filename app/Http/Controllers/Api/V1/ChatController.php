<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\NewChatMessage;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ChatMessageCollection;
use App\Http\Resources\V1\ChatMessageResource;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new ChatMessageCollection(ChatMessage::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ChatMessage $chatMessage)
    {
        return new ChatMessageResource($chatMessage);
    }
    public function sendMessage(Request $request){
        $user = $request->user();
        $sesion_id=$request['sessionId'];
        $session=ChatSession::where("id",$sesion_id)->first();
        if ($user->id !== $session->user_id && $user->id !== $session->doctor_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $validated = $request->validate([
            'message' => ['required', 'string']
        ]);
        $message = $session->messages()->create([
            'sender_id' => $user->id,
            'sender_type' => get_class($user),
            'message' => $validated['message']
        ]);    
        broadcast(new NewChatMessage($message))->toOthers();
        //return response()->json(['message' => 'Message sent', 'data' => $message], 200);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChatMessage $chatMessage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChatMessage $chatMessage)
    {
        //
    }
}
