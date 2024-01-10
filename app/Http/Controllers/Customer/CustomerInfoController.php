<?php

namespace App\Http\Controllers\Customer;

use App\Enums\CardStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerCollection;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Models\User;
// use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Enum;
use Inertia\Inertia;
use Inertia\Response;
use Intervention\Image\ImageManager;

class CustomerInfoController extends Controller
{
    /**
     * Display customer's view of their information
     */
    public function profile(Request $request): Response
    {
        $user = Auth::user();
        // dd($user, $user->customer);
        return Inertia::render('Customer/Profile', [
            'full_name' => $user->full_name,
            'date_of_birth' => date('F d, Y', strtotime($user->customer->date_of_birth)),
            'address' => $user->customer->address,
            'email' => $user->email,
            'phone_number' => $user->customer->phone_number,
            'card_status' => match($user->customer->card_status) {
                'processing' => 'On Process',
                'released' => 'Released',
            }
        ]);
    }

    /**
     * Display table of customer information
     */
    public function index(Request $request): Response
    {
        return Inertia::render('Customer/UserInfo', [
            'data' => new CustomerCollection(Customer::orderBy('id')->with('user')->paginate())
        ]);
    }

    /**
     * Provide paginated tabular data
     */
    public function page(Request $request)
    {
        $request->validate([
            'page' => 'int',
            'items' => 'int',
            'searchQuery' => 'string',
        ]);
        
        if ($request?->searchQuery) {
            $searchableFields = [
                // 'id',
                'full_name',
                'address',
                'phone_number',
                'card_status'
            ];
            $searchableDateFields = [
                'date_of_birth'
            ];
            return new CustomerCollection(
                Customer::searchLocal($request->searchQuery, $searchableFields, $searchableDateFields)
                ->with(['user' => function ($query) use ($request) {
                    foreach (User::$searchableLocal as $field) {
                        $query->orWhere($field, 'LIKE', "%$request->searchQuery%");
                    }
                }])
                ->orderBy('id')
                ->paginate($request?->items ?? 15)
            );
        }

        return new CustomerCollection(
            Customer::with('user')->orderBy('id')->paginate($request?->items ?? 15)
        );
    }

    /**
     * Individually edit a customer's personal data
     */
    public function edit(Request $request): JsonResponse
    {
        $dataToPatch = $request->validate([
            'id' => ['required', 'integer', 'exists:'.Customer::class],
            'full_name' => ['string', 'max:255', 'unique:'.Customer::class],
            'date_of_birth' => ['date'],
            'address' => ['string', 'max:'. Customer::$addressMaxLength],
            'email' => ['email:rfc,dns', 'unique:'.User::class],
            'phone_number' => ['string', 'regex:/^\+63-(9\d{2})-\d{3}-\d{4}$/', 'unique:'.Customer::class],
            'signature' => ['image'],
        ]);

        try {
            $userFields = ['full_name', 'email'];
            $userData = array_filter($dataToPatch, fn($key) => in_array($key, $userFields), ARRAY_FILTER_USE_KEY);
            $customerData = array_filter($dataToPatch, fn($key) => $key !== 'email', ARRAY_FILTER_USE_KEY);

            if (isset($dataToPatch['signature'])) {
                $image = $customerData['signature'];

                // Resize signature file dimensions
                $resizedSignatureImage = ImageManager::gd()
                    ->read($image)
                    ->scaleDown(400, 400);

                $filename = $customerData['id'] . "-signature.{$image->getClientOriginalExtension()}";
        
                $resizedSignatureImage->save(storage_path("app/customer-signatures/{$filename}"));
                $customerData['signature_filename'] = $filename;
                unset($customerData['signature']);
            }

            // dd($dataToPatch, $userData, $customerData);
            $customer = Customer::where('id', $request->id);
            $customer->update($customerData);
            $customer->first()->user()->update($userData);
            $updatedCustomer = $customer->get();
            DB::commit();
        } catch (\Exception|\Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            abort(HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response()->json([
            'message' => 'Successfully updated customer data',
            'customer' => (new CustomerCollection($updatedCustomer))[0],
        ], 200);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer', 'exists:'.Customer::class],
        ]);

        try {
            DB::beginTransaction();
            $customer = Customer::find($request->id);
            $userId = $customer->user_id;
            Customer::where('id', $request->id)->delete();
            User::where('id', $userId)->delete();
            // $user = $customer->user();
            // $user->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            abort(HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'message' => 'Successfully deleted customer'
        ], 200);
    }

    public function updateCardStatus(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer', 'exists:'.Customer::class],
            'card_status' => ['required', 'string'
            // , new Enum(CardStatus::class)
            , 'in:released,processing'
        ],
        ]);
        // dd($request);

        Customer::where('id', $request->id)
        ->update([
            'card_status' => $request->card_status,
        ]);

        return response()->json([
            'message' => 'Successfully updated customer card status'
        ], 200);
    }

    /**
     * Serves customer signatures to admin panel
     */
    public function getSignature(string $filename)
    {
        if (Storage::missing("customer-signatures/$filename")) abort(404);
        return response()->file(storage_path("app/customer-signatures/$filename"));
    }

    /**
     * Serves customer signatures to customer
     */
    public function customerGetSignature(Request $request)
    {
        $user = Auth::user();
        $filename = $user->customer->signature_filename;
        if (Storage::missing("customer-signatures/$filename")) abort(404);
        return response()->file(storage_path("app/customer-signatures/$filename"));
    }
    
}
