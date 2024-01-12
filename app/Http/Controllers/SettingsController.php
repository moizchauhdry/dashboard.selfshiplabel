<?php

namespace App\Http\Controllers;

use App\Models\ShippingService;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::all();
        $shipping_services = ShippingService::get();

        return Inertia::render('Settings/SettingList', [
            'settings' => $settings,
            'shipping_services' => $shipping_services
        ]);
    }

    public function update(Request $request)
    {
        $settings = $request->input('settings');
        foreach ($settings as $setting) {
            $site_setting = SiteSetting::find($setting['id']);
            $site_setting->value = $setting['value'];
            $site_setting->update();
        }

        $services = $request->input('shipping_services');
        foreach ($services as $service) {
            $shipping_service = ShippingService::find($service['id']);
            $shipping_service->update([
                'markup_percentage' => $service['markup_percentage']
            ]);
        }

        return redirect('settings')->with('success', 'Settings Updated!');
    }
}
