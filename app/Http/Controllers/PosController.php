<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use App\Services\SaleService;
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function __construct(
        protected SaleService $saleService
    ) {}

    public function index()
    {
        $products = Product::active()
            ->with('category')
            ->where('stock', '>', 0)
            ->get();
        
        $customers = Customer::active()->get();
        
        return view('pos.index', compact('products', 'customers'));
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.subtotal' => 'required|numeric',
            'items.*.tax' => 'nullable|numeric',
            'items.*.discount' => 'nullable|numeric',
            'items.*.total' => 'required|numeric',
            'subtotal' => 'required|numeric',
            'tax' => 'nullable|numeric',
            'discount' => 'nullable|numeric',
            'total' => 'required|numeric',
            'paid' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,card,mobile,bank',
        ]);

        try {
            // Set paid to total if not provided
            $validated['paid'] = $validated['paid'] ?? $validated['total'];
            
            $sale = $this->saleService->createSale($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Sale completed successfully!',
                'sale_id' => $sale->id,
                'sale' => $sale,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
