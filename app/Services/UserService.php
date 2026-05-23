<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UserService
{
    public function deleteUserById(string $id): void
    {
        DB::transaction(function () use ($id) {
            $user = User::where('id', $id)->first();
            $user->delete();
        });
    }
    
    public function updateUserPassword($id, $password)
    {
        $setting = User::findOrFail($id);
        $setting->password = Hash::make($password);

        $setting->save();

        return $setting;
    }
}
