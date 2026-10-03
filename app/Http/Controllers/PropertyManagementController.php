<?php

namespace App\Http\Controllers;

use App\Models\ManagementEnrollment;
use App\Models\ManagementPlan;
use App\Models\Property;
use App\Services\PropertyManagementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertyManagementController extends Controller
{
    public function __construct(
        protected PropertyManagementService $managementService
    ) {}

    /**
     * Explore Property Management options for a specific property.
     * Clearly contrasts "Leasing Only (₹0 Free)" vs "Leasing + Property Management (Optional Paid)".
     */
    public function explore(int $propertyId)
    {
        $user = Auth::user();
        $property = Property::findOrFail($propertyId);

        if (! $property->isOwnedBy($user) && ! $user->hasRole('admin')) {
            abort(403, 'Unauthorized access to this property management settings.');
        }

        $plans = ManagementPlan::active()->get();
        $enrollment = ManagementEnrollment::where('property_id', $property->id)->first();

        return view('management.explore', compact('property', 'plans', 'enrollment', 'user'));
    }

    /**
     * Submit an enrollment request for a management plan.
     */
    public function requestEnrollment(Request $request, int $propertyId)
    {
        $user = Auth::user();
        $property = Property::findOrFail($propertyId);

        if (! $property->isOwnedBy($user) && ! $user->hasRole('admin')) {
            abort(403, 'Unauthorized access to this property management settings.');
        }

        $validated = $request->validate([
            'management_plan_id' => 'required|exists:management_plans,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        $plan = ManagementPlan::findOrFail($validated['management_plan_id']);

        try {
            $enrollment = $this->managementService->requestEnrollment($user, $property, $plan, $validated['notes'] ?? null);

            return redirect()->route('management.agreement', $enrollment->id)
                ->with('success', 'Property Management requested! Please review and accept the Property Management Agreement to activate service.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Review the Property Management Agreement.
     */
    public function showAgreement(int $enrollmentId)
    {
        $user = Auth::user();
        $enrollment = ManagementEnrollment::with(['property', 'plan'])->findOrFail($enrollmentId);

        if ($enrollment->user_id !== $user->id && ! $user->hasRole('admin')) {
            abort(403, 'Unauthorized access to this agreement.');
        }

        return view('management.agreement', compact('enrollment', 'user'));
    }

    /**
     * Owner accepts the separate Property Management Agreement.
     */
    public function acceptAgreement(Request $request, int $enrollmentId)
    {
        $user = Auth::user();
        $enrollment = ManagementEnrollment::findOrFail($enrollmentId);

        if ($enrollment->user_id !== $user->id && ! $user->hasRole('admin')) {
            abort(403, 'Unauthorized access to this agreement.');
        }

        $validated = $request->validate([
            'confirm_terms' => 'required|accepted',
        ]);

        try {
            $this->managementService->acceptAgreement($enrollment, $user, $request->ip());

            return redirect()->route('dashboard.owner')
                ->with('success', 'Property Management Agreement accepted! Your property "'.$enrollment->property->title.'" is now actively managed by Housing365.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Terminate or cancel management enrollment (reverting to standard Leasing Only).
     */
    public function cancelEnrollment(Request $request, int $enrollmentId)
    {
        $user = Auth::user();
        $enrollment = ManagementEnrollment::findOrFail($enrollmentId);

        if ($enrollment->user_id !== $user->id && ! $user->hasRole('admin')) {
            abort(403, 'Unauthorized access to this management enrollment.');
        }

        $validated = $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        try {
            $this->managementService->cancelEnrollment($enrollment, $user, $validated['reason'] ?? null);

            return redirect()->route('dashboard.owner')
                ->with('success', 'Property Management cancelled. Your property is now in standard "Leasing Only" mode with zero management charges.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
