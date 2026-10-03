<?php

namespace App\Services;

use App\Models\PgBed;
use App\Models\PgBooking;
use App\Models\ServiceAuditLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class PgBookingService
{
    /**
     * Place a temporary transactional hold on a bed for checkout.
     */
    public function holdBed(PgBed $bed, User $tenant, int $holdMinutes = 15): PgBed
    {
        return DB::transaction(function () use ($bed, $tenant, $holdMinutes) {
            // Lock row for update to prevent concurrent double-hold
            $lockedBed = PgBed::where('id', $bed->id)->lockForUpdate()->firstOrFail();

            if (! $lockedBed->isAvailable()) {
                throw new InvalidArgumentException('This bed is not available for booking.');
            }

            $lockedBed->update([
                'status' => PgBed::STATUS_HELD,
                'held_until' => now()->addMinutes($holdMinutes),
                'held_by_user_id' => $tenant->id,
            ]);

            ServiceAuditLog::record($lockedBed, 'pg_bed_held', $tenant, [
                'held_until' => now()->addMinutes($holdMinutes)->toIso8601String(),
            ]);

            return $lockedBed->fresh();
        });
    }

    /**
     * Book and confirm a PG bed.
     * Prevents double-booking via database-level transaction lock.
     */
    public function bookBed(PgBed $bed, User $tenant, array $data): PgBooking
    {
        return DB::transaction(function () use ($bed, $tenant, $data) {
            $lockedBed = PgBed::where('id', $bed->id)->lockForUpdate()->firstOrFail();

            // Allow if AVAILABLE or HELD by the same user
            $isHeldBySameUser = $lockedBed->status === PgBed::STATUS_HELD && (int) $lockedBed->held_by_user_id === (int) $tenant->id;

            if (! $lockedBed->isAvailable() && ! $isHeldBySameUser) {
                throw new InvalidArgumentException('Selected PG bed is already reserved or occupied.');
            }

            $bookingNumber = 'PGB-'.date('Ymd').'-'.strtoupper(substr(uniqid(), -6));
            $room = $lockedBed->room;

            $booking = PgBooking::create([
                'booking_number' => $bookingNumber,
                'property_id' => $lockedBed->property_id,
                'pg_room_id' => $lockedBed->pg_room_id,
                'pg_bed_id' => $lockedBed->id,
                'user_id' => $tenant->id,
                'check_in_date' => $data['check_in_date'] ?? now()->addDay(),
                'monthly_rent' => $data['monthly_rent'] ?? $room->monthly_rent_per_bed,
                'deposit_amount' => $data['deposit_amount'] ?? ($room->deposit_per_bed ?? 0),
                'status' => PgBooking::STATUS_CONFIRMED,
                'notes' => $data['notes'] ?? null,
            ]);

            $lockedBed->update([
                'status' => PgBed::STATUS_RESERVED,
                'held_until' => null,
                'held_by_user_id' => null,
            ]);

            ServiceAuditLog::record($booking, 'pg_booking_confirmed', $tenant, [
                'bed_code' => $lockedBed->bed_code,
                'room_number' => $room->room_number,
                'booking_number' => $bookingNumber,
            ]);

            return $booking;
        });
    }

    /**
     * Cancel booking and release bed back to AVAILABLE.
     */
    public function cancelBooking(PgBooking $booking, User $actor, ?string $reason = null): PgBooking
    {
        if ($booking->user_id !== $actor->id && ! $actor->hasRole('admin') && ! $booking->property->isOwnedBy($actor)) {
            throw new InvalidArgumentException('Unauthorized: You cannot cancel this booking.');
        }

        return DB::transaction(function () use ($booking, $actor, $reason) {
            $booking->update([
                'status' => PgBooking::STATUS_CANCELLED,
                'notes' => $reason ? ($booking->notes.' | Cancel Reason: '.$reason) : $booking->notes,
            ]);

            // Release bed
            $bed = $booking->bed;
            if ($bed) {
                $bed->update([
                    'status' => PgBed::STATUS_AVAILABLE,
                    'held_until' => null,
                    'held_by_user_id' => null,
                ]);
            }

            ServiceAuditLog::record($booking, 'pg_booking_cancelled', $actor, [
                'reason' => $reason,
            ]);

            return $booking->fresh();
        });
    }
}
