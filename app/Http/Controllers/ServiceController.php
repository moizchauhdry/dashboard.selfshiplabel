<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Illuminate\Http\Response;
use Inertia\Inertia;
use App\Models\Service;

class ServiceController extends Controller
{
    
    public function index(){

        $search = '';

        $services = Service::paginate(10);

        return Inertia::render('Services/ServiceList',[
          'search' => $search,
          'services' => $services
        ]);     

    }

        /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {  

        return Inertia::render('Services/CreateService',[

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
          'title' => 'required|string',
          'description' => 'required|string',
          'price' => 'required|numeric|min:0',
      ]);

      $service = new Service();

      $service->title = $validated['title'];
      $service->description = $validated['description'];
      $service->price = $validated['price'];
      $service->status = (boolean) $request->input('status');
      
      $service->save();

      return redirect('services')->with('success', 'Service  Added!');

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $service = Service::find($id);
        
        return Inertia::render('Services/EditService',[
            'service' => $service
        ]);   

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {   
        $id = $request->input('id');

        $service = Service::find($id);

        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);
  
        $service->title = $validated['title'];
        $service->description = $validated['description'];
        $service->price = $validated['price'];
        $service->status = (boolean) $request->input('status');

        $service->update();
  
        return redirect('services')->with('success', 'Service Updated !');

    }


        /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        Service::find($id)->delete();

        return back()->with('success', 'Service deleted!');

        //return response()->json(['status' => TRUE]);

    }
    
}