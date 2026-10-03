<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\LeadTask;
use App\Models\User;
use App\Services\LeadManagementService;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadManagementController extends Controller
{
    public function __construct(
        protected LeadManagementService $leadService,
        protected NotificationService $notificationService
    ) {}

    /**
     * List leads with role-based scoping and filtering.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Lead::with(['property', 'assignedUser', 'statusHistories', 'activities']);

        // Multi-role Server-side authorization scoping
        if (! $user->isAdmin()) {
            $query->where(function ($q) use ($user) {
                $q->where('assigned_user_id', $user->id)
                    ->orWhere('user_id', $user->id)
                    ->orWhereHas('property', function ($propQuery) use ($user) {
                        $propQuery->where('user_id', $user->id);
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('lead_type')) {
            $query->where('lead_type', $request->lead_type);
        }

        $leads = $query->latest()->paginate(15);

        return view('crm.leads.index', compact('leads'));
    }

    /**
     * Show Lead Details & Timeline.
     */
    public function show(Lead $lead)
    {
        $this->authorizeLeadAccess($lead);

        $lead->load(['property', 'assignedUser', 'assignments.assignedUser', 'statusHistories.changedBy', 'activities.user', 'notes.user', 'tasks.assignedTo']);

        return view('crm.leads.show', compact('lead'));
    }

    /**
     * Update Lead Status.
     */
    public function updateStatus(Request $request, Lead $lead)
    {
        $this->authorizeLeadAccess($lead);

        $validated = $request->validate([
            'status' => 'required|string|in:NEW,ASSIGNED,CONTACTED,QUALIFIED,VIEWING_REQUESTED,VIEWING_SCHEDULED,VIEWING_COMPLETED,APPLICATION_STARTED,APPLICATION_SUBMITTED,CONVERTED,LOST,CLOSED',
            'reason' => 'nullable|string|max:500',
        ]);

        $this->leadService->updateStatus($lead, $validated['status'], Auth::user(), $validated['reason'] ?? null);

        // Notify assigned user if actor is different
        if ($lead->assignedUser && $lead->assignedUser->id !== Auth::id()) {
            $this->notificationService->send(
                $lead->assignedUser,
                'lead_status_updated',
                "Lead Status Updated: {$lead->name}",
                "Status changed to {$validated['status']}"
            );
        }

        return back()->with('success', "Lead status updated to {$validated['status']}.");
    }

    /**
     * Reassign Lead.
     */
    public function assign(Request $request, Lead $lead)
    {
        $this->authorizeLeadAccess($lead);

        $validated = $request->validate([
            'assigned_user_id' => 'required|exists:users,id',
            'reason' => 'nullable|string|max:500',
        ]);

        $targetUser = User::findOrFail($validated['assigned_user_id']);
        $this->leadService->assignLead($lead, $targetUser, Auth::user(), $validated['reason'] ?? null);

        $this->notificationService->send(
            $targetUser,
            'lead_assigned',
            "New Lead Assigned: {$lead->name}",
            'You have been assigned a lead for: '.($lead->property?->title ?? 'General Inquiry')
        );

        return back()->with('success', "Lead assigned to {$targetUser->name}.");
    }

    /**
     * Add Note.
     */
    public function addNote(Request $request, Lead $lead)
    {
        $this->authorizeLeadAccess($lead);

        $validated = $request->validate([
            'content' => 'required|string|max:2000',
            'is_private' => 'nullable|boolean',
        ]);

        $this->leadService->addNote($lead, Auth::user(), $validated['content'], (bool) ($validated['is_private'] ?? false));

        return back()->with('success', 'Note added successfully.');
    }

    /**
     * Create Task / Follow-up.
     */
    public function addTask(Request $request, Lead $lead)
    {
        $this->authorizeLeadAccess($lead);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'task_type' => 'required|string|in:call,email,visit,verification,document_collection,general',
            'due_at' => 'nullable|date',
            'priority' => 'required|string|in:low,medium,high,urgent',
            'notes' => 'nullable|string|max:1000',
            'assigned_to_user_id' => 'nullable|exists:users,id',
        ]);

        $task = LeadTask::create([
            'lead_id' => $lead->id,
            'property_id' => $lead->property_id,
            'assigned_to_user_id' => $validated['assigned_to_user_id'] ?? Auth::id(),
            'created_by_user_id' => Auth::id(),
            'title' => $validated['title'],
            'task_type' => $validated['task_type'],
            'due_at' => ! empty($validated['due_at']) ? Carbon::parse($validated['due_at']) : Carbon::now()->addDay(),
            'status' => 'pending',
            'priority' => $validated['priority'],
            'notes' => $validated['notes'] ?? null,
        ]);

        LeadActivity::create([
            'lead_id' => $lead->id,
            'user_id' => Auth::id(),
            'activity_type' => 'followup',
            'title' => "Follow-up Created: {$task->title}",
            'description' => "Task set for {$task->due_at?->format('d M Y, h:i A')}",
            'metadata' => ['task_id' => $task->id],
        ]);

        return back()->with('success', 'Follow-up task scheduled successfully.');
    }

    /**
     * Server-side authorization check.
     */
    protected function authorizeLeadAccess(Lead $lead): void
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return;
        }

        $isAssigned = $lead->assigned_user_id === $user->id;
        $isCreator = $lead->user_id === $user->id;
        $isPropertyOwner = $lead->property && $lead->property->user_id === $user->id;

        if (! $isAssigned && ! $isCreator && ! $isPropertyOwner) {
            abort(403, 'Unauthorized access to lead.');
        }
    }
}
