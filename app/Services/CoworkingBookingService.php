<?php

namespace App\Services;

use App\Models\CoworkingBooking;
use App\Models\CoworkingInventory;
use App\Models\ServiceAuditLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class CoworkingBookingService
{
    /**
     * Check if a desk / cabin slot is free without overlapping reservations.
     */
    public function checkAvailability(CoworkingInventory $inventory, Carbon|string $start, Carbon|string $end): bool
    {
        if ($inventory->status !== CoworkingInventory::STATUS_AVAILABLE) {
            return false;
        }

        $startDate = Carbon::parse($start);
        $endDate = Carbon::parse($end);

        $overlappingCount = CoworkingBooking::where('coworking_inventory_id', $inventory->id)
            ->whereIn('status', [CoworkingBooking::STATUS_CONFIRMED, CoworkingBooking::STATUS_ACTIVE, CoworkingBooking::STATUS_HELD])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where('start_datetime', '<', $endDate)
                    ->where('end_datetime', '>', $startDate);
            })
            ->count();

        // If overlapping bookings reach or exceed capacity, it is not available
        return $overlappingCount < $inventory->capacity;
    }

    /**
     * Reserve and confirm coworking inventory.
     */
    public function bookInventory(CoworkingInventory $inventory, User $user, array $data): CoworkingBooking
    {
        $startDate = Carbon::parse($data['start_datetime']);
        $endDate = Carbon::parse($data['end_datetime']);

        if ($endDate->lte($startDate)) {
            throw new InvalidArgumentException('End time must be after start time.');
        }

        return DB::transaction(function () use ($inventory, $user, $data, $startDate, $endDate) {
            // Lock inventory row
            $lockedInventory = CoworkingInventory::where('id', $inventory->id)->lockForUpdate()->firstOrFail();

            if (! $this->checkAvailability($lockedInventory, $startDate, $endDate)) {
                throw new InvalidArgumentException('This coworking space is not available for the selected dates/times.');
            }

            $bookingType = $data['booking_type'] ?? 'daily';
            $duration = (int) ($data['duration_units'] ?? 1);

            $rate = match ($bookingType) {
                'hourly' => (float) ($lockedInventory->hourly_rate ?? 100),
                'monthly' => (float) ($lockedInventory->monthly_rate ?? 5000),
                default => (float) ($lockedInventory->daily_rate ?? 500),
            };

            $totalAmount = $rate * $duration;
            $bookingNumber = 'CWK-'.date('Ymd').'-'.strtoupper(substr(uniqid(), -6));

            $booking = CoworkingBooking::create([
                'booking_number' => $bookingNumber,
                'property_id' => $lockedInventory->property_id,
                'coworking_inventory_id' => $lockedInventory->id,
                'user_id' => $user->id,
                'booking_type' => $bookingType,
                'start_datetime' => $startDate,
                'end_datetime' => $endDate,
                'duration_units' => $duration,
                'total_amount' => $totalAmount,
                'status' => CoworkingBooking::STATUS_CONFIRMED,
                'notes' => $data['notes'] ?? null,
            ]);

            ServiceAuditLog::record($booking, 'coworking_booking_confirmed', $user, [
                'inventory_code' => $lockedInventory->inventory_code,
                'booking_type' => $bookingType,
                'total_amount' => $totalAmount,
            ]);

            return $booking;
        });
    }

    /**
     * Cancel coworking booking.
     */
    public function cancelBooking(CoworkingBooking $booking, User $actor, ?string $reason = null): CoworkingBooking
    {
        if ($booking->user_id !== $actor->id && ! $actor->hasRole('admin') && ! $booking->property->isOwnedBy($actor)) {
            throw new InvalidArgumentException('Unauthorized: You cannot cancel this booking.');
        }

        return DB::transaction(function () use ($booking, $actor, $reason) {
            $booking->update([
                'status' => CoworkingBooking::STATUS_CANCELLED,
                'notes' => $reason ? ($booking->notes.' | Cancel Reason: '.$reason) : $booking->notes,
            ]);

            ServiceAuditLog::record($booking, 'coworking_booking_cancelled', $actor, [
                'reason' => $reason,
            ]);

            return $booking->fresh();
        });
    }
}
