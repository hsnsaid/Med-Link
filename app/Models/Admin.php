<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Model
{
    /** @use HasFactory<\Database\Factories\AdminFactory> */
    use HasFactory,HasApiTokens;
    protected $guarded=[];
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
