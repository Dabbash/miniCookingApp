<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function updatePassword(Request $request) {

        $user = auth()->user()->password;

        if(!Hash::check($request->password, $user)) {
            return response()->json(['message' => 'Your current password is incorrect'], 401);
        }

        $validatedData = $request->validate([
            'password' => 'required',
            'new_password' => 'required|confirmed',
            'new_password_confirmation' => 'required'
        ]);

        $user->password = bcrypt($validatedData['password']);

        if($user->save()){
            return ['message' => 'password updarted successfully'];
        } else {
            return response()->json(['message' => 'Error, try again'], 500);
        }
    }

    public function updateProfile(Request $request) {

        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email,'.auth()->id(),
        ]);


        if(auth()->user()->update($validatedData)){
            return ['message' => 'updarted successfully'];
        } else {
            return response()->json(['message' => 'Error, try again'], 500);
        }

    }
}
