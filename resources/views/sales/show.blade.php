@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6 pb-4 border-b">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Invoice #{{ $sale->invoice_number }}</h1>
                <p class="text-gray-600 mt-1">Sale Date: {{ $sale->sale_date->format('M d, Y h:i A') }}</p>
            </div>
            <div class="text-right">
                <span class="px-4 py-2 rounded-full text-sm font-semibold
                    {{ $sale->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $sale->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $sale->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                    {{ ucfirst($sale->status) }}
                </span>
            </div>
        </div>

        <!-- Customer & Payment Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Customer Information</h3>
                @if($sale->customer)
                    <p class="text-gray-600"><strong>Name:</strong> {{ $sale->customer->name }}</p>
                    <p class="text-gray-600"><strong>Phone:</strong> {{ $sale->customer->phone }}</p>
                    @if($sale->customer->email)
                        <p class="text-gray-600"><strong>Email:</strong> {{ $sale->customer->email }}</p>
                    @endif
                @else
                    <p class="text-gray-600">Walk-in Customer</p>
                @endif
            </div>
            
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Payment Information</h3>
                <p class="text-gray-600"><strong>Payment Method:</strong> {{ ucfirst($sale->payment_method) }}</p>
                <p class="text-gray-600"><strong>Payment Status:</strong> 
                    <span class="font-semibold {{ $sale->payment_status === 'paid' ? 'text-green-600' : 'text-orange-600' }}">
                        {{ ucfirst($sale->payment_status) }}
                    </span>
                </p>
                <p class="text-gray-600"><strong>Cashier:</strong> {{ $sale->user->name }}</p>
            </div>
        </div>

        <!-- Items Table -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-3">Items</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Tax</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($sale->items as $index => $item)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $index + 1 }}</td>
                            <td class="px-4 py-3">
                                <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                                <div class="text-sm text-gray-500">{{ $item->product->code }}</div>
                            </td>
                            <td class="px-4 py-3 text-center text-sm text-gray-900">{{ number_format($item->quantity, 2) }}</td>
                            <td class="px-4 py-3 text-right text-sm text-gray-900">${{ number_format($item->unit_price, 2) }}</td>
                            <td class="px-4 py-3 text-right text-sm text-gray-900">${{ number_format($item->subtotal, 2) }}</td>
                            <td class="px-4 py-3 text-right text-sm text-gray-900">${{ number_format($item->tax, 2) }}</td>
                            <td class="px-4 py-3 text-right text-sm font-medium text-gray-900">${{ number_format($item->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Totals -->
        <div class="flex justify-end mb-6">
            <div class="w-full md:w-1/2 lg:w-1/3">
                <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                    <div class="flex justify-between text-gray-700">
                        <span>Subtotal:</span>
                        <span class="font-medium">${{ number_format($sale->subtotal, 2) }}</span>
                    </div>
                    @if($sale->tax > 0)
                    <div class="flex justify-between text-gray-700">
                        <span>Tax:</span>
                        <span class="font-medium">${{ number_format($sale->tax, 2) }}</span>
                    </div>
                    @endif
                    @if($sale->discount > 0)
                    <div class="flex justify-between text-gray-700">
                        <span>Discount:</span>
                        <span class="font-medium text-red-600">-${{ number_format($sale->discount, 2) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between text-lg font-bold text-gray-900 pt-2 border-t border-gray-300">
                        <span>Total:</span>
                        <span>${{ number_format($sale->total, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-700">
                        <span>Paid:</span>
                        <span class="font-medium text-green-600">${{ number_format($sale->paid, 2) }}</span>
                    </div>
                    @if($sale->change > 0)
                    <div class="flex justify-between text-gray-700">
                        <span>Change:</span>
                        <span class="font-medium">${{ number_format($sale->change, 2) }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-between items-center pt-4 border-t">
            <a href="{{ route('sales.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                ← Back to Sales
            </a>
            <div class="space-x-2">
                <button onclick="window.print()" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    🖨️ Print Invoice
                </button>
                <a href="{{ route('pos.index') }}" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                    New Sale
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Print Styles -->
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .container, .container * {
            visibility: visible;
        }
        .container {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        button, a {
            display: none !important;
        }
    }
</style>
@endsection
