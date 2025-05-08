<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatMessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->id,
            "chatSessionId"=>$this->chat_session_id,
            "senderType"=>$this->sender_type,
            "senderId"=>$this->sender_id,
            "message"=>$this->message,
            "createdAt"=>$this->created_at
        ];
    }
}
