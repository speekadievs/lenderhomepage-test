<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Hash;
use Illuminate\Http\JsonResponse;


class LoginController extends Controller
{
    /**
     * LoginController constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api')->except(['login']);
    }

    /**
     * Attempt to login user.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::whereEmail($request->input('username'))->first();

        // Check password.
        if (!$user || !Hash::check($request->input('password'), $user->getAuthPassword())) {
            abort(422, 'These credentials do not match our records.');
        }

        $token = auth()->attempt([
            'email'    => $request->input('username'),
            'password' => $request->input('password'),
        ]);

        if (!$token) {
            abort(422, 'These credentials do not match our records.');
        }

        return response()->json([
            'token'   => $token,
            'expires' => auth()->factory()->getTTL(),
        ]);
    }

    /**
     * Get new token from refresh token.
     *
     * @return JsonResponse
     */
    public function refreshToken(): JsonResponse
    {
        return response()->json([
            'token'   => auth()->refresh(),
            'expires' => auth()->factory()->getTTL(),
        ]);
    }
}
