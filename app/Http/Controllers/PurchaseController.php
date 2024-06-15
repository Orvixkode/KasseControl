<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Product;
use App\Services\PurchaseService;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function __construct(
        protected PurchaseService $purchaseService
    ) {}

    public function index()
    {
        $purchases = Purchase::with(['supplier', 'user'])
            ->latest()
            ->paginate(20);
        
        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = Supplier::active()->get();
        $products = Product::active()->get();
        
        return view('purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'nullable|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_cost' => 'required|numeric|min:0',
            'items.*.total' => 'required|numeric',
            'total' => 'required|numeric',
            'paid' => 'nullable|numeric',
            'payment_method' => 'nullable|in:cash,card,bank,cheque',
            'notes' => 'nullable|string',
        ]);

        try {
            $purchase = $this->purchaseService->createPurchase($validated);
            
            return redirect()->route('purchases.show', $purchase)
                ->with('success', 'Purchase created successfully!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(Purchase $purchase)
    {
        $purchase->load('supplier', 'items.product', 'user');
        return view('purchases.show', compact('purchase'));
    }
}
