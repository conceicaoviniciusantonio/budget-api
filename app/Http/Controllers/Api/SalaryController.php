<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Salary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $perPage = request()->query('per_page', 15);

            $salaries = Salary::query()->paginate($perPage);

            return response()->json([
                'data' => $salaries->items(),
                'meta' => [
                    'total' => $salaries->total(),
                    'per_page' => $salaries->perPage(),
                    'current_page' => $salaries->currentPage(),
                    'last_page' => $salaries->lastPage(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Unable to fetch salaries',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'salary' => 'required|numeric|min:0',
                'year' => 'required|numeric|min:2000',
                'month' => 'required|numeric|min:1|max:12',
            ]);

            $userId = auth()->id();

            $validated['user_id'] = $userId;

            $salary = Salary::create($validated);

            return response()->json([
                'data' => $salary,
                'message' => 'Salary created successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Unable to create salary',
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
            $salary = Salary::query()->findOrFail($id);

            return response()->json([
                'data' => $salary,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Unable to fetch salary',
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
            $salary = Salary::query()->findOrFail($id);

            $validated = $request->validate([
                'salary' => 'required|numeric|min:0',
                'year' => 'required|numeric|min:2000',
                'month' => 'required|numeric|min:1|max:12',
            ]);

            $salary->update($validated);

            return response()->json([
                'data' => $salary,
                'message' => 'Salary updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Unable to update salary',
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
            $salary = Salary::query()->findOrFail($id);

            $salary->delete();

            return response()->json([
                'message' => 'Salary deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Unable to delete salary',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
