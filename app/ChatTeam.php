<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatTeam extends Model
{
    protected $fillable = ['main_user', 'secondary_users', 'messages', 'tries', 'round', 'price'];
}
