<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class StockService
{
    /**
     * Update product stock
     */
    public function updateStock(Product $product, float $quantity, string $type, ?string $reference = null, ?string $notes = null): void
    {
        DB::transaction(function () use ($product, $quantity, $type, $reference, $notes) {
            // Update product stock
            $oldStock = $product->stock;
            
            if (in_array($type, ['purchase', 'adjustment'])) {
                $product->stock += $quantity;
            } elseif (in_array($type, ['sale', 'return'])) {
                $product->stock -= $quantity;
            }
            
            $product->save();
            
            // Record stock movement
            StockMovement::create([
                'product_id' => $product->id,
                'type' => $type,
                'quantity' => $quantity,
                'balance' => $product->stock,
                'reference' => $reference,
                'notes' => $notes,
                'user_id' => auth()->id(),
            ]);
        });
    }
    
    /**
     * Check if product has sufficient stock
     */
    public function hasSufficientStock(Product $product, float $quantity): bool
    {
        return $product->stock >= $quantity;
    }
    
    /**
     * Get low stock products
     */
    public function getLowStockProducts()
    {
        return Product::lowStock()->active()->with('category')->get();
    }
}
