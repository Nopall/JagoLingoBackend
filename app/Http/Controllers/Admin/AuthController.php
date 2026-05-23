<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function loginView()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials, $request->remember)) {
            return response()->json([
                'status'  => true,
                'msg' => 'Login Success.'
            ]);
        } else {
            return response()->json([
                'status'  => false,
                'msg' => 'Email or password not match.'
            ], 400);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/'); // Redirect ke halaman utama setelah logout
    }
}
