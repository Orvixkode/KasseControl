<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $sale->invoice_no ?? $sale->id }} - KasseControl</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Print Button -->
        <div class="no-print mb-4 flex justify-between">
            <a href="{{ route('sales.show', $sale) }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                ← Back
            </a>
            <button onclick="window.print()" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                🖨️ Print Invoice
            </button>
        </div>

        <!-- Invoice -->
        <div class="bg-white rounded-lg shadow-lg p-8 max-w-4xl mx-auto">
            <!-- Header -->
            <div class="border-b-2 border-gray-300 pb-6 mb-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-4xl font-bold text-blue-600 mb-2">KasseControl</h1>
                        <p class="text-gray-600">Point of Sale System</p>
                        <p class="text-sm text-gray-500">123 Business Street</p>
                        <p class="text-sm text-gray-500">City, State 12345</p>
                        <p class="text-sm text-gray-500">Phone: (555) 123-4567</p>
                    </div>
                    <div class="text-right">
                        <h2 class="text-3xl font-bold text-gray-800 mb-2">INVOICE</h2>
                        <p class="text-lg"><strong>Invoice #:</strong> {{ $sale->invoice_no ?? str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</p>
                        <p class="text-sm text-gray-600">
                            <strong>Date:</strong> {{ $sale->sale_date ? $sale->sale_date->format('M d, Y h:i A') : $sale->created_at->format('M d, Y h:i A') }}
                        </p>
                        <p class="mt-2">
                            <span class="px-3 py-1 rounded-full text-sm font-semibold
                                {{ $sale->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $sale->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $sale->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($sale->status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Customer & Cashier Info -->
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">Bill To:</h3>
                    @if($sale->customer)
                        <p class="font-semibold text-lg">{{ $sale->customer->name }}</p>
                        <p class="text-gray-600">{{ $sale->customer->phone }}</p>
                        @if($sale->customer->email)
                            <p class="text-gray-600">{{ $sale->customer->email }}</p>
                        @endif
                        @if($sale->customer->address)
                            <p class="text-gray-600">{{ $sale->customer->address }}</p>
                        @endif
                    @else
                        <p class="font-semibold text-lg">Walk-in Customer</p>
                        <p class="text-gray-600">No customer details</p>
                    @endif
                </div>
                
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">Cashier:</h3>
                    <p class="font-semibold text-lg">{{ $sale->user->name }}</p>
                    <p class="text-gray-600">{{ $sale->user->email }}</p>
                    <p class="text-gray-600 mt-2"><strong>Payment:</strong> {{ ucfirst($sale->payment_method) }}</p>
                </div>
            </div>

            <!-- Items Table -->
            <div class="mb-6">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-100 border-y-2 border-gray-300">
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">#</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Product</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Qty</th>
                            <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">Unit Price</th>
                            <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">Tax</th>
                            <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->items as $index => $item)
                        <tr class="border-b border-gray-200">
                            <td class="px-4 py-3 text-sm">{{ $index + 1 }}</td>
                            <td class="px-4 py-3">
                                <div class="font-medium">{{ $item->product->name }}</div>
                                <div class="text-xs text-gray-500">{{ $item->product->code }}</div>
                            </td>
                            <td class="px-4 py-3 text-center">{{ number_format($item->quantity, 2) }}</td>
                            <td class="px-4 py-3 text-right">${{ number_format($item->unit_price, 2) }}</td>
                            <td class="px-4 py-3 text-right">${{ number_format($item->tax, 2) }}</td>
                            <td class="px-4 py-3 text-right font-semibold">${{ number_format($item->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Totals -->
            <div class="flex justify-end mb-8">
                <div class="w-64">
                    <div class="flex justify-between py-2 text-gray-700">
                        <span>Subtotal:</span>
                        <span class="font-medium">${{ number_format($sale->subtotal, 2) }}</span>
                    </div>
                    @if($sale->tax > 0)
                    <div class="flex justify-between py-2 text-gray-700">
                        <span>Tax (10%):</span>
                        <span class="font-medium">${{ number_format($sale->tax, 2) }}</span>
                    </div>
                    @endif
                    @if($sale->discount > 0)
                    <div class="flex justify-between py-2 text-gray-700">
                        <span>Discount:</span>
                        <span class="font-medium text-red-600">-${{ number_format($sale->discount, 2) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between py-3 border-t-2 border-gray-300 text-xl font-bold text-gray-900">
                        <span>Total:</span>
                        <span>${{ number_format($sale->total, 2) }}</span>
                    </div>
                    <div class="flex justify-between py-2 text-gray-700 bg-green-50 px-3 rounded">
                        <span>Paid:</span>
                        <span class="font-semibold text-green-600">${{ number_format($sale->paid, 2) }}</span>
                    </div>
                    @if($sale->change > 0)
                    <div class="flex justify-between py-2 text-gray-700 px-3">
                        <span>Change:</span>
                        <span class="font-medium">${{ number_format($sale->change, 2) }}</span>
                    </div>
                    @endif
                    @php
                        $balance = $sale->total - $sale->paid;
                    @endphp
                    @if($balance > 0.01)
                    <div class="flex justify-between py-2 text-gray-700 bg-orange-50 px-3 rounded mt-1">
                        <span>Balance Due:</span>
                        <span class="font-semibold text-orange-600">${{ number_format($balance, 2) }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Footer -->
            <div class="border-t-2 border-gray-300 pt-6 text-center">
                <p class="text-gray-600 mb-2">Thank you for your business!</p>
                <p class="text-sm text-gray-500">This is a computer-generated invoice and requires no signature.</p>
                @if($sale->notes)
                    <div class="mt-4 p-3 bg-gray-50 rounded">
                        <p class="text-xs font-semibold text-gray-600 uppercase">Notes:</p>
                        <p class="text-sm text-gray-700">{{ $sale->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Print Again Button (bottom) -->
        <div class="no-print mt-4 text-center">
            <button onclick="window.print()" class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                🖨️ Print Invoice
            </button>
        </div>
    </div>
</body>
</html>
