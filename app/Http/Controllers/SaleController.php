<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with(['customer', 'user']);
        
        if ($request->from_date && $request->to_date) {
            $query->whereBetween('sale_date', [$request->from_date, $request->to_date]);
        }
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        $sales = $query->latest('sale_date')->paginate(20);
        
        return view('sales.index', compact('sales'));
    }

    public function show(Sale $sale)
    {
        $sale->load('customer', 'items.product', 'user');
        return view('sales.show', compact('sale'));
    }

    public function invoice(Sale $sale)
    {
        $sale->load('customer', 'items.product', 'user');
        return view('sales.invoice', compact('sale'));
    }
}
