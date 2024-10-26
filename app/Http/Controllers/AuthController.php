<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Exception;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json(['message' => 'User registered successfully'], 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Validation failed', 'message' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['error' => 'Registration failed', 'message' => $e->getMessage()], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json(['message' => 'Login successfully', 'token' => $token], 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Invalid credentials', 'message' => $e->getMessage()], 422);
        } catch (Exception $e) {
            return response()->json(['error' => 'Login failed', 'message' => $e->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            if (!$request->user()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $request->user()->tokens()->delete();

            return response()->json(['message' => 'Logged out successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Logout failed', 'message' => $e->getMessage()], 500);
        }
    }
}
