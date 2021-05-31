<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QueueSing extends Model
{
    protected $fillable = ['user_1', 'user_2', 'price'];
}
