<?php

namespace App\Http\Controllers\Alibnhamze;

use App\Http\Controllers\Controller;

class ParentDashboardController extends Controller
{
    public function index(){
        return view('alibnhamze.parent.dashboard');
    }
}
