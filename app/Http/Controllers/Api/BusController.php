<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bus;

class BusController extends Controller
{
   public function index()
   {
       $buses = Bus::latest()->get();
       return response()->json(['message' => 'Buses récupérés avec succès', 'data' => $buses], 200);
   }

   public function store(Request $request)
   {
       $validatedData = $request->validate([
           'matricule' => 'required|string|unique:buses',
           'photo' => ['required', 'mimes:jpg,png,gif', 'max:2048'], // 2048 KB = 2 MB
           'brand' => 'required|string',
           'model' => 'required|string',
           'seat' => 'required|integer',
           'status' => 'required|in:actif,inactif',
       ]);

       if ($request->hasFile('photo')) {
           $file = $request->file('photo');
           $filename = time() . $file->getClientOriginalName();
           $file->move(public_path('images'), $filename);
           $validatedData['photo'] = 'images/' . $filename;
       }

       $bus = Bus::create($validatedData);
       return response()->json(['message' => 'Bus créé avec succès', 'data' => $bus], 201);
   }

   public function show(Bus $bus)
   {
       return response()->json(['message' => 'Bus récupéré avec succès', 'data' => $bus], 200);
   }

   public function update(Request $request, Bus $bus)
   {
       $bus->update($request->all());
       return response()->json(['message' => 'Bus mis à jour avec succès', 'data' => $bus], 200);
   }

   public function destroy(Bus $bus)
   {
       $bus->delete();
       return response()->json(['message' => 'Bus supprimé avec succès'], 204);
   }

   public function check(Request $request)
   {
       $bus = Bus::where('matricule', $request->matricule)
                  ->where('brand', $request->brand)
                  ->where('model', $request->model)
                  ->where('seat', $request->seat)
                  ->first();

       if ($bus) {
           return response()->json(['message' => 'Bus trouvé', 'data' => $bus], 200);
       } else {
           return response()->json(['message' => 'Bus non trouvé'], 404);
       }
   }
}
