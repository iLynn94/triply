<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone_number' => ['nullable', 'string', 'max:255'],
            'citizenship' => ['required', 'in:Kenyan Citizen,Kenyan Resident,East Africa Resident,Non Resident'],
            'profile_image_url' => ['nullable', 'url'],
        ]);

        $user->update($validated);

        return redirect()->route('profile')->with('success', 'Profile updated successfully!');
    }

    public function resendVerificationEmail(Request $request)
    {
        $user = auth()->user();

        if ($user->hasVerifiedEmail()) {
            return back()->with('error', 'Your email is already verified');
        }

        $user->sendEmailVerificationNotification();

        return back()->with('success', 'Verification email sent! Please check your email (or logs if in development).');
    }

    public function verifyEmail(Request $request, $id, $hash)
    {
        $user = \App\Models\User::findOrFail($id);

        // Verify the hash matches
        if (!hash_equals($hash, sha1($user->getEmailForVerification()))) {
            abort(403, 'Invalid verification link');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('profile')->with('success', 'Your email was already verified!');
        }

        $user->markEmailAsVerified();

        // Also set is_verified to true for our custom verification system
        $user->is_verified = true;
        $user->save();

        return redirect()->route('profile')->with('success', 'Email verified successfully! Your account is now verified.');
    }
}
