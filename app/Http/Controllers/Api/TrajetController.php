<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trajet;

class TrajetController extends Controller
{
    public function index()
    {
        // $trajets = Trajet::all();
        $trajets = Trajet::with('route', 'bus')->get();
        return response()->json(['message' => 'Trajets récupérés avec succès', 'data' => $trajets], 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'refTrajet' => 'required|string',
            'start' => 'required|date',
            'end' => 'required|date',
            'active' => 'in:1,0',
            'route_id' => 'required|exists:routes,id',
            'bus_id' => 'required|exists:buses,id',
        ]);

        $trajet = Trajet::create($validatedData);
        return response()->json(['message' => 'Trajet créé avec succès', 'data' => $trajet], 201);
    }

    public function show(Trajet $trajet)
    {
        return response()->json(['message' => 'Trajet récupéré avec succès', 'data' => $trajet], 200);
    }

    public function update(Request $request, Trajet $trajet)
    {
        $validatedData = $request->validate([
            'refTrajet' => 'string',
            'start' => 'date',
            'end' => 'date',
            'active' => 'in:1,0',
            'route_id' => 'exists:routes,id',
            'bus_id' => 'exists:buses,id',
        ]);

        $trajet->update($validatedData);
        return response()->json(['message' => 'Trajet mis à jour avec succès', 'data' => $trajet], 200);
    }

    public function destroy(Trajet $trajet)
    {
        $trajet->delete();
        return response()->json(['message' => 'Trajet supprimé avec succès'], 204);
    }

    public function search(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $query = $request->input('query', '');

        $trajets = Trajet::query();

        if ($query) {
            $trajets->where('refTrajet', 'like', '%' . $query . '%')
                ->orWhere('active', 'like', '%' . $query . '%');
        }

        $trajets = $trajets->paginate($perPage);

        return response()->json(['message' => 'Résultats de recherche récupérés avec succès', 'data' => $trajets], 200);
    }
}

