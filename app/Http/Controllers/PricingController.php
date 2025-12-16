<?php

namespace App\Http\Controllers;

class PricingController extends Controller
{
    public function index()
    {
        return view('pricing.index', [
            'proPrice' => config('midtrans.pro_price'),
            'proName' => config('midtrans.pro_name'),
        ]);
    }
}
