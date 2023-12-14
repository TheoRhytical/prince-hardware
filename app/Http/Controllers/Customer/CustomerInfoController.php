<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CustomerInfoController extends Controller
{
    /**
     * Display customer's view of their information
     */
    public function view(Request $request): Response
    {
        return Inertia::render('Customer/Profile');
    }

    /**
     * Display table of customer information
     */
    public function index(Request $request): Response
    {
        return Inertia::render('Customer/UserInfo');
    }
}
