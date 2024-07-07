<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Organisation;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user registration successfully with default organisation.
     */
    public function testRegisterUserSuccessfullyWithDefaultOrganisation()
    {
        $userData = [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password',
            'phone' => '123456789'
        ];

        $response = $this->postJson('/api/auth/register', $userData);

        $response
            ->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'message' => 'Registration successful',
                'data' => [
                    'accessToken' => true, // replace with your logic for checking token
                    'user' => [
                        'userId' => true, // replace with your logic for checking userId
                        'firstName' => 'John',
                        'lastName' => 'Doe',
                        'email' => 'john.doe@example.com',
                        'phone' => '123456789'
                    ]
                ]
            ])
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'accessToken',
                    'user' => [
                        'userId',
                        'firstName',
                        'lastName',
                        'email',
                        'phone'
                    ]
                ]
            ]);
    }

    /**
     * Test user registration fails with missing required fields.
     */
    public function testFailRegistrationWithMissingRequiredFields()
    {
        $userData = [
            // Missing 'firstName'
            'lastName' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password',
            'phone' => '123456789'
        ];

        $response = $this->postJson('/api/auth/register', $userData);

        $response
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    [
                        'field' => 'firstName',
                        'message' => 'The firstName field is required.'
                    ]
                ]
            ]);
    }

    /**
     * Test user login successfully.
     */
    public function testLoginUserSuccessfully()
    {
        // First, register a user
        $user = User::factory()->create([
            'email' => 'john.doe@example.com',
            'password' => bcrypt('password'),
        ]);

        $loginData = [
            'email' => 'john.doe@example.com',
            'password' => 'password',
        ];

        $response = $this->postJson('/api/auth/login', $loginData);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Login successful',
                'data' => [
                    'accessToken' => true, // replace with your logic for checking token
                    'user' => [
                        'userId' => true, // replace with your logic for checking userId
                        'firstName' => 'John',
                        'lastName' => 'Doe',
                        'email' => 'john.doe@example.com',
                        'phone' => null // adjust based on your implementation
                    ]
                ]
            ]);
    }

    /**
     * Test user login fails with invalid credentials.
     */
    public function testFailLoginWithInvalidCredentials()
    {
        // Attempt login with incorrect password
        $loginData = [
            'email' => 'john.doe@example.com',
            'password' => 'wrongpassword',
        ];

        $response = $this->postJson('/api/auth/login', $loginData);

        $response
            ->assertStatus(401)
            ->assertJson([
                'status' => 'Bad request',
                'message' => 'Authentication failed'
            ]);
    }

    // Add more test methods for other scenarios as discussed earlier...

    // Example: Scenario 3, 6, 7, 8, 9...

    // Ensure to adjust and expand based on your application's actual implementation and requirements.
}

