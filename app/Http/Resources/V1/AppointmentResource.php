<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'doctorId'=>$this->doctor_id,
            'userId'=>$this->user_id,
            'date'=>$this->date,
            'time'=>$this->time,
        ];
    }
}
