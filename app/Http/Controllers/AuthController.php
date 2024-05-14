<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    function register(Request $request) {
        $validation = Validator::make($request->all(), [
            'full_name' => ['required'],
            'bio' => ['required', 'max:100'],
            'username' => ['required', 'min:3', 'unique:users,username', 'alpha_dash'],
            'password' => ['required', 'min:6'],
            'is_private' => ['boolean'],
        ]);

        if ($validation->fails()) {
            return Response::json([
                'message' => 'Invalid field',
                'errors' => $validation->errors()
            ], 401);
        }

        User::create([
            'full_name' => $request->full_name,
            'username' => $request->username,
            'password' => $request->password,
            'bio' => $request->bio,
            'is_private' => $request->is_private ?? 0,
        ]);

        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('user-token')->plainTextToken;
            $result_data_login = [
                'full_name' => $user->full_name,
                'bio' => $user->bio,
                'username' => $user->username,
                'is_private' => $user->is_private,
                'id' => $user->id,
            ];

            return Response::json([
                'message' => 'Register success',
                'token' => $token,
                'user' => $result_data_login
            ], 200);
        }
    }

    function login(Request $request) {
        $validation = Validator::make($request->all(), [
            'username' => ['required'],
            'password' => ['required']
        ]);

        if ($validation->fails()) {
            return Response::json(['message' => 'Wrong username or password'], 401);
        }

        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken(env('SECRET_TOKEN', null))->plainTextToken;
            $result_data_login = [
                'id' => $user->id,
                'full_name' => $user->full_name,
                'username' => $user->username,
                'bio' => $user->bio,
                'is_private' => $user->is_private,
                'created_at' => $user->created_at,
            ];

            return Response::json([
                'message' => 'Login success',
                'token' => $token,
                'user' => $result_data_login
            ], 200);
        }

        return Response::json(['message' => 'Unauthenticated'], 401);
    }

    function logout() {
        $user = Auth::user();
        $id_token = $user->currentAccessToken()->id;
        $user->tokens()->where('id', $id_token)->delete();
        
        return Response::json(['message' => 'Logout success']);
    }

    function me() {
        return Auth::user();
    }
}
