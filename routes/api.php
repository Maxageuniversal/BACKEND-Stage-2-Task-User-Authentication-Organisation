<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrganisationController;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/api/users/{id}', function (Request $request, $id) {
        // Logic to retrieve user data
    });

    Route::group(['middleware' => 'jwt.auth'], function () {
        // Protected routes here
        Route::get('/organisations', [OrganisationController::class, 'index']);
        Route::get('/organisations/{orgId}', [OrganisationController::class, 'show']);
        Route::post('/organisations', [OrganisationController::class, 'store']);
        Route::post('/organisations/{orgId}/users', [OrganisationController::class, 'addUser']);
    });
});

// Ensure there's no extra closing brace or semicolon here
