<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class ChatMessage extends Model
{
    use HasApiTokens;
    protected $guarded=[];
    public function session(){
        return $this->belongsTo(ChatSession::class);
    }
    public function sender(){
        return $this->morphTo();
    }

}
