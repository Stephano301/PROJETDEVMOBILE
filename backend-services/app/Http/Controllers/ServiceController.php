<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::where('is_active', true);

        // Filtre par catégorie si fourni
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        $services = $query->orderBy('name')->get();

        return response()->json([
            'success'  => true,
            'services' => $services,
        ]);
    }

    public function show($id)
    {
        $service = Service::with('providers.user')->findOrFail($id);

        return response()->json([
            'success' => true,
            'service' => $service,
        ]);
    }
}