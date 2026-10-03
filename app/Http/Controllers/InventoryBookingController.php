<?php

namespace App\Http\Controllers;

use App\Models\CoworkingBooking;
use App\Models\CoworkingInventory;
use App\Models\PgBed;
use App\Models\PgBooking;
use App\Models\Property;
use App\Services\CoworkingBookingService;
use App\Services\PgBookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryBookingController extends Controller
{
    public function __construct(
        protected PgBookingService $pgService,
        protected CoworkingBookingService $coworkingService
    ) {}

    /**
     * View PG property room and bed selection matrix.
     */
    public function showPgProperty(int $propertyId)
    {
        $property = Property::with(['pgRooms.beds'])->findOrFail($propertyId);
        $user = Auth::user();

        return view('inventory.pg-show', compact('property', 'user'));
    }

    /**
     * Place temporary hold on a PG bed.
     */
    public function holdPgBed(Request $request, int $bedId)
    {
        $user = Auth::user();
        $bed = PgBed::findOrFail($bedId);

        try {
            $heldBed = $this->pgService->holdBed($bed, $user);

            return back()->with('success', "Bed {$heldBed->bed_code} held for 15 minutes. Please complete booking.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Book PG bed.
     */
    public function bookPgBed(Request $request, int $bedId)
    {
        $user = Auth::user();
        $bed = PgBed::findOrFail($bedId);

        $validated = $request->validate([
            'check_in_date' => 'required|date|after_or_equal:today',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $booking = $this->pgService->bookBed($bed, $user, $validated);

            return redirect()->route('dashboard.tenant')
                ->with('success', "PG Bed {$bed->bed_code} booked successfully! Booking Ref: {$booking->booking_number}");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Cancel PG Booking.
     */
    public function cancelPgBooking(Request $request, int $bookingId)
    {
        $user = Auth::user();
        $booking = PgBooking::with('property')->findOrFail($bookingId);

        $validated = $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        try {
            $this->pgService->cancelBooking($booking, $user, $validated['reason'] ?? null);

            return back()->with('success', 'PG booking cancelled successfully. Bed released to inventory.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * View Coworking space desk & cabin inventory.
     */
    public function showCoworkingProperty(int $propertyId)
    {
        $property = Property::with(['coworkingInventories'])->findOrFail($propertyId);
        $user = Auth::user();

        return view('inventory.coworking-show', compact('property', 'user'));
    }

    /**
     * Book Coworking inventory (hot desk, cabin, meeting room).
     */
    public function bookCoworking(Request $request, int $inventoryId)
    {
        $user = Auth::user();
        $inventory = CoworkingInventory::findOrFail($inventoryId);

        $validated = $request->validate([
            'booking_type' => 'required|string|in:hourly,daily,monthly',
            'start_datetime' => 'required|date|after_or_equal:now',
            'end_datetime' => 'required|date|after:start_datetime',
            'duration_units' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $booking = $this->coworkingService->bookInventory($inventory, $user, $validated);

            return redirect()->route('dashboard.tenant')
                ->with('success', "Coworking space {$inventory->inventory_code} reserved successfully! Booking Ref: {$booking->booking_number}");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Cancel Coworking booking.
     */
    public function cancelCoworkingBooking(Request $request, int $bookingId)
    {
        $user = Auth::user();
        $booking = CoworkingBooking::with('property')->findOrFail($bookingId);

        $validated = $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        try {
            $this->coworkingService->cancelBooking($booking, $user, $validated['reason'] ?? null);

            return back()->with('success', 'Coworking booking cancelled successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
