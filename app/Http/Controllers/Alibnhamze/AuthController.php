<?php

namespace App\Http\Controllers\Alibnhamze;

use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function login(){
        return view('alibnhamze.auth.login');
    }
}
