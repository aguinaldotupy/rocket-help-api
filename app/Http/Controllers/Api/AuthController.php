<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum'])->only(['logout']);
    }

    /**
     * @unauthenticated
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!auth()->attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if (auth()->user()->tokens()->count() > 0) {
            auth()->user()->tokens()->delete();
        }

        return response()->json([
            'token' => auth()->user()->createToken('api')->plainTextToken
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            $request->user()->tokens()->delete();
        } catch (\Exception $e) {
            Log::error($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'message' => 'Something went wrong'
            ], 500);
        }

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * @unauthenticated
     */
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password',
        ]);

        $user = new User();
        $user->fill($request->only('name', 'email'));
        $user->password = bcrypt($request->input('password'));
        $user->save();

        return response()->json([
            'message' => 'User created successfully!',
        ]);
    }
}
