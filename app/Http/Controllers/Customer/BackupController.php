<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BackupController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Customer/Backup');
    }

    public function download(Request $request)
    {
        return response()->json([
            'message' => 'Error'
        ], 500);
    }
}
