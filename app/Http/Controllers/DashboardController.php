<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\Lead;
use App\Models\LeadTask;
use App\Models\ManagementEnrollment;
use App\Models\Promotion;
use App\Models\Property;
use App\Models\RentLedger;
use App\Models\ServiceQuote;
use App\Models\ServiceRequest;
use App\Models\TenantApplication;
use App\Models\TenantScreening;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Role-to-route mapper.
     */
    protected array $roleRoutes = [
        'owner' => 'dashboard.owner',
        'tenant' => 'dashboard.tenant',
        'broker' => 'dashboard.broker',
        'builder' => 'dashboard.builder',
        'pg_operator' => 'dashboard.pg-operator',
        'coliving_operator' => 'dashboard.coliving-operator',
        'coworking_operator' => 'dashboard.coworking-operator',
        'vendor' => 'dashboard.vendor',
        'buyer' => 'dashboard.buyer',
        'seller' => 'dashboard.seller',
    ];

    /**
     * Unified Dashboard Entry Point.
     * If user has 1 active role: redirect to role workspace.
     * If user has multiple roles: show Housing365 Persona / Workspace Selector.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        $activeRoles = $user->activeRoles();

        // If user has no active roles, assign default tenant
        if ($activeRoles->isEmpty()) {
            $user->assignRole('tenant');
            $activeRoles = $user->activeRoles();
        }

        // If explicit role requested via query param and user possesses it, switch and redirect
        if ($request->has('role') && $user->hasRole($request->query('role'))) {
            $role = $request->query('role');
            $user->switchRole($role);
            $targetRoute = $this->roleRoutes[$role] ?? 'dashboard.tenant';

            return redirect()->route($targetRoute);
        }

        // If exactly 1 active role, redirect directly
        if ($activeRoles->count() === 1) {
            $singleRole = $activeRoles->first()->name;
            $user->switchRole($singleRole);
            $targetRoute = $this->roleRoutes[$singleRole] ?? 'dashboard.tenant';

            return redirect()->route($targetRoute);
        }

        // If multiple roles, show the Housing365 Persona Workspace Selector
        return view('dashboard.selector', [
            'user' => $user,
            'activeRoles' => $activeRoles,
            'currentRole' => $user->currentRole(),
            'roleRoutes' => $this->roleRoutes,
        ]);
    }

    /**
     * Switch user's active workspace role.
     */
    public function switchRole(Request $request, string $role)
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        if (! $user->hasRole($role)) {
            abort(403, "You do not have access to the '{$role}' workspace.");
        }

        $user->switchRole($role);
        $targetRoute = $this->roleRoutes[$role] ?? 'dashboard';

        return redirect()->route($targetRoute)->with('success', 'Switched to '.ucfirst(str_replace('_', ' ', $role)).' workspace.');
    }

    /**
     * Owner Workspace Foundation.
     */
    public function owner()
    {
        $user = Auth::user();

        // Real properties query based on user's identity
        $cleanPhone = $user->phone ? preg_replace('/[^0-9]/', '', $user->phone) : null;
        $properties = Property::query()
            ->when($cleanPhone, function ($query, $phone) use ($user) {
                $query->where(function ($q) use ($phone, $user) {
                    $q->where('seller_phone', 'like', "%{$phone}%")
                        ->orWhere('seller_email', $user->email);
                });
            }, function ($query) use ($user) {
                $query->where('seller_email', $user->email);
            })
            ->latest()
            ->get();

        $inquiriesCount = Inquiry::whereIn('property_id', $properties->pluck('id'))->count();

        // Housing365 Care requests for owner's properties
        $careRequests = ServiceRequest::with('category')
            ->where(function ($q) use ($user, $properties) {
                $q->where('user_id', $user->id)
                    ->orWhereIn('property_id', $properties->pluck('id'));
            })
            ->latest()
            ->take(5)
            ->get();

        // Property Management Enrollments for owner's properties
        $enrollments = ManagementEnrollment::with('plan')
            ->whereIn('property_id', $properties->pluck('id'))
            ->get()
            ->keyBy('property_id');

        $applicantsCount = TenantApplication::whereIn('property_id', $properties->pluck('id'))->count();
        $rentLedgersCount = RentLedger::where('owner_id', $user->id)->count();

        // Phase 9 Leads for Owner
        $leads = Lead::whereIn('property_id', $properties->pluck('id'))
            ->orWhere('assigned_user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();
        $leadsCount = Lead::whereIn('property_id', $properties->pluck('id'))->count();
        $activePromotions = Promotion::where('user_id', $user->id)->where('is_active', true)->count();

        return view('dashboard.owner', [
            'user' => $user,
            'properties' => $properties,
            'inquiriesCount' => $inquiriesCount,
            'careRequests' => $careRequests,
            'enrollments' => $enrollments,
            'applicantsCount' => $applicantsCount,
            'rentLedgersCount' => $rentLedgersCount,
            'leads' => $leads,
            'leadsCount' => $leadsCount,
            'activePromotions' => $activePromotions,
            'activeRoles' => $user->activeRoles(),
            'currentRole' => 'owner',
        ]);
    }

    /**
     * Tenant Workspace Foundation.
     */
    public function tenant()
    {
        $user = Auth::user();

        // User's contact unlocks, inquiries, and maintenance requests
        $contactCredits = $user->availableContactCredits();
        $unlockedCount = $user->contactUnlocks()->count();
        $inquiries = Inquiry::where('email', $user->email)->latest()->take(5)->get();
        $maintenanceRequests = ServiceRequest::with('category')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $rentInvoicesCount = RentLedger::where('tenant_id', $user->id)->where('status', '!=', 'PAID')->count();
        $screenings = TenantScreening::where('tenant_id', $user->id)->latest()->take(3)->get();

        return view('dashboard.tenant', [
            'user' => $user,
            'contactCredits' => $contactCredits,
            'unlockedCount' => $unlockedCount,
            'inquiries' => $inquiries,
            'maintenanceRequests' => $maintenanceRequests,
            'rentInvoicesCount' => $rentInvoicesCount,
            'screenings' => $screenings,
            'activeRoles' => $user->activeRoles(),
            'currentRole' => 'tenant',
        ]);
    }

    /**
     * Broker Workspace Foundation.
     */
    public function broker()
    {
        $user = Auth::user();

        $leads = Lead::where('assigned_user_id', $user->id)->latest()->paginate(10);
        $totalLeads = Lead::where('assigned_user_id', $user->id)->count();
        $convertedLeads = Lead::where('assigned_user_id', $user->id)->where('status', 'CONVERTED')->count();
        $pendingTasks = LeadTask::where('assigned_to_user_id', $user->id)->where('status', 'pending')->count();

        return view('dashboard.broker', [
            'user' => $user,
            'leads' => $leads,
            'totalLeads' => $totalLeads,
            'convertedLeads' => $convertedLeads,
            'pendingTasks' => $pendingTasks,
            'activeRoles' => $user->activeRoles(),
            'currentRole' => 'broker',
        ]);
    }

    /**
     * Builder Workspace Foundation.
     */
    public function builder()
    {
        $user = Auth::user();

        $leads = Lead::where('assigned_user_id', $user->id)
            ->orWhere('lead_type', 'builder_project')
            ->latest()
            ->paginate(10);
        $siteVisits = Lead::where('status', 'VIEWING_SCHEDULED')->count();
        $promotions = Promotion::where('user_id', $user->id)->where('is_active', true)->count();

        return view('dashboard.builder', [
            'user' => $user,
            'leads' => $leads,
            'siteVisits' => $siteVisits,
            'promotions' => $promotions,
            'activeRoles' => $user->activeRoles(),
            'currentRole' => 'builder',
        ]);
    }

    /**
     * PG Operator Workspace Foundation.
     */
    public function pgOperator()
    {
        $user = Auth::user();

        $leads = Lead::where('lead_type', 'pg')
            ->orWhere('assigned_user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('dashboard.pg-operator', [
            'user' => $user,
            'leads' => $leads,
            'activeRoles' => $user->activeRoles(),
            'currentRole' => 'pg_operator',
        ]);
    }

    /**
     * Co-living Operator Workspace Foundation.
     */
    public function colivingOperator()
    {
        $user = Auth::user();

        $leads = Lead::where('lead_type', 'coliving')
            ->orWhere('assigned_user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('dashboard.coliving-operator', [
            'user' => $user,
            'leads' => $leads,
            'activeRoles' => $user->activeRoles(),
            'currentRole' => 'coliving_operator',
        ]);
    }

    /**
     * Coworking Operator Workspace Foundation.
     */
    public function coworkingOperator()
    {
        $user = Auth::user();

        $leads = Lead::where('lead_type', 'coworking')
            ->orWhere('assigned_user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('dashboard.coworking-operator', [
            'user' => $user,
            'leads' => $leads,
            'activeRoles' => $user->activeRoles(),
            'currentRole' => 'coworking_operator',
        ]);
    }

    /**
     * Vendor Workspace Foundation.
     */
    public function vendor()
    {
        $user = Auth::user();
        $profile = $user->vendorProfile;

        $assignedRequests = ServiceRequest::with(['category', 'property', 'latestQuote', 'workOrder'])
            ->where('assigned_vendor_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $workOrders = WorkOrder::with(['serviceRequest.category', 'property', 'quote'])
            ->where('vendor_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $quotesCount = ServiceQuote::where('vendor_id', $user->id)->count();
        $acceptedQuotesCount = ServiceQuote::where('vendor_id', $user->id)
            ->where('status', ServiceQuote::STATUS_APPROVED)
            ->count();
        $completedJobsCount = WorkOrder::where('vendor_id', $user->id)
            ->whereIn('status', [WorkOrder::STATUS_QC_PENDING, WorkOrder::STATUS_QC_COMPLETED])
            ->count();

        return view('dashboard.vendor', [
            'user' => $user,
            'profile' => $profile,
            'assignedRequests' => $assignedRequests,
            'workOrders' => $workOrders,
            'quotesCount' => $quotesCount,
            'acceptedQuotesCount' => $acceptedQuotesCount,
            'completedJobsCount' => $completedJobsCount,
            'activeRoles' => $user->activeRoles(),
            'currentRole' => 'vendor',
        ]);
    }

    /**
     * Buyer Workspace Foundation.
     */
    public function buyer()
    {
        $user = Auth::user();

        return view('dashboard.buyer', [
            'user' => $user,
            'activeRoles' => $user->activeRoles(),
            'currentRole' => 'buyer',
        ]);
    }

    /**
     * Seller Workspace Foundation.
     */
    public function seller()
    {
        $user = Auth::user();

        return view('dashboard.seller', [
            'user' => $user,
            'activeRoles' => $user->activeRoles(),
            'currentRole' => 'seller',
        ]);
    }
}
