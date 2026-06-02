<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function index(Request $request)
    {
        $query = Provider::with(['user', 'services'])
            ->where('is_available', true);

        // Filtre par service
        if ($request->has('service_id')) {
            $query->whereHas('services', function ($q) use ($request) {
                $q->where('services.id', $request->service_id);
            });
        }

        $providers = $query->orderByDesc('rating')->get();

        // Formater la réponse
        $data = $providers->map(function ($provider) {
            return [
                'id'             => $provider->id,
                'name'           => $provider->user->name,
                'email'          => $provider->user->email,
                'phone'          => $provider->phone,
                'city'           => $provider->city,
                'bio'            => $provider->bio,
                'photo'          => $provider->photo,
                'hourly_rate'    => $provider->hourly_rate,
                'rating'         => $provider->rating,
                'reviews_count'  => $provider->reviews_count,
                'is_available'   => $provider->is_available,
                'is_verified'    => $provider->is_verified,
                'services'       => $provider->services->map(fn($s) => [
                    'id'       => $s->id,
                    'name'     => $s->name,
                    'category' => $s->category,
                    'icon'     => $s->icon,
                ]),
            ];
        });

        return response()->json([
            'success'   => true,
            'providers' => $data,
        ]);
    }

    public function show($id)
    {
        $provider = Provider::with(['user', 'services', 'reviews.user'])
            ->findOrFail($id);

        return response()->json([
            'success'  => true,
            'provider' => [
                'id'            => $provider->id,
                'name'          => $provider->user->name,
                'email'         => $provider->user->email,
                'phone'         => $provider->phone,
                'city'          => $provider->city,
                'bio'           => $provider->bio,
                'photo'         => $provider->photo,
                'hourly_rate'   => $provider->hourly_rate,
                'rating'        => $provider->rating,
                'reviews_count' => $provider->reviews_count,
                'is_available'  => $provider->is_available,
                'is_verified'   => $provider->is_verified,
                'services'      => $provider->services,
                'reviews'       => $provider->reviews->map(fn($r) => [
                    'id'         => $r->id,
                    'rating'     => $r->rating,
                    'comment'    => $r->comment,
                    'client'     => $r->user->name,
                    'created_at' => $r->created_at->format('d/m/Y'),
                ]),
            ],
        ]);
    }

    public function timeSlots($id)
    {
        $provider = Provider::findOrFail($id);

        $slots = $provider->timeSlots()
            ->where('date', '>=', now()->toDateString())
            ->where('is_booked', false)
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return response()->json([
            'success'    => true,
            'time_slots' => $slots,
        ]);
    }
}