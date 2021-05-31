<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatSing extends Model
{
    protected $fillable = ['main_user', 'secondary_user', 'messages', 'tries'];
}
