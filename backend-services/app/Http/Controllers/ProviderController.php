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

        if ($request->has('service_id')) {
            $query->whereHas('services', function ($q) use ($request) {
                $q->where('services.id', $request->service_id);
            });
        }

        $providers = $query->orderByDesc('rating')->get();

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

    public function timeSlots(Request $request, $id)
    {
        $provider = Provider::with('services')->findOrFail($id);

        $date = $request->query('date', now()->toDateString());

        $serviceId = $provider->services->first()?->id;

        $slots = $provider->timeSlots()
            ->whereDate('date', $date)
            ->orderBy('start_time')
            ->get()
            ->map(function($slot) use ($serviceId) {
                return [
                    'id'          => $slot->id,
                    'provider_id' => $slot->provider_id,
                    'service_id'  => $serviceId,
                    'date'        => $slot->date,
                    'start_time'  => $slot->start_time,
                    'end_time'    => $slot->end_time,
                    'is_booked'   => $slot->is_booked,
                ];
            });

        return response()->json([
            'success'    => true,
            'time_slots' => $slots,
        ]);
    }
}