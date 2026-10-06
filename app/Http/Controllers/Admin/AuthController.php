<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function create()
    {
        return view('admin.auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'national_code' => ['required'],
            'password' => ['required'],
        ]);

        $admin = User::query()
            ->where('national_code', $credentials['national_code'])
            ->where('is_admin', true)
            ->first();

        if (
            !$admin ||
            !$admin->password ||
            !Hash::check($credentials['password'], $admin->password)
        ) {
            return back()
                ->withErrors([
                    'national_code' => 'کد ملی یا رمز عبور اشتباه است.',
                ])
                ->withInput($request->only('national_code'));
        }

        Auth::guard('admin')->login(
            $admin,
            $request->boolean('remember')
        );

        $request->session()->regenerate();

        return redirect()->route('admin.dashboard');
    }
    public function logout(Request $request){
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
