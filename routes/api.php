<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrganisationController;

// Public routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:api')->group(function () {
    // User-specific routes
    Route::get('/api/users/{id}', function (Request $request, $id) {
        // Logic to retrieve user data
    });

    // JWT authenticated routes
    Route::group(['middleware' => 'jwt.auth'], function () {
        // Organisation routes
        Route::get('/api/organisations', [OrganisationController::class, 'index']);
        Route::get('/api/organisations/{orgId}', [OrganisationController::class, 'show']);
        Route::post('/api/organisations', [OrganisationController::class, 'store']);
        Route::post('/api/organisations/{orgId}/users', [OrganisationController::class, 'addUser']);
    });
});
