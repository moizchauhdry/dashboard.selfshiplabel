<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Lead;
use App\Models\User;

class leadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Inertia::render('Leads1', [
            'leads' =>User::when($request->term,function($query,$term){
            $query->where('first_name','LIKE','%'.$term.'%');
            })->where('type','lead')->paginate(1)
         ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('CreateLead');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    $request->validate([
            'first_name' => 'required|string|max:255'
        ]);

        $user = Lead::create([
            'first_name' => $request->first_name
        ]);
         return redirect('leads');
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         $lead = User::find($id);
         return Inertia::render('Lead1', ['lead' => $lead
      ]);       
    }  
      public function Search(Request $request)
    {
       

        $key = $request->search;
        if(!empty($key)){

        $leads = Lead::where('first_name','LIKE',"%{$key}%")
                                    ->orWhere('last_name','LIKE',"%{$key}%")
                                    ->orderBy('id', 'desc')
                                    ->paginate(10);
                                }else{

         $leads = Lead::orderBy('id', 'desc')
                        ->paginate(10);
                                }

        return Inertia::render('Leads', ['leads' => $leads]);
      
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       // dd($request->all());
        if ($request->has('id')) {
            User::find($request->input('id'))->update($request->all());
            return redirect('leads');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
          $lead = User::find($id);
          $lead->delete();
          return redirect('leads');
    }
}
