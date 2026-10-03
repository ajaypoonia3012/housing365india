<?php

namespace App\Services;

use App\Models\AnalyticsEvent;
use App\Models\Property;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    /**
     * Log privacy-safe event. Strictly no phone/email/PII leakage in metadata.
     */
    public function logEvent(string $eventType, ?Property $property = null, ?User $user = null, array $metadata = []): AnalyticsEvent
    {
        // Filter out any sensitive contact data
        unset($metadata['phone'], $metadata['mobile'], $metadata['email'], $metadata['owner_phone'], $metadata['seller_phone'], $metadata['seller_email']);

        return AnalyticsEvent::create([
            'event_name' => $eventType,
            'property_id' => $property?->id,
            'user_id' => $user?->id,
            'metadata' => $metadata,
            'source' => $metadata['source'] ?? 'web',
        ]);
    }

    /**
     * Get Aggregated Dashboard Analytics.
     */
    public function getAdminMetrics(): array
    {
        $totalListings = Property::count();
        $activeListings = Property::where('status', 'active')->count();

        $leadsCount = DB::table('leads')->count();
        $qualifiedLeads = DB::table('leads')->whereIn('status', ['QUALIFIED', 'VIEWING_SCHEDULED', 'CONVERTED'])->count();
        $convertedLeads = DB::table('leads')->where('status', 'CONVERTED')->count();
        $slaBreaches = DB::table('leads')->where('sla_status', 'breached')->count();

        $monetizationRevenue = DB::table('monetization_transactions')->where('status', 'successful')->sum('amount');
        $invoicesCount = DB::table('invoices')->where('payment_status', 'paid')->count();
        $promotionsActive = DB::table('promotions')->where('is_active', true)->where('ends_at', '>=', Carbon::now())->count();

        return [
            'total_listings' => $totalListings,
            'active_listings' => $activeListings,
            'total_leads' => $leadsCount,
            'qualified_leads' => $qualifiedLeads,
            'converted_leads' => $convertedLeads,
            'sla_breaches' => $slaBreaches,
            'monetization_revenue' => (float) $monetizationRevenue,
            'invoices_count' => $invoicesCount,
            'active_promotions' => $promotionsActive,
        ];
    }
}
