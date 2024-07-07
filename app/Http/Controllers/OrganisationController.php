<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class OrganisationController extends Controller
{
    public function index()
    {
        // Retrieve authenticated user
        $user = Auth::user();

        if (!$user) {
            return Response::json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        // Eager load organisations to avoid N+1 query issue
        $userWithOrganisations = User::with('organisations')->find($user->id);

        // Check if user with organisations is found
        if (!$userWithOrganisations) {
            return Response::json([
                'status' => 'error',
                'message' => 'User not found',
            ], 404);
        }

        // Extract organisations from the user model
        $organisations = $userWithOrganisations->organisations;

        return Response::json([
            'status' => 'success',
            'message' => 'Retrieved user organisations',
            'data' => [
                'organisations' => $organisations,
            ],
        ], 200);
    }
}
