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
            
            $token = $user->createToken($user->is_admin ? 'admin-token' : 'user-token')->plainTextToken;

            return response()->json(['token' => $token, 'user' => $user], 201);
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

    public function index() {

        $user = User::all();

        return response()->json($user, 200);
    }

    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }

    public function update(Request $request, $id) {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => ['sometimes', 'required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'sometimes|required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        $user->update($validated);

        return response()->json(['message' => 'User updated successfully']);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    public function count()
    {
        $count = User::count();
        return response()->json(['count' => $count], 200);
    }
}
