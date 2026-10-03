<?php

namespace App\Http\Controllers;

use App\Models\CityLocation;
use App\Models\Referral;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReferralController extends Controller
{
    /**
     * Public Refer & Earn landing page.
     */
    public function landing(Request $request)
    {
        $refCode = $request->query('ref');
        $referringUser = null;

        if ($refCode) {
            $referringUser = User::where('referral_code', $refCode)->first();
            session(['referral_code' => $refCode]);
        }

        $user = Auth::user();
        $userCode = $user ? $user->getOrCreateReferralCode() : null;

        $rewardAmount = Setting::get('referral_reward_amount', '100');
        $cities = CityLocation::orderBy('name')->pluck('name');

        return view('referrals.landing', compact(
            'refCode',
            'referringUser',
            'user',
            'userCode',
            'rewardAmount',
            'cities'
        ));
    }

    /**
     * Referral Program Terms & Conditions page.
     */
    public function terms()
    {
        $rewardAmount = Setting::get('referral_reward_amount', '100');

        return view('referrals.terms', compact('rewardAmount'));
    }

    /**
     * Short link redirect: /r/{code} -> /refer-and-earn?ref={code}
     */
    public function redirectShortLink(string $code)
    {
        return redirect()->route('refer-and-earn', ['ref' => $code]);
    }

    /**
     * Securely download or view private property-board photo.
     */
    public function downloadPhoto(int $id)
    {
        $user = Auth::user();
        if (! $user) {
            abort(403, 'Authentication required to access referral documents.');
        }

        $referral = Referral::findOrFail($id);

        if (! $referral->isAuthorizedReferrer($user)) {
            abort(403, 'Unauthorized access to this referral document.');
        }

        if (empty($referral->photo_path) || ! Storage::disk('local')->exists($referral->photo_path)) {
            abort(404, 'Photo not found or has been removed.');
        }

        return Storage::disk('local')->response($referral->photo_path);
    }
}
