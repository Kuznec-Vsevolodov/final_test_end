<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
class TopListController extends Controller
{
    public function getList(){
        return User::all()->toArray();
    }
}
