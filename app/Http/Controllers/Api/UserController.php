<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {            
        $validatedData = $request->validate([
            'image_url' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'gender' => ['required', 'boolean'],
            'birth_date' => ['nullable', 'date'],
            'address' => ['nullable', 'string'],
            'role' => ['required', 'in:customer'],
        ]);

        if ($request->hasFile('image_url')) {
            $validatedData['image_url'] = $request->file('image_url')->store('images', 'public');
        } else {
            $validatedData['image_url'] = null;
        }    
        User::create($validatedData);

        return response()->json(['success' => 'Account created successfully']);
    }

    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!auth()->attempt($validatedData)) {
            return response()->json([
                'error' => 'Invalid Credentials'
            ], 401);
        }

        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'You have successfully logged in!',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'You have successfully logged out!'
        ]);
    }
}
