<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $tag = "#" . substr(str_shuffle("0123456789"), 0, 4);

        $request["username"] .= $tag;

        $body = $request->validate([
            'username' => 'required|string|max:32|min:8|unique:users,username',
            'email' => 'required|string|unique:users,email|email',
            'password' => 'required|string|confirmed'
        ]);

        $usr = User::create([
            'username' => $body['username'],
            'email' => $body['email'],
            'password' => bcrypt($body['password']),
        ]);

        $token = $usr->createToken('app_token')->plainTextToken;

        $response = [
            'user' => $usr,
            'token' => $token,
        ];

        return response($response, 201);
    }

    public function changeTag(Request $request)
    {
        $username = substr(auth()->user()->username, 0, -5);

        $request['username'] = $username . $request['tag'];

        if ($request['username'] === auth()->user()->username) return response(['message' => 'Tag unchanged.'], 422);

        $body = $request->validate([
            'username' => 'required|string|max:32|min:8|unique:users,username|regex:/^([a-z])+#+\d{4}$/i'
        ]);

        auth()->user()->update(['username' => $body['username']]);

        return response(['user' => auth()->user()->username], 201);
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

        $idfType = filter_var($body['identifier'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $usr = User::where($idfType, $body['identifier'])->first();

        if (!$usr || !Hash::check($body['password'], $usr->password)) {
            return response([
                'message' => 'Invalid Credentials.'
            ], 401);
        }

        $token = $usr->createToken('app_token')->plainTextToken;

        $response = [
            "data" => [
                'user' => $usr,
                'token' => $token,
            ],
            "success" => true
        ];

        return response($response, 201);
    }
}