<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    public function login(\Illuminate\Http\Request $request)
    {
        // get the user
        $user = User::query()->where('email', $request->email)->first();

        // check if user with email exists
        if (is_null($user)) {
            return response()->json([
                'error' => 'user not found'
            ], 401);
        }

        // send token if password is correct
        if (Hash::check($request->password, $user->password)) {
            return response()->json([
                'userId' => $user->id,
                'token' => $user->remember_token,
            ]);
        }

        // return error if password is incorrect
        return response()->json([
            'error' => 'password incorrect'
        ], 401);
    }
}