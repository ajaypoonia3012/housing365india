<?php

namespace App\Http\Controllers;

use App\Models\CityLocation;
use App\Models\Referral;
use App\Models\ReferralPayout;
use App\Models\ReferralReward;
use App\Models\Setting;
use App\Services\ReferralService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReferralDashboardController extends Controller
{
    public function __construct(
        protected ReferralService $referralService
    ) {}

    /**
     * Dashboard Referral Overview & History.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $stats = $this->referralService->getReferralStats($user);
        $rewardStats = $this->referralService->getRewardStats($user);
        $userCode = $user->getOrCreateReferralCode();

        $referrals = Referral::where('referrer_user_id', $user->id)
            ->with(['latestReward', 'qualifiedProperty'])
            ->latest()
            ->paginate(15);

        return view('dashboard.referrals.index', compact(
            'user',
            'stats',
            'rewardStats',
            'userCode',
            'referrals'
        ));
    }

    /**
     * Show form to submit a new referral.
     */
    public function create()
    {
        $user = Auth::user();
        $cities = CityLocation::orderBy('name')->pluck('name');
        $rewardAmount = Setting::get('referral_reward_amount', '100');

        return view('dashboard.referrals.create', compact('user', 'cities', 'rewardAmount'));
    }

    /**
     * Store new referral submission.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'submission_method' => 'required|in:refer_owner,property_board_photo',
            'owner_name' => 'required|string|max:100',
            'owner_phone' => 'required|string|max:25',
            'owner_email' => 'nullable|email|max:150',
            'state' => 'nullable|string|max:100',
            'city' => 'required|string|max:100',
            'locality' => 'required|string|max:150',
            'property_type' => 'required|string|max:100',
            'listing_intent' => 'required|in:rent,sale,pg,coliving,commercial,land',
            'property_address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'referrer_relationship' => 'nullable|string|max:100',
            'photo' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:5120',
            'consent_confirmed' => 'required|accepted',
        ]);

        try {
            $referral = $this->referralService->createReferral(
                $user,
                $validated,
                $request->file('photo')
            );

            return redirect()->route('dashboard.referrals.show', $referral->id)
                ->with('success', '🎉 Referral submitted successfully! Our verification team has queued this property for qualification.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Show specific referral details and verification audit trail.
     */
    public function show(int $id)
    {
        $user = Auth::user();
        $referral = Referral::with(['rewards', 'qualifiedProperty', 'auditLogs'])
            ->findOrFail($id);

        if (! $referral->isAuthorizedReferrer($user)) {
            abort(403, 'Unauthorized access to this referral record.');
        }

        return view('dashboard.referrals.show', compact('referral', 'user'));
    }

    /**
     * My Rewards ledger and UPI payout hub.
     */
    public function rewards(Request $request)
    {
        $user = Auth::user();
        $rewardStats = $this->referralService->getRewardStats($user);

        $rewards = ReferralReward::where('user_id', $user->id)
            ->with(['referral', 'payout'])
            ->latest()
            ->paginate(15);

        $payouts = ReferralPayout::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('dashboard.referrals.rewards', compact(
            'user',
            'rewardStats',
            'rewards',
            'payouts'
        ));
    }

    /**
     * Save or update UPI ID for payouts.
     */
    public function savePayoutMethod(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'upi_id' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-zA-Z0-9.\-_]{2,49}@[a-zA-Z]{2,49}$/',
            ],
        ], [
            'upi_id.regex' => 'Please enter a valid UPI ID format (e.g. yourname@okhdfcbank or 9876543210@paytm).',
        ]);

        $user->update([
            'upi_id' => strtolower(trim($validated['upi_id'])),
            'upi_id_encrypted' => encrypt(strtolower(trim($validated['upi_id']))),
        ]);

        return back()->with('success', 'UPI ID saved successfully! Payouts will be disbursed to: '.$user->masked_upi);
    }

    /**
     * Request payout for approved rewards.
     */
    public function requestPayout(Request $request)
    {
        $user = Auth::user();

        try {
            $payout = $this->referralService->requestPayout($user);

            return redirect()->route('dashboard.rewards')
                ->with('success', 'Payout request #'.$payout->payout_number.' for ₹'.number_format($payout->amount, 2).' has been submitted for admin processing.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
