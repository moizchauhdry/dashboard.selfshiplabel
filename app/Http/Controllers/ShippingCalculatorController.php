<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShippingCalculatorController extends Controller
{
    public function index()
    {
        $countries = Country::get(['id', 'nicename as name', 'iso'])->toArray();
        $warehouses = Warehouse::get()->toArray();

        return Inertia::render('ShippingCalculator/Index', [
            'countries' => $countries,
            'warehouses' => $warehouses,
        ]);
    }
}
