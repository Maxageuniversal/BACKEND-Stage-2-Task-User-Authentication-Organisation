<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organisation;

class OrganisationController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Retrieve organizations belonging to the user
        $organisations = $user->organisations()->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Retrieved user organisations',
            'data' => [
                'organisations' => $organisations,
            ],
        ], 200);
    }

    public function show($orgId)
    {
        $user = auth()->user();

        // Retrieve organization if user belongs to it
        $organisation = $user->organisations()->where('orgId', $orgId)->first();

        if (!$organisation) {
            return response()->json([
                'status' => 'Not Found',
                'message' => 'Organization not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Retrieved organisation',
            'data' => $organisation,
        ], 200);
    }

    public function store(Request $request)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Bad Request',
                'message' => 'Client error',
                'errors' => $validator->errors()->toArray(),
            ], 400);
        }

        // Create organisation
        $organisation = new Organisation([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);

        $organisation->save();

        // Attach user to organisation
        $user = auth()->user();
        $organisation->users()->attach($user);

        return response()->json([
            'status' => 'success',
            'message' => 'Organisation created successfully',
            'data' => $organisation,
        ], 201);
    }

    public function addUser(Request $request, $orgId)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'userId' => 'required|string', // You may need to adjust this based on your implementation
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'Bad Request',
                'message' => 'Client error',
                'errors' => $validator->errors()->toArray(),
            ], 400);
        }

        // Find organisation
        $organisation = Organisation::find($orgId);

        if (!$organisation) {
            return response()->json([
                'status' => 'Not Found',
                'message' => 'Organization not found',
            ], 404);
        }

        // Add user to organisation
        $user = User::find($request->input('userId'));

        if (!$user) {
            return response()->json([
                'status' => 'Not Found',
                'message' => 'User not found',
            ], 404);
        }

        $organisation->users()->attach($user);

        return response()->json([
            'status' => 'success',
            'message' => 'User added to organisation successfully',
        ], 200);
    }
}
