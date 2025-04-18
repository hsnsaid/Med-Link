<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Doctor extends Model
{
    /** @use HasFactory<\Database\Factories\DoctorFactory> */
    use HasFactory,HasApiTokens;
    protected $guarded = [
        'approved',
        'rating'
    ];
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
    public function appointments(){
        return $this->hasMany(Appointment::class);
    }
}
