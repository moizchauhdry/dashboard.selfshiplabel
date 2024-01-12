<?php

namespace App\Http\Controllers;

use App\Models\ServicePage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ServicePageController extends Controller
{
    //
    public function edit()
    {
        $services = ServicePage::all();

        return Inertia::render('ServicePage/Edit',['services' => $services]);
    }

    public function update(Request $request)
    {
        $services = $request->services;

        foreach ($services as $item){
            $service = ServicePage::find($item['id']);
            $service->description = $item['description'];
            $service->save();
        }

        return redirect()->back()->with('success','Record Updated Successfully');
    }
}
