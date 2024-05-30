<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        // Attempting authentication with provided email and password
        if (auth()->attempt($request->only('email', 'password'))) {
            $user = auth()->user();
            $token = $user->createToken('token-name')->plainTextToken;

            return response()->json(['token' => $token], 201);
        }

        // If authentication fails, redirect back with input and a warning message
        return response()->json(['message' => 'Invalid username or password'], 401);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out'], 200);
    }

    public function register(Request $request)
    {
        
        $validated = $request->validate([
            'name' => ['required', 'string','max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
        ],
        [
            'email.unique' => 'The email has already been taken.',
            'password.min' => 'The password must be at least 6 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, one digit, and one special character.',
        ]);

        $validated ['password'] = bcrypt($validated ['password']);
        $user = User::create($validated);
        

        return response()->json(['message' => 'User registered successfully'], 201);
    }
}
