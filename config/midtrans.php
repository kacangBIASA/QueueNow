<?php

return [
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    'pro_price' => (int) env('MIDTRANS_PRO_PRICE', 25000),
    'pro_name' => env('MIDTRANS_PRO_NAME', 'QueueNow Pro'),
];
