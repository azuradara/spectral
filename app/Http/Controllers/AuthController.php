<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $body = $request->validate([
            'username' => 'required|string|max:32|unique:users,username',
            'email' => 'required|string|unique:users,email|email',
            'name' => 'required|max:32',
            'password' => 'required|string|confirmed'
        ]);

        $usr = User::create([
            'username' => $body['username'],
            'email' => $body['email'],
            'name' => $body['name'],
            'password' => bcrypt($body['password']),
        ]);

        $token = $usr->createToken('app_token')->plainTextToken;

        $response = [
            'user' => $usr,
            'token' => $token,
        ];

        return response($response, 201);
    }

    public function signout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Signed out'
        ];
    }

    public function login(Request $request)
    {
        $body = $request->validate([
            'identifier' => 'required',
            'password' => 'required'
        ]);

        $idfType = filter_var($body['identified'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $usr = User::where($idfType, $body['identifier'])->first();

        if (!$usr || !Hash::check($body['password'], $usr->password)) {
            return response([
                'message' => 'Invalid Credentials.'
            ], 401);
        }

        $token = $usr->createToken('app_token')->plainTextToken;

        $response = [
            'user' => $usr,
            'token' => $token,
        ];

        return response($response, 201);
    }
}