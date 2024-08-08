<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'phone_number' => 'nullable|string',
                'photo' => 'nullable|string',
            ]);

            $userId = auth()->id();

            $validated['user_id'] = $userId;

            $profile = Profile::create($validated);

            return response()->json([
                'data' => $profile,
                'message' => 'Profile created successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Unable to create profile',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $profile = Profile::findOrFail($id);

            return response()->json([
                'data' => $profile,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Unable to fetch profile',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'phone_number' => 'nullable|string',
                'photo' => 'nullable|string',
            ]);

            $profile = Profile::findOrFail($id);

            $profile->update($validated);

            return response()->json([
                'data' => $profile,
                'message' => 'Profile updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Unable to update profile',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $profile = Profile::findOrFail($id);

            $profile->delete();

            return response()->json([
                'message' => 'Profile deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Unable to delete profile',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
