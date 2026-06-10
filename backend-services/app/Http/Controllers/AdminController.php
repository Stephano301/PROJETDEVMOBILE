<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Provider;
use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Stats globales
        $stats = [
            'total_users'     => User::count(),
            'total_providers' => Provider::count(),
            'total_bookings'  => Booking::count(),
            'total_revenue'   => Booking::where('status', 'completed')
                                    ->sum('total_price'),
        ];

        // Utilisateurs récents
        $recentUsers = User::with('roles')
            ->latest()
            ->take(5)
            ->get();

        // Réservations récentes
        $recentBookings = Booking::with([
            'user',
            'provider.user',
            'service'
        ])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentUsers',
            'recentBookings'
        ));
    }

    public function users()
    {
        $users = User::with('roles')->latest()->paginate(15);
        return view('admin.users', compact('users'));
    }

    public function providers()
    {
        $providers = Provider::with('user')->latest()->paginate(15);
        return view('admin.providers', compact('providers'));
    }

    public function bookings()
    {
        $bookings = Booking::with(['user', 'provider.user', 'service'])
            ->latest()
            ->paginate(15);
        return view('admin.bookings', compact('bookings'));
    }

    public function updateBookingStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => $request->status]);
        return back()->with('success', 'Statut mis à jour');
    }
}