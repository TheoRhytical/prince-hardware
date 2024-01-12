<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function dashboard()
    {
        // $adminName = Auth::user()->full_name;
        return Inertia::render('Dashboard', [
            'adminName' => Auth::user()->full_name,
            'releasedCards' => Customer::where('card_status', 'released')
                ->whereNotNull('released_at')
                ->count(),
            'totalCustomers' => Customer::count(),
            'newCustomers' => Customer::where('card_status', 'processing')
                ->whereNull('released_at')
                ->count(),
        ]);
    }
}
