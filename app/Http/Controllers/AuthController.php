<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest; // Import custom request for validation
use Illuminate\Support\Facades\Auth; // Import Auth facade for authentication

class AuthController extends Controller
{
    /**
     * Handle the incoming login request.
     *
     * @param  \App\Http\Requests\LoginRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(LoginRequest $request)
    {
        // Validate the incoming credentials using the custom LoginRequest
        $credentials = $request->validated();
        
        // Attempt to log the user in using the provided credentials
        if (!Auth::attempt($credentials)) {
            // If authentication fails, return an unauthorized response (401)
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        
        // If authentication succeeds, generate an API token for the user
        $token = $request->user()->createToken('api-token')->plainTextToken;

        // Return the generated token in a JSON response
        return response()->json(['token' => $token]);
    }
}
