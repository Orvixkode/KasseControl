<?php

return [
    // Business Settings
    'currency' => env('CURRENCY', 'USD'),
    'currency_symbol' => env('CURRENCY_SYMBOL', '$'),
    'tax_rate' => env('TAX_RATE', 10),
    'timezone' => env('TIMEZONE', 'UTC'),
    
    // Date/Time Formats
    'date_format' => env('DATE_FORMAT', 'Y-m-d'),
    'time_format' => env('TIME_FORMAT', 'H:i:s'),
    'datetime_format' => env('DATE_FORMAT', 'Y-m-d') . ' ' . env('TIME_FORMAT', 'H:i:s'),
    
    // POS Settings
    'default_payment_method' => 'cash',
    'enable_barcode_scanner' => true,
    'print_receipt_after_sale' => true,
    
    // Stock Settings
    'low_stock_threshold' => 5,
    'enable_stock_tracking' => true,
    
    // Sales Settings
    'allow_sale_on_low_stock' => false,
    'allow_sale_on_zero_stock' => false,
];
