<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerCollection;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Models\User;
// use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class CustomerInfoController extends Controller
{
    /**
     * Display customer's view of their information
     */
    public function profile(Request $request): Response
    {
        return Inertia::render('Customer/Profile');
    }

    /**
     * Display table of customer information
     */
    public function index(Request $request): Response
    {
        return Inertia::render('Customer/UserInfo', [
            // 'data' => Customer::with('user')->paginate()
            'data' => new CustomerCollection(Customer::with('user')->paginate())
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
            return new CustomerCollection(
                Customer::searchLocal($request->searchQuery)
                ->with(['user' => function ($query) use ($request) {
                    // $query->where();
                    foreach (User::$searchableLocal as $field) {
                        $query->orWhere($field, 'LIKE', "%$request->searchQuery%");
                    }
                }])
                // ->ddRawSql()
                ->paginate($request?->items ?? 15)
            );
        }

        return new CustomerCollection(
            Customer::with('user')->paginate($request?->items ?? 15)
        );
    }

    /**
     * Serves customer signatures
     */
    public function getSignature(string $filename)
    {
        if (Storage::missing("/customers_signatures/$filename")) abort(404);
        return response()->file(storage_path("app/customers_signatures/$filename"));
    }

}
