<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\ChatSessionEnded;
use App\Http\Controllers\Controller;
use App\Models\ChatSession;
use Illuminate\Http\Request;

class ChatSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(ChatSession $chatSession)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChatSession $chatSession)
    {
        //
    }
    public function end(Request $request,ChatSession $session){
        $user=$request->user();
        if($user->id !== $session->doctor_id){
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        if($session->ended_at){
            return response()->json(['message' => 'Chat already ended'], 400);
        }
        $session->ended_at=now();
        $session->save();
        $user->status="offline";
        $user->save();
        broadcast(new ChatSessionEnded($session))->toOthers();
        return response()->json(['message' => 'Chat ended successfully.']);    
    }
    public function review(Request $request,ChatSession $session){
        $user=$request->user();
        if($user->id !== $session->user_id){
            return response()->json(['message' => 'Unauthorized'], 403);
        };
        $validated = $request->validate([
            'rating' => ['required'],
            'type' => ['required','in:saved,discard'],
        ]);
        $doctor=$session->doctor()->first();
        $count=ChatSession::where('doctor_id',$doctor->id)->count();
        $doctor->rating=($doctor->rating*($count-1)+$request['rating'])/$count;
        $doctor->save();
        if($validated['type']=="discard"){
            $session->delete();
            return response()->json(['message' => 'Chat session discarded']);
        }else{
            return response()->json(['message' => 'Chat session is saved']);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChatSession $chatSession)
    {
        //
    }
}
