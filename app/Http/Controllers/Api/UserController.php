<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Mail\UserRegisteredNotification;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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

        $validatedData['password'] = Hash::make($validatedData['password']);

        $user = User::create($validatedData);

        $admin = User::where('role', 'admin')->first();

        if ($admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'user_registration',
                'message' => "User {$user->name} baru saja mendaftar.",
                'is_read' => false,
            ]);

            Mail::to($admin->email)->send(new UserRegisteredNotification($user));
    }

        return response()->json([
            'success' => 'Account created successfully',
            'user' => $user
        ]);
    }

    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'error' => 'Invalid Credentials'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'You have successfully logged in!',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
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
