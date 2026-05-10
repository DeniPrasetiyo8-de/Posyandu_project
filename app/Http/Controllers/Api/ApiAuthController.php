<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ApiAuthController extends Controller
{
    /**
     * POST /api/auth/login
     * Login user dan return token
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah'
            ], 401);
        }

        // Revoke old tokens
        $user->tokens()->delete();

        // Create new token with abilities
        $abilities = $user->role === 'admin' ? ['admin', 'user'] : ['user'];
        $token = $user->createToken('auth-token', $abilities)->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role ?? 'user',
                    'rw' => $user->rw,
                    'phone' => $user->phone,
                    'address' => $user->address,
                    'rt' => $user->rt,
                ],
                'token' => $token
            ]
        ]);
    }

    /**
     * POST /api/auth/logout
     * Logout user dan revoke token
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ]);
    }

    /**
     * GET /api/auth/me
     * Get current user info
     */
    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role ?? 'user',
                'rw' => $user->rw,
                'phone' => $user->phone,
                'address' => $user->address,
                'rt' => $user->rt,
            ]
        ]);
    }

    /**
     * POST /api/auth/register
     * Register new user (optional - bisa disabled untuk production)
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'rw' => 'nullable|string|max:10',
            'rt' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'rw' => $request->rw,
            'rt' => $request->rt,
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => 'user', // Default role
        ]);

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
                'token' => $token
            ]
        ], 201);
    }
}
