<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules;

/**
 * @group Authentication Controller
 */
class AuthController extends Controller
{
    /**
     * Login
     * @unauthenticated
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user)
            throw ValidationException::withMessages([
                'email' => ['Email not registered'],
            ]);

        if (! $jwtToken = auth()->attempt($credentials))
            throw ValidationException::withMessages([
                'password' => ['Incorrect password'],
            ]);

        return $this->respondWithJwt($jwtToken, 200);
    }

    /**
     * Register
     * @unauthenticated
     * @bodyParam password_confirmation string required
     */
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $jwtToken = Auth::login($user);

        // ! NOTE: Ignore if there is an error here
        // !        It's just an error with Intelephense 
        // !        not working with jwt-auth config
        return $this->respondWithJwt($jwtToken, 200);
    }

    /**
     * Logout
     */
    public function logout(Request $request): JsonResponse
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out'], 204);
    }

    /**
     * Refresh Token
     */
    public function refresh(Request $request): JsonResponse
    {
        return $this->respondWithJwt(Auth::refresh(), 200);
    }

    protected function respondWithJwt(string $token, int $status): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ]);
    }
}
