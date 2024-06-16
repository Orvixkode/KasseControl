@extends('layouts.app')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <!-- Today's Sales -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Today's Sales</p>
                <p class="text-2xl font-bold text-gray-900">${{ number_format($todaySummary->total_amount ?? 0, 2) }}</p>
                <p class="text-xs text-gray-500">{{ $todaySummary->total_sales ?? 0 }} transactions</p>
            </div>
            <div class="p-3 bg-blue-100 rounded-full">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- This Month's Sales -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">This Month</p>
                <p class="text-2xl font-bold text-gray-900">${{ number_format($monthSummary->total_amount ?? 0, 2) }}</p>
                <p class="text-xs text-gray-500">{{ $monthSummary->total_sales ?? 0 }} transactions</p>
            </div>
            <div class="p-3 bg-green-100 rounded-full">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Low Stock Alerts -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Low Stock Items</p>
                <p class="text-2xl font-bold text-gray-900">{{ $lowStockProducts->count() }}</p>
                <p class="text-xs text-gray-500">Need attention</p>
            </div>
            <div class="p-3 bg-yellow-100 rounded-full">
                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Quick POS Access -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow p-6 text-white">
        <h3 class="text-lg font-semibold mb-2">Quick Sale</h3>
        <p class="text-sm mb-4 opacity-90">Start a new transaction</p>
        <a href="{{ route('pos.index') }}" class="inline-block bg-white text-blue-600 px-4 py-2 rounded font-medium hover:bg-blue-50">
            Open POS
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Sales -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h2 class="text-lg font-semibold">Recent Sales</h2>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @forelse($recentSales as $sale)
                <div class="flex justify-between items-center pb-3 border-b">
                    <div>
                        <p class="font-medium">{{ $sale->invoice_no }}</p>
                        <p class="text-sm text-gray-600">{{ $sale->customer->name ?? 'Walk-in Customer' }}</p>
                        <p class="text-xs text-gray-500">{{ $sale->sale_date->format('M d, Y H:i') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-green-600">${{ number_format($sale->total, 2) }}</p>
                        <span class="text-xs px-2 py-1 bg-green-100 text-green-800 rounded">{{ ucfirst($sale->status) }}</span>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">No sales yet</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Low Stock Products -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h2 class="text-lg font-semibold">Low Stock Alert</h2>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @forelse($lowStockProducts as $product)
                <div class="flex justify-between items-center pb-3 border-b">
                    <div>
                        <p class="font-medium">{{ $product->name }}</p>
                        <p class="text-sm text-gray-600">{{ $product->category->name ?? 'Uncategorized' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-red-600">{{ $product->stock }} {{ $product->unit }}</p>
                        <p class="text-xs text-gray-500">Min: {{ $product->min_stock }}</p>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">All products have sufficient stock</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
