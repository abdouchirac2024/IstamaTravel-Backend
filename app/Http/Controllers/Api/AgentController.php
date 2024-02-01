<?php

namespace App\Http\Controllers\Api;

use App\Enums\TypeAgentEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\AgentStoreRequest;
use App\Http\Resources\AgentResource;
use App\Http\Resources\DriverResource;
use App\Models\Agent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
class AgentController extends Controller
{
    public function index()
    {
        $agents = Agent::query()->whereNotIn('type_id', [TypeAgentEnum::DRIVER->value])->get();

        return AgentResource::collection($agents);
    }

    public function drivers()
    {
        $agents = Agent::query()->whereIn('type_id', [TypeAgentEnum::DRIVER->value])->get();

        return DriverResource::collection($agents);
    }

    public function store(AgentStoreRequest $request)
    {
        $data = $request->validated();

        // Create an agent
        $agent = Agent::create($data);

        // Create a user associated with the agent
        $data['password'] = Hash::make($data['password']);
        $agent->user()->create($data);

        return response()->json(new AgentResource($agent));
    }


    public function show(Agent $agent)
    {
        return new AgentResource($agent);
    }


    // public function update(Agent $agent, Request $request)
    // {
    //     $data = $request->validated();

    //     $agent->update($data);

    //     if ($data['password']) {
    //         $data['password'] = Hash::make($data['password']);
    //     }

    //     $agent->user()->update($data);

    //     return new AgentResource($agent);
    // }

    public function update(Agent $agent, Request $request)
    {
        // Ensure that the request is authenticated
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Validate the request data
        $validator = Validator::make($request->all(), [
            'type_id' => 'required|exists:types,id',
            'driving_license' => 'required|string',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'required|string',
            'gender' => 'required|in:male,female,other',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'active' => 'required|boolean',
            'password' => 'nullable|string|min:8',
        ]);

        // If validation fails, return the errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Get the validated data
        $data = $validator->validated();

        // Update the agent's data
        $agent->update([
            'type_id' => $data['type_id'],
            'driving_license' => $data['driving_license'],
            // Add other fields as needed
        ]);

        // Update the associated user's data
        $user = $agent->user;
        $user->update([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'gender' => $data['gender'],
            'active' => $data['active'],
        ]);

        // If password is provided, update it
        if (!empty($data['password'])) {
            $user->update([
                'password' => Hash::make($data['password']),
            ]);
        }

        // Handle image upload and update
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . $file->getClientOriginalName();

            // Store the image in the storage/app/public/images folder
            $file->storeAs('public/images', $filename);

            // Delete the old image if it exists
            if ($user->image) {
                Storage::delete('public/' . $user->image);
            }

            // Update the image path in the database
            $user->update([
                'image' => 'images/' . $filename,
            ]);
        }

        // Return success response
        return response()->json(['message' => 'Agent updated successfully', 'data' => new AgentResource($agent)]);
    }
    public function destroy(Agent $agent)
    {
        $agent->user()->delete();
        $agent->delete();

        return response()->json([
            'message' => 'Agent supprimé avec succès',
        ]);
    }
}
