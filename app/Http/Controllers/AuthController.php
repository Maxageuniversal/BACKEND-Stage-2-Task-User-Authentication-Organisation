<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Organisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
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

        // Create default organisation
        $organisation = new Organisation([
            'name' => $user->firstName . "'s Organisation",
            'description' => 'Default organisation created during registration.',
        ]);
        $organisation->save();

        // Attach user to organisation
        $organisation->users()->attach($user);

        // Generate JWT token
        $accessToken = auth()->login($user);

        return response()->json([
            'status' => 'success',
            'message' => 'Registration successful',
            'data' => [
                'accessToken' => $accessToken,
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
            ], 400);
        }

        // Attempt to authenticate user
        if (!$token = auth()->attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => 'Bad Request',
                'message' => 'Invalid credentials',
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => [
                'accessToken' => $token,
                'user' => auth()->user(),
            ],
        ], 200);
    }
}
