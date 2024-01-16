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
        $request->validate([
            'email' => ['required', 'email:rfc,dns'],
        ]);
        return response()->json([
            'message' => 'Downloaded data sent to '. $request->email
        ], 200);
    }
}
