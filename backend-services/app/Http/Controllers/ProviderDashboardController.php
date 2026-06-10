<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderDashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = $request->user();
        $provider = Provider::where('user_id', $user->id)->firstOrFail();

        $bookings = Booking::where('provider_id', $provider->id)
            ->with(['service', 'user'])
            ->orderByDesc('created_at')
            ->get();

        $upcoming = $bookings->whereIn('status', ['pending', 'confirmed'])->count();
        $completed = $bookings->where('status', 'completed')->count();
        $cancelled = $bookings->where('status', 'cancelled')->count();
        $revenueMonth = $bookings->where('status', 'completed')
            ->sum('total_price');

        return response()->json([
            'success' => true,
            'data' => [
                'provider_name'   => $user->name,
                'rating'          => $provider->rating,
                'upcoming_count'  => $upcoming,
                'completed_count' => $completed,
                'cancelled_count' => $cancelled,
                'revenue_month'   => $revenueMonth,
                'bookings'        => $bookings->whereIn('status', ['pending', 'confirmed'])
                    ->values()
                    ->map(function ($b) {
                        return [
                            'id'           => $b->id,
                            'client_name'  => $b->user->name,
                            'service_name' => $b->service->name ?? 'Service',
                            'booking_date' => $b->booking_date,
                            'booking_time' => $b->booking_time,
                            'total_price'  => $b->total_price,
                            'status'       => $b->status,
                            'address'      => $b->address,
                        ];
                    }),
            ],
        ]);
    }

    public function acceptBooking(Request $request, $id)
    {
        $user = $request->user();
        $provider = Provider::where('user_id', $user->id)->firstOrFail();

        $booking = Booking::where('id', $id)
            ->where('provider_id', $provider->id)
            ->firstOrFail();

        $booking->update(['status' => 'confirmed']);

        return response()->json([
            'success' => true,
            'message' => 'Réservation acceptée',
        ]);
    }

    public function refuseBooking(Request $request, $id)
    {
        $user = $request->user();
        $provider = Provider::where('user_id', $user->id)->firstOrFail();

        $booking = Booking::where('id', $id)
            ->where('provider_id', $provider->id)
            ->firstOrFail();

        $booking->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Réservation refusée',
        ]);
    }
}