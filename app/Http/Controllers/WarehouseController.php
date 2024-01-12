<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Warehouse;
use App\Models\Country;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $warehouses = Warehouse::with('country')->paginate(25);

        return Inertia::render('Warehouses/WarehouseList',[
            'warehouses' => $warehouses
        ]);   
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {  
        $countries = Country::all(['id','nicename as name'])->toArray();

        return Inertia::render('Warehouses/CreateWarehouse',[
            'countries' => $countries
        ]);  
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'country_id' => 'required',
            'state' => 'required|string',
            'zip' => 'required|string',
            'city' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'email' => 'required|string',
            'contact_person' => 'required|string',
        ]);

        $warehouse = new Warehouse();
        $warehouse->name = $validated['name'];
        $warehouse->country_id = $validated['country_id'];
        $warehouse->city = $validated['city'];
        $warehouse->state = $validated['state'];
        $warehouse->phone = $validated['phone'];
        $warehouse->zip = $validated['zip'];
        $warehouse->address = $validated['address'];
        $warehouse->email = $validated['email'];
        $warehouse->contact_person = $validated['contact_person'];


        $warehouse->save();
        
        return redirect('warehouses')->with('success','Warehouse Created Successfully.');  
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /** Show the form for editing the specified resource. @param  int  $id @return \Illuminate\Http\Response */public function edit($id) {$warehouse = Warehouse::find($id);$countries = Country::all(['id','nicename as name'])->toArray();return Inertia::render('Warehouses/EditWarehouse',['warehouse' => $warehouse, 'countries' => $countries]);}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $id = $request->input('id');

        $warehouse = Warehouse::find($id);

        $validated = $request->validate([
            'name' => 'required|string',
            'country_id' => 'required',
            'state' => 'required|string',
            'city' => 'required|string',
            'zip' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'email' => 'required|string',
            'contact_person' => 'required|string',
        ]);

        $warehouse->name = $validated['name'];
        $warehouse->country_id = $validated['country_id'];
        $warehouse->state = $validated['state'];
        $warehouse->city = $validated['city'];
        $warehouse->zip = $validated['zip'];
        $warehouse->phone = $validated['phone'];
        $warehouse->address = $validated['address'];
        $warehouse->email = $validated['email'];
        $warehouse->contact_person = $validated['contact_person'];

        $warehouse->update();

        return redirect('warehouses')->with('success', 'Warehouse Updated Successfully!');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Warehouse::find($id)->delete();
        return back()->with('error', 'Not deleted!');
    }
}
