<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Migration
{
    /**
     * Display a listing of the users.
     */
    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credential = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credential)) {
            return redirect()->intended('dashboard')->with('success', 'You are logged in!');
        }
        if (!Auth::attempt($credential)) {
            return back()->withErrors([
                'email' => 'Your provided credentials do not match in our records.',
            ])->onlyInput('email');
        }

        return to_route('dashboard')
            ->withSuccess('You have successfully logged in!');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function store(Request $request)
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
        ]);

        $validatedData['image_url'] = $request->file('image_url')->store('images', 'public');
        User::create($validatedData);

        return to_route('auth.login')->with('success', 'Account created successfully');
    }

    public function dashboard()
    {
        return view('auth.dashboard');
    }

    public function logout(request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('auth.login')
            ->withSuccess('You have logged out successfully!');;
    }
}
