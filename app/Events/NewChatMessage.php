<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewChatMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    /**
     * Create a new event instance.
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return new Channel('chat.' . $this->message->chat_session_id);
    }
    public function broadcastWith()
    {
        return [
            'message' => $this->message->message,
            'sender_id' => $this->message->sender_id,
            'sender_type'=>$this->message->sender_type,
            'timestamp' => $this->message->created_at->toDateTimeString()
        ];
    }
    public function broadcastAs()
    {
      return 'message';
    }

}
