<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\TimeSlot;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $bookings = Booking::with(['provider.user', 'service'])
            ->where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success'  => true,
            'bookings' => $bookings,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'provider_id'  => 'required|exists:providers,id',
            'service_id'   => 'required|exists:services,id',
            'booking_date' => 'required|date',
            'time_slot_id' => 'required|exists:time_slots,id',
            'booking_time' => 'required',
            'address'      => 'nullable|string',
        ]);

        // Vérifier que le créneau est disponible
        $slot = TimeSlot::findOrFail($request->time_slot_id);
        if ($slot->is_booked) {
            return response()->json([
                'success' => false,
                'message' => 'Ce créneau est déjà réservé',
            ], 422);
        }

        $booking = Booking::create([
            'user_id'      => $request->user()->id,
            'provider_id'  => $request->provider_id,
            'service_id'   => $request->service_id,
            'time_slot_id' => $request->time_slot_id,
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
            'address'      => $request->address ?? '',
            'notes'        => $request->notes ?? '',
            'status'       => 'pending',
            'total_price'  => 0,
        ]);

        // Marquer le créneau comme réservé
        $slot->update(['is_booked' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Réservation créée avec succès',
            'data'    => $booking,
        ], 201);
    }

    public function cancel(Request $request, $id)
    {
        $booking = Booking::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $booking->update(['status' => 'cancelled']);

        // Libérer le créneau
        if ($booking->time_slot_id) {
            TimeSlot::where('id', $booking->time_slot_id)
                ->update(['is_booked' => false]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Réservation annulée',
        ]);
    }
}