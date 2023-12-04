<?php

namespace App\Http\Controllers;

use App\Models\Customer;
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
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('username', $credentials['username'])->first();

        if (!$user)
            throw ValidationException::withMessages([
                'username' => ['Username does not exist'],
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
     * @bodyParam phone_number string required Example: '+63-910-123-4567'
     * d
     */
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'full_name' => ['required', 'string', 'max:255', 'unique:'.Customer::class],
            'date_of_birth' => ['required', 'date'],
            'address' => ['required', 'string', 'max:'. Customer::$addressMaxLength],
            'email' => ['required', 'email:rfc,dns'],
            'phone_number' => ['required', 'string', 'regex:/^\+63-(9\d{2})-\d{3}-\d{4}$/'],
            'signature' => ['required', 'image'],
        ]);

        $user = Customer::create([
            'full_name' => $request->full_name,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            // 'signature_filename' => $request->signature_filename,
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
