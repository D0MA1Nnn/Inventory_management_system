<?php

namespace App\Http\Controllers;

use App\Models\User;

class CustomerController extends Controller
{
    public function index()
    {
        return view('customers');
    }

    public function getCustomers()
    {
        return User::where('role', 'customer')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getStats()
    {
        $customers = User::where('role', 'customer')->get();

        return response()->json([
            'total' => $customers->count(),
            'new' => $customers->where('created_at', '>=', now()->subDays(7))->count(),
        ]);
    }
}