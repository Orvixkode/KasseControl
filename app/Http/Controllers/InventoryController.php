<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->withCount(['stockMovements'])
            ->paginate(20);

        $lowStockCount = Product::whereColumn('stock', '<=', 'min_stock')->count();
        $outOfStockCount = Product::where('stock', '=', 0)->count();
        $totalProducts = Product::count();
        $totalStockValue = Product::sum(DB::raw('stock * cost'));

        return view('inventory.index', compact(
            'products',
            'lowStockCount',
            'outOfStockCount',
            'totalProducts',
            'totalStockValue'
        ));
    }

    public function movements($productId)
    {
        $product = Product::findOrFail($productId);
        $movements = StockMovement::where('product_id', $productId)
            ->with(['sale', 'purchase'])
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('inventory.movements', compact('product', 'movements'));
    }

    public function adjust(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer',
            'type' => 'required|in:add,subtract,set',
            'reason' => 'required|string|max:255',
        ]);

        $oldStock = $product->stock;

        switch ($request->type) {
            case 'add':
                $newStock = $oldStock + $request->quantity;
                break;
            case 'subtract':
                $newStock = max(0, $oldStock - $request->quantity);
                break;
            case 'set':
                $newStock = $request->quantity;
                break;
        }

        $product->update(['stock' => $newStock]);

        // Log stock movement
        StockMovement::create([
            'product_id' => $product->id,
            'type' => 'adjustment',
            'quantity_before' => $oldStock,
            'quantity_changed' => $newStock - $oldStock,
            'quantity_after' => $newStock,
            'reference_type' => 'manual',
            'reference_id' => auth()->id(),
            'notes' => $request->reason,
        ]);

        return redirect()->route('inventory.index')
            ->with('success', 'Stock adjusted successfully');
    }
}
