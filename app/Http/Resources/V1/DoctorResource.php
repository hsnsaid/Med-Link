<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
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
            'name'=>$this->name,
            'email'=>$this->email,
            'gender'=>$this->gender,
            'password'=>$this->password,
            'phoneNumber'=>$this->phone_number,
            'speciality'=>$this->speciality,
            'typeConsultation'=>$this->type_consultation,
            'city'=>$this->city,
            'street'=>$this->street,
            'rating'=>$this->rating,
            'approved'=>$this->approved,            
            'picture'=>$this->picture,
            'status'=>$this->status
        ];
    }
}
