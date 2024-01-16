<?php

namespace App\Http\Controllers\Customer;

use App\Helpers\HttpJsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerCollection;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CustomerHistoryController extends Controller
{
    /**
     * Display table of customer information
     */
    public function index(Request $request)
    {
        return Inertia::render('Customer/History', [
            'data' => new CustomerCollection(Customer::with('user')->orderBy('id')->paginate())
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
        // $customer = new Customer();
        // dd($customer->searchLocal($request->searchQuery));
        
        if ($request?->searchQuery) {
            $searchableFields = [
                // 'id',
                'full_name',
            ];
            $searchableDateFields = [
                'registered_at',
                'released_at'
            ];
            return new CustomerCollection(
                Customer::searchLocal($request->searchQuery, $searchableFields, $searchableDateFields)
                ->with('user')
                ->orderBy('id')
                // ->ddRawSql()
                ->paginate($request?->items ?? 15)
            );
        }

        return new CustomerCollection(
            Customer::with('user')->orderBy('id')->paginate($request?->items ?? 15)
        );
    }

    public function update(Request $request): JsonResponse
    {
        $dataToUpdate = $request->validate([
            'id' => ['required', 'integer', 'exists:'.Customer::class],
            'full_name' => ['string', 'max:255', 'unique:'.Customer::class],
            'registered_date' => ['date'],
            'released_date' => ['date'],
        ]);

        if ($request?->released_date) {
            $registeredDateUnix = strtotime($request->registered_date);
            $releasedDateUnix = strtotime($request->released_date);
            if ($registeredDateUnix > $releasedDateUnix) 
                return HttpJsonResponse::error(HttpResponse::HTTP_UNPROCESSABLE_ENTITY, 'Released Date should be after Registered Date');
            $dataToUpdate['released_at'] = $dataToUpdate['released_date'];
            unset($dataToUpdate['released_date']);
        }

        try {
            DB::beginTransaction();
            if ($request?->full_name) {
                $customer = Customer::find($request->id);
                User::where('id', $customer->user_id)
                    ->update('full_name', $request->full_name);
            }
            unset($dataToUpdate['id']); // Prevent id from being updated
            if ($request?->registered_date) {
                $dataToUpdate['registered_at'] = $dataToUpdate['registered_date'];
                unset($dataToUpdate['registered_date']);
            }
            Customer::where('id', $request->id)
                ->update($dataToUpdate);
            $updatedCustomer = Customer::where('id', $request->id)->get();
            DB::commit();
        } catch (\Exception|\Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return HttpJsonResponse::error(HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'message' => 'Successfully updated customer data',
            'customer' => (new CustomerCollection($updatedCustomer))[0],
        ], 200);
    }
}
