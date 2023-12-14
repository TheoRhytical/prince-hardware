<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AnnouncementController extends Controller
{
    /**
     * Display view for announcement
     */
    public function index(Request $request): Response
    {
        return Inertia::render('Customer/Announcement');
    }
}
