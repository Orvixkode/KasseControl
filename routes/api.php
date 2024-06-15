<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API routes for POS quick actions
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/products/search', function (Request $request) {
        $query = $request->get('q', '');
        
        $products = \App\Models\Product::where('active', true)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('code', $query)
                  ->orWhere('barcode', $query);
            })
            ->with('category')
            ->limit(10)
            ->get();
        
        return response()->json($products);
    });
    
    Route::get('/customers/search', function (Request $request) {
        $query = $request->get('q', '');
        
        $customers = \App\Models\Customer::where('active', true)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('phone', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get();
        
        return response()->json($customers);
    });
});
