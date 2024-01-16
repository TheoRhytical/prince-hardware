<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\HttpJsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Intervention\Image\ImageManager;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'full_name' => ['required', 'string', 'max:255', 'unique:'.Customer::class],
            'date_of_birth' => ['required', 'date', 'before:today', 'after:120 years ago'],
            'address' => ['required', 'string', 'max:'. Customer::$addressMaxLength],
            'email' => ['required', 'email:rfc,dns', 'unique:'.User::class],
            'phone_number' => ['required', 'string', 'regex:/^\+63-(9\d{2})-\d{3}-\d{4}$/', 'unique:'.Customer::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'signature' => ['required', 'image'],
        ]);


        try {
			DB::beginTransaction();
            // Create new customer instance without saving (to use id later)
            $user = User::create([
                'email' => $request->email,
                'full_name' => $request->full_name,
                'user_type' => 'customer',
                'password' => Hash::make($request->password),
            ]);
        
            $customer = Customer::create([
                'full_name' => $request->full_name,
                'date_of_birth' => $request->date_of_birth,
                'address' => $request->address,
                'phone_number' => $request->phone_number,
                'card_status' => 'processing',
                'user_id' => $user->id,
            ]);

            $image = $request->signature;

            // Resize signature file dimensions
            $resizedSignatureImage = ImageManager::gd()
                ->read($image)
                ->scaleDown(400, 400);

            $filename = "{$customer->id}-signature.{$image->getClientOriginalExtension()}";
        
            $resizedSignatureImage->save(storage_path("app/customer-signatures/{$filename}"));
            $customer->update(['signature_filename' => $filename]);
            DB::commit();
        } catch (\Exception|\Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return HttpJsonResponse::error(HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        event(new Registered($customer));

        Auth::login($user);

        return response()->json([
            'message' => 'Congratulations! Your Prince Card registration is complete.',
        ], 201);




        // $user = User::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password),
        // ]);

        // event(new Registered($user));

        // Auth::login($user);

        // return redirect(RouteServiceProvider::HOME);
    }
}
