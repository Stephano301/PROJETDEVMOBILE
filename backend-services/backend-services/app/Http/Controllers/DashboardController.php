<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Provider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function providerDashboard()
    {
        $provider = Auth::user()->provider;

        $bookings = Booking::with(['user', 'service'])
            ->where('provider_id', $provider->id)
            ->orderBy('booking_date')
            ->get()
            ->map(function ($booking) {
                return [
                    'id'    => $booking->id,
                    'title' => $booking->service->name
                              . ' — '
                              . $booking->user->name,
                    'start' => $booking->booking_date->format('Y-m-d')
                              . 'T'
                              . $booking->booking_time,
                    'color' => match($booking->status) {
                        'confirmed'   => '#1565C0',
                        'pending'     => '#FF6D00',
                        'in_progress' => '#2E7D32',
                        'completed'   => '#757575',
                        'cancelled'   => '#C62828',
                        default       => '#1565C0',
                    },
                    'status'  => $booking->status,
                    'address' => $booking->address,
                ];
            });

        $stats = [
            'total_bookings'   => Booking::where('provider_id', $provider->id)->count(),
            'pending'          => Booking::where('provider_id', $provider->id)
                                    ->where('status', 'pending')->count(),
            'completed'        => Booking::where('provider_id', $provider->id)
                                    ->where('status', 'completed')->count(),
            'rating'           => $provider->rating,
        ];

        return view('dashboard.provider', compact('bookings', 'stats', 'provider'));
    }
}