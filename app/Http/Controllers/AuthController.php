<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showSignIn(Request $request)
    {
        // Store the previous URL for redirect after login (unless it's from sign-up or sign-in pages)
        $previousUrl = url()->previous();
        $currentUrl = url()->current();

        if ($previousUrl !== $currentUrl &&
            !str_contains($previousUrl, '/sign-up') &&
            !str_contains($previousUrl, '/sign-in')) {
            session(['url.intended' => $previousUrl]);
        }

        return view('auth.sign-in');
    }

    public function showSignUp(Request $request)
    {
        // Store the previous URL for redirect after signup (unless it's from sign-up or sign-in pages)
        $previousUrl = url()->previous();
        $currentUrl = url()->current();

        if ($previousUrl !== $currentUrl &&
            !str_contains($previousUrl, '/sign-up') &&
            !str_contains($previousUrl, '/sign-in')) {
            session(['url.intended' => $previousUrl]);
        }

        return view('auth.sign-up');
    }

    public function signUp(Request $request)
    {
        $validated = $request->validate([
            'first_name'        => 'required|string|max:100',
            'last_name'         => 'required|string|max:100',
            'email'             => 'required|email|max:255|unique:users,email',
            'phone_number'      => 'nullable|string|max:20',
            'citizenship'       => 'required|in:Kenyan Citizen,Kenyan Resident,East Africa Resident,Non Resident',
            'password'          => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'first_name'    => $validated['first_name'],
            'last_name'     => $validated['last_name'],
            'email'         => $validated['email'],
            'phone_number'  => $validated['phone_number'] ?? null,
            'citizenship'   => $validated['citizenship'],
            'password'      => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        // Determine redirect URL (previous page or home)
        $redirectUrl = session('url.intended', '/');
        session()->forget('url.intended');

        // Flash toast notification
        session()->flash('notify', [
            'content' => 'Account created successfully! Welcome to Triply.',
            'type' => 'success',
            'duration' => 2000
        ]);

        return redirect($redirectUrl);
    }

    public function signIn(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($credentials, $request->remember)) {
            return back()->withErrors([
                'email' => 'Invalid credentials.'
            ]);
        }

        // Determine redirect URL (previous page or home)
        $redirectUrl = session('url.intended', '/');
        session()->forget('url.intended');

        // Flash toast notification
        session()->flash('notify', [
            'content' => 'Welcome back! You have successfully signed in.',
            'type' => 'success',
            'duration' => 2000
        ]);

        return redirect($redirectUrl);
    }

    public function logout()
    {
        Auth::logout();

        session()->flash('notify', [
            'content' => 'You have been logged out successfully.',
            'type' => 'success',
            'duration' => 2000
        ]);

        return redirect('/');
    }
}

