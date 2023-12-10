<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules;
use Intervention\Image\Facades\Image;

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
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user)
            throw ValidationException::withMessages([
                'email' => ['Email is not registered'],
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
     * @bodyParam phone_number string required Example: +63-910-123-4567
     */
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'full_name' => ['required', 'string', 'max:255', 'unique:'.Customer::class],
            'date_of_birth' => ['required', 'date'],
            'address' => ['required', 'string', 'max:'. Customer::$addressMaxLength],
            'email' => ['required', 'email:rfc,dns', 'unique:'.Customer::class],
            'phone_number' => ['required', 'string', 'regex:/^\+63-(9\d{2})-\d{3}-\d{4}$/', 'unique:'.Customer::class],
            'signature' => ['required', 'image'],
        ]);

        try {
			DB::beginTransaction();
            // Create new customer instance without saving (to use id later)
            $customer = Customer::create([
                'full_name' => $request->full_name,
                'date_of_birth' => $request->date_of_birth,
                'address' => $request->address,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
            ]);

            $image = $request->signature;

            // Resize signature file dimensions
            $resizedSignatureImage = Image::make($image)
                ->resize(400, 400, function ($constraint) {
                    $constraint->aspectRatio();
                });

            $filename = "{$customer->id}-signature.{$image->getClientOriginalExtension()}";
        
            $resizedSignatureImage->save(storage_path("app/customers_signatures/{$filename}"));
            $customer->update(['signature_filename' => $filename]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Internal Server Error',
            ], 500);
        }

        return response()->json([
            'message' => 'Successfully registered for customer reward card',
        ], 201);

        // $jwtToken = Auth::login($user);

        // ! NOTE: Ignore if there is an error here
        // !        It's just an error with Intelephense 
        // !        not working with jwt-auth config
        // return $this->respondWithJwt($jwtToken, 200);
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
