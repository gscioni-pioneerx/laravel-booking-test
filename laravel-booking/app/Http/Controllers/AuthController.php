<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * User registration
     *
     * @param Request $request
     * @return JsonResponse
     */

    public function register(Request $request): JsonResponse
    {
        $request->headers->set('Accept', 'application/json');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:8'
        ]);

        $user = User::create($validated);

        return response()->json([
            'user' => $user
        ]);
    }

    /**
     * User login
     *
     * @param Request $request
     * @return JsonResponse
     */

    public function login(Request $request): JsonResponse
    {
        $request->headers->set('Accept', 'application/json');

        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($validated)) {
            return response()->json([
                'message' => 'Invaid email address or password.'
            ], 401);
        }

        $token = $request->user()->createToken('auth_token');

        return response()->json([
            'message' => 'Successfully logged in',
            'token' => $token->plainTextToken
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out'
        ]);
    }
}
