<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class SaleService
{
    public function __construct(
        protected StockService $stockService
    ) {}

    /**
     * Create a new sale
     */
    public function createSale(array $data): Sale
    {
        return DB::transaction(function () use ($data) {
            // Create sale
            $sale = Sale::create([
                'invoice_no' => $this->generateInvoiceNumber(),
                'customer_id' => $data['customer_id'] ?? null,
                'user_id' => auth()->id(),
                'sale_date' => now(),
                'subtotal' => $data['subtotal'],
                'tax' => $data['tax'] ?? 0,
                'discount' => $data['discount'] ?? 0,
                'total' => $data['total'],
                'paid' => $data['paid'],
                'payment_method' => $data['payment_method'],
                'status' => 'completed',
                'notes' => $data['notes'] ?? null,
            ]);

            // Create sale items and update stock
            foreach ($data['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                // Check stock
                if (!$this->stockService->hasSufficientStock($product, $item['quantity'])) {
                    throw new \Exception("Insufficient stock for product: {$product->name}");
                }

                // Create sale item
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['subtotal'],
                    'tax' => $item['tax'] ?? 0,
                    'discount' => $item['discount'] ?? 0,
                    'total' => $item['total'],
                ]);

                // Update stock
                $this->stockService->updateStock(
                    $product,
                    $item['quantity'],
                    'sale',
                    "Sale #{$sale->invoice_no}"
                );
            }

            return $sale->load('items.product', 'customer');
        });
    }

    /**
     * Generate unique invoice number
     */
    protected function generateInvoiceNumber(): string
    {
        $prefix = 'INV';
        $date = now()->format('Ymd');
        $lastSale = Sale::whereDate('created_at', today())->latest('id')->first();
        $number = $lastSale ? (intval(substr($lastSale->invoice_no, -4)) + 1) : 1;
        
        return sprintf('%s-%s-%04d', $prefix, $date, $number);
    }

    /**
     * Get sales summary
     */
    public function getSalesSummary(string $period = 'today')
    {
        $query = Sale::query()->where('status', 'completed');

        if ($period === 'today') {
            $query->today();
        } elseif ($period === 'month') {
            $query->thisMonth();
        }

        return $query->selectRaw('
            COUNT(*) as total_sales,
            SUM(total) as total_amount,
            SUM(paid) as total_paid,
            SUM(total - paid) as total_due
        ')->first();
    }
}
