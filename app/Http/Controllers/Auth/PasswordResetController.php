<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $newPassword = Str::random(8);

        $user->password = Hash::make($newPassword);
        $user->save();

        // Kirim email dengan Mailable
        Mail::to($user->email)->send(new ResetPasswordMail($newPassword));

        return response()->json(['message' => 'Password baru telah dikirim ke email Anda']);
    }
}

