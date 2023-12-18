<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerCollection;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
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
    public function page()
    {
        return new CustomerCollection(Customer::with('user')->paginate());
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
