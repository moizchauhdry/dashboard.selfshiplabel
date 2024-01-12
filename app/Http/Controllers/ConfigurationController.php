<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ConfigurationController extends Controller
{
    //
    public function index(Request $request)
    {

    }

    public function edit($id)
    {
        $configuration = Configuration::find(1);

        return Inertia::render('Configurations/Promotional',[
            'promotional' => $configuration,
        ]);
    }

    public function update(Request $request)
    {
        $validator = $request->validate([
            'description' => 'required|max:300',
        ]);

        $configuration = Configuration::find(1);
        $configuration->description = $request->description;
        $configuration->save();

        return redirect()->back()->with('success','Message Updated Successfully');

    }
}
