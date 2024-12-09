<?php

namespace App\Http\Controllers;

use App\Models\Shipping;
use App\Models\ShippingService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShippingServiceController extends Controller
{
    public function index()
    {
        $services = ShippingService::get();

        return Inertia::render('ShippingService/Index', [
            'services' => $services,
        ]);
    }

    public function update(Request $request)
    {
        $services = $request->input('services');

        foreach ($services as $service) {
            $shipping_service = ShippingService::find($service['id']);
            $shipping_service->update(['markup_percentage' => $service['markup_percentage']]);
        }

        return redirect()->back()->with('success', 'The markup percentage update successfully.');
    }
}
