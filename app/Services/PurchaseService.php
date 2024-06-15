<?php

namespace App\Services;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class PurchaseService
{
    public function __construct(
        protected StockService $stockService
    ) {}

    /**
     * Create a new purchase
     */
    public function createPurchase(array $data): Purchase
    {
        return DB::transaction(function () use ($data) {
            // Create purchase
            $purchase = Purchase::create([
                'reference_no' => $this->generateReferenceNumber(),
                'supplier_id' => $data['supplier_id'] ?? null,
                'user_id' => auth()->id(),
                'purchase_date' => $data['purchase_date'] ?? now(),
                'total' => $data['total'],
                'paid' => $data['paid'] ?? 0,
                'payment_method' => $data['payment_method'] ?? 'cash',
                'status' => 'received',
                'notes' => $data['notes'] ?? null,
            ]);

            // Create purchase items and update stock
            foreach ($data['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                // Create purchase item
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_cost' => $item['unit_cost'],
                    'total' => $item['total'],
                ]);

                // Update product cost
                $product->update(['cost' => $item['unit_cost']]);

                // Update stock
                $this->stockService->updateStock(
                    $product,
                    $item['quantity'],
                    'purchase',
                    "Purchase #{$purchase->reference_no}"
                );
            }

            return $purchase->load('items.product', 'supplier');
        });
    }

    /**
     * Generate unique reference number
     */
    protected function generateReferenceNumber(): string
    {
        $prefix = 'PUR';
        $date = now()->format('Ymd');
        $lastPurchase = Purchase::whereDate('created_at', today())->latest('id')->first();
        $number = $lastPurchase ? (intval(substr($lastPurchase->reference_no, -4)) + 1) : 1;
        
        return sprintf('%s-%s-%04d', $prefix, $date, $number);
    }
}
