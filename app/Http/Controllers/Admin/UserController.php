<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\UserDataTable;
use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Models\User;
use Illuminate\Http\Request;


class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('user.list');
    }
    
    public function formEditUser(String $id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('user.index')->with('error', 'User not found.');
        }
        
        return view('user.form', compact('user'));
    }
    
    public function updateUser(Request $request, $id)
    {
        $data = $request->all();
        
        $setting = $this->userService->updateUserPassword($id, $data['password']);

        return response()->json([
            'status' => true,
            'message' => 'User updated successfully.',
            'data' => $setting,
        ]);
    }

    public function deleteUserById(String $id)
    {
        $this->userService->deleteUserById($id);

        return response()->json([
            'status' => true,
            'message' => 'User deleted successfully.',
        ]);
    }
}
