<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Store;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StoreController extends Controller
{

    public function index()
    {

        $stores = Store::with(['warehouse', 'country']);

        $search = '';

        if (isset($_GET['search']) && !empty($_GET['search'])) {

            $search = $_GET['search'];

            $stores->where(
                function ($query) use ($search) {

                    return $query
                        ->orWhere('name', 'LIKE', "%$search%")
                        ->orWhere('city', 'LIKE', "%$search%")
                        ->orWhere('zip', 'LIKE', "%$search%")
                        ->orWhere('address', 'LIKE', "%$search%");
                }
            );
        }

        $stores = $stores->paginate(25);
        return Inertia::render('Store/StoreList', [
            'search' => $search,
            'stores' => $stores
        ]);
    }

    public function create()
    {
        $countries = Country::all(['id', 'nicename as name'])->toArray();
        $warehouses = Warehouse::all('id', 'name');

        return Inertia::render('Store/CreateStore', [
            'countries' => $countries,
            'warehouses' => $warehouses
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
            'warehouse_id' => 'required',
            'country_id' => 'required',
            'state' => 'required|string',
            'zip' => 'required|string',
            'city' => 'required|string',
            'address' => 'required|string',
            'pickup_charges' => 'required|string',
        ]);

        $store = new Store();
        $store->name = $validated['name'];
        $store->warehouse_id = $validated['warehouse_id'];
        $store->country_id = $validated['country_id'];
        $store->city = $validated['city'];
        $store->state = $validated['state'];
        $store->zip = $validated['zip'];
        $store->address = $validated['address'];
        $store->pickup_charges = $validated['pickup_charges'];


        $store->save();

        return redirect('store')->with('success', 'Store Created Successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Store $store)
    {

        $countries = Country::all(['id', 'nicename as name'])->toArray();
        $warehouses = Warehouse::all(['id', 'name'])->toArray();

        return Inertia::render('Store/EditStore', [
            'store' => $store,
            'warehouses' => $warehouses,
            'countries' => $countries
        ]);
    }

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

        $store = Store::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string',
            'warehouse_id' => 'required',
            'country_id' => 'required',
            'state' => 'required|string',
            'zip' => 'required|string',
            'city' => 'required|string',
            'address' => 'required|string',
            'pickup_charges' => 'required|string',
        ]);

        $store->name = $validated['name'];
        $store->warehouse_id = $validated['warehouse_id'];
        $store->country_id = $validated['country_id'];
        $store->city = $validated['city'];
        $store->state = $validated['state'];
        $store->zip = $validated['zip'];
        $store->address = $validated['address'];
        $store->pickup_charges = $validated['pickup_charges'];

        $store->save();

        return redirect('store')->with('success', 'Store Updated Successfully!');
    }
}
