<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerHistoryController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Customer/History');
    }
}
