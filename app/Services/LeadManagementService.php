<?php

namespace App\Services;

use App\Models\Inquiry;
use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\LeadAssignment;
use App\Models\LeadRoutingRule;
use App\Models\LeadStatusHistory;
use App\Models\Property;
use App\Models\SlaRule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LeadManagementService
{
    /**
     * Ingest lead from inquiry or direct data.
     */
    public function ingestFromInquiry(Inquiry $inquiry, array $context = []): Lead
    {
        return DB::transaction(function () use ($inquiry, $context) {
            $property = $inquiry->property ?? ($inquiry->property_id ? Property::find($inquiry->property_id) : null);

            $leadType = $this->determineLeadType($inquiry, $property);
            $priority = $context['priority'] ?? 'normal';

            // Calculate SLA deadline
            $slaDeadline = $this->calculateSlaDeadline($leadType, $priority);

            $lead = Lead::create([
                'inquiry_id' => $inquiry->id,
                'user_id' => auth()->id(),
                'property_id' => $inquiry->property_id,
                'contact_name' => $inquiry->name,
                'contact_phone' => $inquiry->phone,
                'contact_email' => $inquiry->email ?? 'inquiry@housing365.in',
                'name' => $inquiry->name,
                'phone' => $inquiry->phone,
                'email' => $inquiry->email ?? 'inquiry@housing365.in',
                'lead_type' => $leadType,
                'source' => $context['source'] ?? 'marketplace_inquiry',
                'source_channel' => $context['source_channel'] ?? 'web',
                'status' => Lead::STATUS_NEW,
                'priority' => $priority,
                'budget_min' => $context['budget_min'] ?? null,
                'budget_max' => $context['budget_max'] ?? null,
                'city' => $property?->city ?? $context['city'] ?? null,
                'locality' => $property?->locality ?? $context['locality'] ?? null,
                'preferred_city' => $property?->city ?? $context['city'] ?? null,
                'preferred_locality' => $property?->locality ?? $context['locality'] ?? null,
                'notes' => $inquiry->message,
                'utm_data' => $context['utm_data'] ?? null,
                'sla_deadline_at' => $slaDeadline,
                'sla_status' => 'pending',
            ]);

            // Create initial status history
            LeadStatusHistory::create([
                'lead_id' => $lead->id,
                'from_status' => null,
                'to_status' => Lead::STATUS_NEW,
                'changed_by_id' => auth()->id(),
                'changed_by_user_id' => auth()->id(),
                'reason' => 'Inquiry submitted on marketplace',
            ]);

            // Create activity
            LeadActivity::create([
                'lead_id' => $lead->id,
                'user_id' => $inquiry->user_id,
                'activity_type' => 'inquiry_created',
                'title' => 'New Lead Ingested',
                'description' => 'Lead created from property enquiry: '.($property ? $property->title : 'General'),
                'metadata' => ['inquiry_id' => $inquiry->id],
            ]);

            // Route lead automatically
            $this->routeLead($lead, $property);

            return $lead->fresh(['assignments', 'statusHistories', 'activities']);
        });
    }

    /**
     * Create direct lead (e.g. Care, Property Management, Land, Builder Project).
     */
    public function createLead(array $data, ?User $createdBy = null): Lead
    {
        return DB::transaction(function () use ($data, $createdBy) {
            $property = ! empty($data['property_id']) ? Property::find($data['property_id']) : null;
            $leadType = $data['lead_type'] ?? 'residential_rental';
            $priority = $data['priority'] ?? 'normal';

            $slaDeadline = $this->calculateSlaDeadline($leadType, $priority);

            $lead = Lead::create([
                'user_id' => $data['user_id'] ?? $createdBy?->id,
                'property_id' => $property?->id,
                'organization_id' => $data['organization_id'] ?? null,
                'contact_name' => $data['name'] ?? $data['contact_name'] ?? 'Direct Contact',
                'contact_phone' => $data['phone'] ?? $data['contact_phone'] ?? null,
                'contact_email' => $data['email'] ?? $data['contact_email'] ?? 'lead@housing365.in',
                'name' => $data['name'] ?? $data['contact_name'] ?? 'Direct Contact',
                'phone' => $data['phone'] ?? $data['contact_phone'] ?? null,
                'email' => $data['email'] ?? $data['contact_email'] ?? 'lead@housing365.in',
                'lead_type' => $leadType,
                'source' => $data['source'] ?? 'direct',
                'source_channel' => $data['source_channel'] ?? 'web',
                'status' => Lead::STATUS_NEW,
                'priority' => $priority,
                'budget_min' => $data['budget_min'] ?? null,
                'budget_max' => $data['budget_max'] ?? null,
                'city' => $data['preferred_city'] ?? $property?->city,
                'locality' => $data['preferred_locality'] ?? $property?->locality,
                'preferred_city' => $data['preferred_city'] ?? $property?->city,
                'preferred_locality' => $data['preferred_locality'] ?? $property?->locality,
                'notes' => $data['notes'] ?? null,
                'utm_data' => $data['utm_data'] ?? null,
                'sla_deadline_at' => $slaDeadline,
                'sla_status' => 'pending',
            ]);

            LeadStatusHistory::create([
                'lead_id' => $lead->id,
                'from_status' => null,
                'to_status' => Lead::STATUS_NEW,
                'changed_by_id' => $createdBy?->id,
                'changed_by_user_id' => $createdBy?->id,
                'reason' => 'Direct lead creation',
            ]);

            LeadActivity::create([
                'lead_id' => $lead->id,
                'user_id' => $createdBy?->id,
                'activity_type' => 'lead_created',
                'title' => 'Direct Lead Created',
                'description' => $lead->notes ?? 'Lead created directly.',
                'metadata' => ['lead_type' => $leadType],
            ]);

            $this->routeLead($lead, $property);

            return $lead->fresh(['assignments', 'statusHistories', 'activities']);
        });
    }

    /**
     * Transition Lead Status with Immutable Audit Trail.
     */
    public function updateStatus(Lead $lead, string $newStatus, ?User $actor = null, ?string $reason = null): Lead
    {
        $oldStatus = $lead->status;
        if ($oldStatus === $newStatus) {
            return $lead;
        }

        return DB::transaction(function () use ($lead, $oldStatus, $newStatus, $actor, $reason) {
            $now = Carbon::now();

            $updates = ['status' => $newStatus];

            // Mark first response if moving from NEW to CONTACTED/QUALIFIED/ASSIGNED
            if (empty($lead->first_response_at) && in_array($newStatus, [Lead::STATUS_CONTACTED, Lead::STATUS_QUALIFIED, Lead::STATUS_VIEWING_SCHEDULED])) {
                $updates['first_response_at'] = $now;
                if ($lead->sla_deadline_at && $now->greaterThan($lead->sla_deadline_at)) {
                    $updates['sla_status'] = 'breached';
                    $updates['breached_at'] = $now;
                } else {
                    $updates['sla_status'] = 'achieved';
                }
            }

            $lead->update($updates);

            // Audit history
            LeadStatusHistory::create([
                'lead_id' => $lead->id,
                'from_status' => $oldStatus,
                'to_status' => $newStatus,
                'changed_by_id' => $actor?->id,
                'changed_by_user_id' => $actor?->id,
                'reason' => $reason,
            ]);

            // Activity timeline
            LeadActivity::create([
                'lead_id' => $lead->id,
                'user_id' => $actor?->id,
                'activity_type' => 'status_change',
                'title' => "Status Changed: {$oldStatus} → {$newStatus}",
                'description' => $reason ?? 'Status updated by '.($actor ? $actor->name : 'System'),
                'metadata' => ['from' => $oldStatus, 'to' => $newStatus],
            ]);

            return $lead->fresh(['statusHistories', 'activities']);
        });
    }

    /**
     * Assign Lead to User/Organization with History.
     */
    public function assignLead(Lead $lead, ?User $assignedUser, ?User $assignedBy = null, ?string $reason = null): Lead
    {
        return DB::transaction(function () use ($lead, $assignedUser, $assignedBy, $reason) {
            $previousUser = $lead->assignedUser;

            $lead->update([
                'assigned_user_id' => $assignedUser?->id,
                'status' => ($lead->status === Lead::STATUS_NEW && $assignedUser) ? Lead::STATUS_ASSIGNED : $lead->status,
            ]);

            LeadAssignment::create([
                'lead_id' => $lead->id,
                'assigned_user_id' => $assignedUser?->id,
                'assigned_to_user_id' => $assignedUser?->id,
                'assigned_by_id' => $assignedBy?->id,
                'assigned_by_user_id' => $assignedBy?->id,
                'assignment_type' => $assignedBy ? 'manual' : 'auto_route',
                'notes' => $reason ?? ($assignedUser ? "Assigned to {$assignedUser->name}" : 'Unassigned'),
                'reason' => $reason ?? ($assignedUser ? "Assigned to {$assignedUser->name}" : 'Unassigned'),
            ]);

            LeadActivity::create([
                'lead_id' => $lead->id,
                'user_id' => $assignedBy?->id,
                'activity_type' => 'assignment',
                'title' => $assignedUser ? "Lead Assigned to {$assignedUser->name}" : 'Lead Unassigned',
                'description' => $reason ?? 'Assignment updated.',
                'metadata' => [
                    'previous_user_id' => $previousUser?->id,
                    'new_user_id' => $assignedUser?->id,
                ],
            ]);

            return $lead->fresh(['assignments', 'activities']);
        });
    }

    /**
     * Add Note to Lead.
     */
    public function addNote(Lead $lead, User $author, string $content, bool $isPrivate = false)
    {
        return DB::transaction(function () use ($lead, $author, $content, $isPrivate) {
            $note = $lead->notes()->create([
                'user_id' => $author->id,
                'content' => $content,
                'is_private' => $isPrivate,
            ]);

            LeadActivity::create([
                'lead_id' => $lead->id,
                'user_id' => $author->id,
                'activity_type' => 'note',
                'title' => 'Note Added',
                'description' => mb_substr($content, 0, 100).(mb_strlen($content) > 100 ? '...' : ''),
                'metadata' => ['note_id' => $note->id, 'is_private' => $isPrivate],
            ]);

            return $note;
        });
    }

    /**
     * Route Lead using Configurable Routing Rules.
     */
    public function routeLead(Lead $lead, ?Property $property = null): void
    {
        $city = $lead->preferred_city ?? $property?->city;
        $leadType = $lead->lead_type;

        // Find best matching active routing rule
        $rule = LeadRoutingRule::where('is_active', true)
            ->where(function ($q) use ($leadType) {
                $q->whereNull('lead_type')->orWhere('lead_type', $leadType);
            })
            ->where(function ($q) use ($city) {
                $q->whereNull('city')->orWhere('city', $city);
            })
            ->orderBy('priority', 'desc')
            ->first();

        if ($rule) {
            if ($rule->routing_method === 'listing_owner' && $property && $property->user) {
                $this->assignLead($lead, $property->user, null, "Auto-routed to property owner via rule: {$rule->name}");

                return;
            }

            if ($rule->routing_method === 'direct_user' && $rule->assignToUser) {
                $this->assignLead($lead, $rule->assignToUser, null, "Auto-routed to assigned user via rule: {$rule->name}");

                return;
            }
        }

        // Fallback: If property has owner, assign to property owner
        if ($property && $property->user) {
            $this->assignLead($lead, $property->user, null, 'Default fallback: Assigned to property owner');
        }
    }

    /**
     * Calculate SLA Deadline based on Rules.
     */
    public function calculateSlaDeadline(string $leadType, string $priority): Carbon
    {
        $slaRule = SlaRule::where('is_active', true)
            ->where(function ($q) use ($leadType) {
                $q->whereNull('lead_type')->orWhere('lead_type', $leadType);
            })
            ->where(function ($q) use ($priority) {
                $q->whereNull('priority')->orWhere('priority', $priority);
            })
            ->first();

        $minutes = $slaRule ? $slaRule->response_time_minutes : 120; // Default 2 hours

        return Carbon::now()->addMinutes($minutes);
    }

    /**
     * Determine Lead Type from Inquiry context.
     */
    protected function determineLeadType(Inquiry $inquiry, ?Property $property): string
    {
        if (! $property) {
            return 'general';
        }

        $purpose = strtolower($property->purpose ?? '');
        $type = strtolower($property->property_type ?? '');

        if ($purpose === 'sale' || $purpose === 'buy') {
            return 'property_sale';
        }
        if ($type === 'land' || $type === 'plot') {
            return 'land';
        }
        if ($type === 'commercial' || $type === 'office' || $type === 'retail') {
            return 'commercial';
        }
        if ($type === 'pg' || $type === 'hostel') {
            return 'pg';
        }
        if ($type === 'coliving') {
            return 'coliving';
        }
        if ($type === 'coworking') {
            return 'coworking';
        }

        return 'residential_rental';
    }
}
