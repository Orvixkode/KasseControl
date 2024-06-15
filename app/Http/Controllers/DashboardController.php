<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use App\Services\SaleService;
use App\Services\StockService;

class DashboardController extends Controller
{
    public function __construct(
        protected SaleService $saleService,
        protected StockService $stockService
    ) {}

    public function index()
    {
        // Get today's summary
        $todaySummary = $this->saleService->getSalesSummary('today');
        
        // Get this month's summary
        $monthSummary = $this->saleService->getSalesSummary('month');
        
        // Recent sales
        $recentSales = Sale::with(['customer', 'user'])
            ->latest()
            ->limit(10)
            ->get();
        
        // Low stock products
        $lowStockProducts = $this->stockService->getLowStockProducts();
        
        // Top selling products
        $topProducts = Product::withCount(['saleItems as total_sold' => function ($query) {
                $query->selectRaw('SUM(quantity)');
            }])
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();
        
        return view('dashboard', compact(
            'todaySummary',
            'monthSummary',
            'recentSales',
            'lowStockProducts',
            'topProducts'
        ));
    }
}
