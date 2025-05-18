<?php

namespace App\Events;

use App\Models\ChatSession;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatSessionStarted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $user,$session;
    /**
     * Create a new event instance.
     */
    public function __construct(User $user, ChatSession $session)
    {
        $this->user=$user;
        $this->session=$session;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     */
    public function broadcastOn()
    {
        return new Channel("doctor.{$this->session->doctor_id}");
    }
    public function broadcastWith()
    {
        return [
            'session' => $this->session,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ]
        ];
    }
    public function broadcastAs()
    {
        return 'ChatSessionStarted';
    }
}
