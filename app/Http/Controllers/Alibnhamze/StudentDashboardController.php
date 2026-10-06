<?php

namespace App\Http\Controllers\Alibnhamze;

use App\Http\Controllers\Controller;

class StudentDashboardController extends Controller
{
    public function index()
    {
        return view('alibnhamze.student.dashboard');
    }
}
