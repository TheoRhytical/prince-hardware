<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerCollection;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
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
                'created_at',
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
}
