<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin.setting.index', [
            'user' => Auth::user(),
        ]);
    }

    

    public function update(Request $request)
    {
        $user = Auth::user(); // Ambil user yang sedang login

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female,other',
            'birth_date' => 'nullable|date',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('profile_image')) {
            if ($user->image_url) {
                Storage::disk('public')->delete($user->image_url);
            }
            $imagePath = $request->file('profile_image')->store('profile_images', 'public');
            $validated['image_url'] = $imagePath;
        }

        $user->update($validated);

        return redirect()->route('setting.index')->with('success', 'Pengaturan akun berhasil diperbarui!');
    }
}
