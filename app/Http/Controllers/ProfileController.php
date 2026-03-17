<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function editPassword()
    {
        return view('profile.password');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'confirmed', Password::min(6)],
        ]);

        $request->user()->update([
            'password' => $validated['password'],
        ]);

        return redirect()->route('profile.password.edit')
            ->with('success', 'Your password has been updated.');
    }
}
