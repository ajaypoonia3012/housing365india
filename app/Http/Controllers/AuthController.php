<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show the user registration form.
     */
    /**
     * Show the user registration form.
     */
    public function showRegister(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('properties.index');
        }

        return view('auth.register');
    }

    /**
     * Handle user registration.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:150|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:6|confirmed',
            'city' => 'nullable|string|max:100',
            'roles' => 'nullable|array',
            'roles.*' => 'string|in:owner,tenant,buyer,seller,broker,builder,pg_operator,coliving_operator,coworking_operator,vendor',
            'user_type' => 'nullable|string',
        ]);

        // Determine personas to assign
        $selectedRoles = $request->input('roles', []);
        if (empty($selectedRoles) && ! empty($validated['user_type'])) {
            $legacyType = $validated['user_type'];
            if ($legacyType === 'operator') {
                $selectedRoles = ['pg_operator', 'coliving_operator'];
            } elseif (in_array($legacyType, ['owner', 'tenant', 'buyer', 'seller', 'broker', 'builder', 'vendor'])) {
                $selectedRoles = [$legacyType];
            } else {
                $selectedRoles = ['tenant'];
            }
        }

        if (empty($selectedRoles)) {
            $selectedRoles = ['tenant'];
        }

        $selectedRoles = array_values(array_unique($selectedRoles));
        $primaryRole = $selectedRoles[0] ?? 'tenant';

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'user_type' => $primaryRole,
            'current_role' => $primaryRole,
            'city' => $validated['city'] ?? 'Jaipur',
            'password' => Hash::make($validated['password']),
        ]);

        foreach ($selectedRoles as $roleName) {
            $user->assignRole($roleName);
        }

        Auth::login($user);

        // Record registration activity
        Inquiry::create([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'service_type' => 'User Account Registration',
            'subject' => 'New User Registration ('.implode(', ', array_map('ucfirst', $selectedRoles)).') - '.($user->city ?? 'India'),
            'message' => 'User registered with personas: '.implode(', ', $selectedRoles).' in city: '.($user->city ?? 'India'),
        ]);

        return redirect()->route('dashboard')->with('success', 'Account registered successfully! Welcome to Housing365 India.');
    }

    /**
     * Show the user login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle user login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('info', 'You have been logged out successfully.');
    }

    /**
     * Deprecated Prime Activation endpoint for backward compatibility.
     */
    public function activatePrime(Request $request)
    {
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Housing365 standard free account active.',
                'redirect_url' => route('properties.index'),
            ]);
        }

        return redirect()->route('properties.index');
    }

    /**
     * Deprecated Prime Dashboard redirect.
     */
    public function myPrime()
    {
        return redirect()->route('properties.index');
    }

    /**
     * Direct owner contact unlock with Contact Access Paywall enforcement.
     */
    public function unlockOwnerContact(Request $request, $propertyId)
    {
        return app(ContactAccessController::class)->unlockContact($request, $propertyId);
    }
}
