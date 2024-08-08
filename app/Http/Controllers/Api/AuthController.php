<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('budget')->plainTextToken;
            $profile = $user->profile;
            if ($profile) {
                $profile = $profile->only(['id', 'first_name', 'last_name', 'photo']);
            }
            return response()->json([
                'token' => $token,
                'profile' => $profile,
            ]);
        } else {
            return response()->json([
                'error' => 'Unauthorized',
            ], 401);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Token revoked',
        ]);
    }
}
