<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Provider;
use App\Models\User;
use App\Models\Service;
use App\Models\Review;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users'     => User::count(),
            'total_providers' => Provider::count(),
            'total_bookings'  => Booking::count(),
            'total_revenue'   => Booking::where('status', 'completed')->sum('total_price'),
        ];

        $recentUsers    = User::with('roles')->latest()->take(5)->get();
        $recentBookings = Booking::with(['user', 'provider.user', 'service'])
            ->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentBookings'));
    }

    public function users(Request $request)
    {
        $query = User::with('roles');
        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
        }
        $users = $query->latest()->paginate(15);
        return view('admin.users', compact('users'));
    }

    public function storeProvider(Request $request)
{
    $request->validate([
        'name'        => 'required|string|max:255',
        'email'       => 'required|email|unique:users,email',
        'password'    => 'required|min:8',
        'hourly_rate' => 'required|numeric|min:0',
    ]);

    $user = User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => bcrypt($request->password),
        'role'     => 'prestataire',
    ]);

    $provider = Provider::create([
        'user_id'      => $user->id,
        'phone'        => $request->phone ?? '',
        'city'         => $request->city ?? 'Antananarivo',
        'bio'          => $request->bio ?? '',
        'hourly_rate'  => $request->hourly_rate,
        'is_available' => true,
        'is_verified'  => true,
        'rating'       => 0,
        'reviews_count'=> 0,
    ]);

    if ($request->service_id) {
        $provider->services()->attach($request->service_id);
    }

    return back()->with('success', 'Prestataire ajouté avec succès');
}

    public function providers(Request $request)
    {
        $query = Provider::with(['user', 'services']);
        if ($request->search) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%");
            });
        }
        $stats = [
            'total'    => Provider::count(),
            'active'   => Provider::where('is_available', true)->count(),
            'pending'  => Provider::where('is_verified', false)->count(),
            'suspended'=> Provider::where('is_available', false)->count(),
        ];
        $providers = $query->latest()->paginate(15);
        return view('admin.providers', compact('providers', 'stats'));
    }

    public function services(Request $request)
    {
        $query = Service::query();
        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }
        $stats = [
            'total'    => Service::count(),
            'active'   => Service::where('is_active', true)->count(),
            'inactive' => Service::where('is_active', false)->count(),
        ];
        $services = $query->latest()->paginate(15);
        return view('admin.services', compact('services', 'stats'));
    }

    public function categories()
    {
        $categories = Service::select('category')
            ->selectRaw('count(*) as total')
            ->selectRaw('sum(case when is_active=1 then 1 else 0 end) as active')
            ->groupBy('category')
            ->paginate(15);

        $stats = [
            'total'    => $categories->total(),
            'active'   => Service::where('is_active', true)->distinct('category')->count('category'),
            'inactive' => 1,
        ];
        return view('admin.categories', compact('categories', 'stats'));
    }

    public function bookings(Request $request)
    {
        $query = Booking::with(['user', 'provider.user', 'service']);
        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        if ($request->search) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%");
            });
        }
        $stats = [
            'total'     => Booking::count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'pending'   => Booking::where('status', 'pending')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];
        $bookings = $query->latest()->paginate(15);
        return view('admin.bookings', compact('bookings', 'stats'));
    }

    public function storeService(Request $request)
{
    $request->validate([
        'name'        => 'required|string|max:255',
        'category'    => 'required|string|max:255',
        'description' => 'nullable|string',
        'base_price'  => 'required|numeric|min:0',
        'icon'        => 'nullable|string|max:255',
    ]);

    Service::create([
        'name'        => $request->name,
        'category'    => strtolower($request->category),
        'description' => $request->description ?? '',
        'base_price'  => $request->base_price,
        'icon'        => $request->icon ?? 'ic_cat_plumbing',
        'is_active'   => true,
    ]);

    return back()->with('success', 'Service ajouté avec succès');
}

public function storeBooking(Request $request)
{
    $request->validate([
        'user_id'      => 'required|exists:users,id',
        'provider_id'  => 'required|exists:providers,id',
        'service_id'   => 'required|exists:services,id',
        'booking_date' => 'required|date',
        'booking_time' => 'required',
        'address'      => 'nullable|string',
    ]);

    Booking::create([
        'user_id'      => $request->user_id,
        'provider_id'  => $request->provider_id,
        'service_id'   => $request->service_id,
        'booking_date' => $request->booking_date,
        'booking_time' => $request->booking_time,
        'address'      => $request->address ?? '',
        'notes'        => $request->notes ?? '',
        'status'       => 'pending',
        'total_price'  => 0,
    ]);

    return back()->with('success', 'Réservation ajoutée avec succès');
}

    public function payments(Request $request)
    {
        $query = Booking::with(['user', 'provider.user', 'service'])
            ->whereIn('status', ['completed', 'confirmed']);
        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        $stats = [
            'total_revenue' => Booking::where('status', 'completed')->sum('total_price'),
            'paid'          => Booking::where('status', 'completed')->sum('total_price'),
            'pending'       => Booking::where('status', 'confirmed')->sum('total_price'),
            'refunded'      => 0,
        ];
        $payments = $query->latest()->paginate(15);
        return view('admin.payments', compact('payments', 'stats'));
    }

    public function reviews(Request $request)
    {
        $query = Review::with(['user', 'provider.user', 'booking.service']);
        if ($request->search) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%");
            });
        }
        $stats = [
            'total'    => Review::count(),
            'published'=> Review::count(),
            'pending'  => 0,
            'reported' => 0,
        ];
        $reviews = $query->latest()->paginate(15);
        return view('admin.reviews', compact('reviews', 'stats'));
    }

    public function updateBookingStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => $request->status]);
        return back()->with('success', 'Statut mis à jour avec succès');
    }

    public function toggleProvider($id)
    {
        $provider = Provider::findOrFail($id);
        $provider->update(['is_available' => !$provider->is_available]);
        return back()->with('success', 'Statut prestataire mis à jour');
    }

    public function toggleService($id)
    {
        $service = Service::findOrFail($id);
        $service->update(['is_active' => !$service->is_active]);
        return back()->with('success', 'Statut service mis à jour');
    }
}