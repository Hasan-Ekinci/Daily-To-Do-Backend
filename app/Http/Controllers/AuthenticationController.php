<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    /**
     * The login function
     */
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

            // delete the previous tokens of the user
            $user->tokens()->delete();

            // return the response
            return response()->json([
                'userId' => $user->id,
                'token' => $user->createToken('auth-token')->plainTextToken,
            ]);
        }

        // return error if password is incorrect
        return response()->json([
            'error' => 'password incorrect'
        ], 401);
    }
}