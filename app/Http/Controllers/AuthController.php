<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Property;
use App\Models\Inquiry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Show the user registration form.
     */
    public function showRegister(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('my.prime');
        }
        $selectedPlan = $request->get('plan', '');
        return view('auth.register', compact('selectedPlan'));
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
            'user_type' => 'required|in:tenant,buyer,owner',
            'city' => 'nullable|string|max:100',
            'plan' => 'nullable|string|max:50',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'user_type' => $validated['user_type'],
            'city' => $validated['city'] ?? 'Jaipur',
            'password' => Hash::make($validated['password']),
        ]);

        // If user registered with a Prime plan selected
        if (!empty($validated['plan'])) {
            $planLimits = [
                'Prime Basic' => ['contacts' => 25, 'days' => 45],
                'Prime Pro' => ['contacts' => 50, 'days' => 90],
                'Prime VIP' => ['contacts' => 999, 'days' => 180],
            ];
            $plan = $validated['plan'];
            $config = $planLimits[$plan] ?? ['contacts' => 50, 'days' => 90];
            $user->activatePrime($plan, $config['contacts'], $config['days']);
        }

        Auth::login($user);

        // Record inquiry/lead
        Inquiry::create([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'service_type' => !empty($validated['plan']) ? 'Housing365 Prime Registration' : 'Standard User Registration',
            'subject' => 'New User Registration (' . ucfirst($user->user_type) . ') - ' . ($user->city ?? 'Jaipur'),
            'message' => 'User registered in ' . ($user->city ?? 'Jaipur') . '. Prime: ' . ($user->is_prime ? $user->prime_plan : 'Free Account'),
        ]);

        if ($user->is_prime) {
            return redirect()->route('my.prime')->with('success', 'Welcome to Housing365 Prime! Your membership is active.');
        }

        return redirect()->route('properties.index')->with('success', 'Account registered successfully! Welcome to Housing 365.');
    }

    /**
     * Show the user login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('my.prime');
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
            if (Auth::user()->is_prime) {
                return redirect()->intended(route('my.prime'));
            }
            return redirect()->intended(route('properties.index'));
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
     * Handle AJAX/Modal Prime Activation and User Linking.
     */
    public function activatePrime(Request $request)
    {
        $request->validate([
            'plan' => 'required|string',
            'name' => 'required|string|max:120',
            'phone' => 'required|string|max:20',
            'city' => 'nullable|string|max:100',
            'localities' => 'nullable|string|max:255',
            'bhk' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:150',
        ]);

        $planLimits = [
            'Prime Basic' => ['contacts' => 25, 'days' => 45, 'price' => '₹999'],
            'Prime Pro' => ['contacts' => 50, 'days' => 90, 'price' => '₹1,999'],
            'Prime VIP' => ['contacts' => 999, 'days' => 180, 'price' => '₹3,999'],
        ];

        $plan = $request->plan;
        $config = $planLimits[$plan] ?? ['contacts' => 50, 'days' => 90, 'price' => '₹1,999'];

        if (Auth::check()) {
            $user = Auth::user();
            $user->phone = $request->phone;
            $user->city = $request->city ?? $user->city;
            $user->preferred_localities = $request->localities;
            $user->preferred_bhk = $request->bhk;
            $user->activatePrime($plan, $config['contacts'], $config['days']);
        } else {
            // Find or create user by email or phone
            $email = $request->email ?: (preg_replace('/[^0-9]/', '', $request->phone) . '@housing365.in');
            $user = User::where('email', $email)->orWhere('phone', $request->phone)->first();

            if (!$user) {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $email,
                    'phone' => $request->phone,
                    'city' => $request->city ?? 'Jaipur',
                    'user_type' => 'tenant',
                    'preferred_localities' => $request->localities,
                    'preferred_bhk' => $request->bhk,
                    'password' => Hash::make(Str::random(12)),
                ]);
            }

            $user->activatePrime($plan, $config['contacts'], $config['days']);
            Auth::login($user);
        }

        // Record inquiry
        Inquiry::create([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'service_type' => 'Prime Activation (' . $plan . ')',
            'subject' => 'Housing365 Prime Activated for ' . $user->name . ' (' . ($user->city ?? 'Jaipur') . ')',
            'message' => "City: " . ($user->city ?? 'Jaipur') . "\nPlan: {$plan} ({$config['price']})\nLocalities: {$request->localities}\nBHK: {$request->bhk}",
        ]);

        $waMsg = "Hi Housing365, I have activated {$plan} ({$config['price']}). Name: {$user->name}, Phone: {$user->phone}, City: {$user->city}. Please assign my Relationship Manager!";
        $waUrl = "https://wa.me/916376163263?text=" . urlencode($waMsg);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Prime Membership Activated!',
                'redirect_url' => route('my.prime'),
                'whatsapp_url' => $waUrl,
            ]);
        }

        return redirect()->route('my.prime')->with('success', 'Housing365 Prime Membership Activated successfully!');
    }

    /**
     * Show User's Prime Dashboard (/my-prime).
     */
    public function myPrime()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Please log in to view your Prime Membership.');
        }

        $user = Auth::user();
        $recommendedProperties = Property::where('status', 'Available')
            ->when(!empty($user->city), fn($q) => $q->where('city', $user->city))
            ->latest()
            ->take(6)
            ->get();

        return view('prime.dashboard', compact('user', 'recommendedProperties'));
    }

    /**
     * Unlock direct owner contact on a property for a Prime member.
     */
    public function unlockOwnerContact(Request $request, $propertyId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'require_login' => true,
                'message' => 'Please log in to view direct owner contact details.',
            ], 401);
        }

        $user = Auth::user();
        if (!$user->isPrimeActive()) {
            return response()->json([
                'success' => false,
                'require_prime' => true,
                'message' => 'Housing365 Prime membership required to unlock direct owner numbers.',
            ], 403);
        }

        $property = Property::findOrFail($propertyId);

        // Increment unlocked contacts count if not unlimited
        if ($user->prime_contacts_limit < 999) {
            $user->increment('unlocked_contacts_count');
        }

        return response()->json([
            'success' => true,
            'owner_name' => $property->seller_name ?? 'Property Owner',
            'owner_phone' => $property->seller_phone ?? '+91 63761 63263',
            'remaining_contacts' => $user->remainingContacts(),
            'message' => 'Owner phone unlocked successfully!',
        ]);
    }
}
