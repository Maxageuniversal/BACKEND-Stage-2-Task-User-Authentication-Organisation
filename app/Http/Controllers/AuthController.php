<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Organisation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Bad Request',
                'message' => 'Registration unsuccessful',
                'errors' => $validator->errors()->toArray(),
            ], 400);
        }

        // Create user
        $user = User::create([
            'firstName' => $request->input('firstName'),
            'lastName' => $request->input('lastName'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'phone' => $request->input('phone'),
        ]);

        // Create default organisation for the user
        $organisation = Organisation::create([
            'name' => $user->firstName . "'s Organisation",
            'description' => 'Default organisation for ' . $user->firstName,
        ]);

        // Attach user to organisation
        $organisation->users()->attach($user);

        // Generate JWT token
        try {
            $token = JWTAuth::fromUser($user);
        } catch (JWTException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Could not create token',
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Registration successful',
            'data' => [
                'accessToken' => $token,
                'user' => $user,
            ],
        ], 201);
    }

    public function login(Request $request)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Bad Request',
                'message' => 'Authentication failed',
                'errors' => $validator->errors()->toArray(),
            ], 401);
        }

        // Attempt to log user in
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'status' => 'Bad Request',
                    'message' => 'Invalid credentials',
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Could not create token',
            ], 500);
        }

        // Retrieve user details
        $user = auth()->user();

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => [
                'accessToken' => $token,
                'user' => $user,
            ],
        ], 200);
    }
}
