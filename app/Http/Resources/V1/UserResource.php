<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'password'=>$this->password,
            'phoneNumber'=>$this->phone_number,
            "age"=> $this->age,
            "sexe"=>$this->sexe,
            "chronicDisease"=>$this->chronic_disease,
            "groupage"=>$this->groupage,
            "balance"=>$this->balance
        ];
    }
}
