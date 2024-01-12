<?php

namespace App\Http\Controllers;

use App\Models\CMSPage;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Route;

class CMSPageController extends Controller
{
    //
    public function index(Request $request)
    {
        $cms = CMSPage::OrderBy('id','DESC')->where('status',1)->paginate(15);
        return Inertia::render('pages/list', ['cms' => $cms]);
    }

    public function edit($id){
        $cms = CMSPage::find($id);
        return Inertia::render('pages/edit', ['cms' => $cms]);
    }

    public function update(Request $request){

        $validate = $request->validate([
            'title' => 'required',
            'meta_title' => 'required',
            'meta_description' => 'required',
            'description' => 'required',
            'meta_keywords' => 'required'
        ]);

        $cms = CMSPage::find($request->id);
        $cms->title = $request->title;
        $cms->meta_title = $request->meta_title;
        $cms->meta_description = $request->meta_description;
        $cms->description = $request->description;
        $cms->meta_keywords = $request->meta_keywords;
        $cms->meta_url = $request->meta_url;
        $cms->save();

        return redirect('pages/list')->with('success', 'CMS Page Has Been Updated Updated Successfully.');
    }

    public function show($slug){

        $page = CMSPage::where('slug',$slug)->first();
        if($page != null){
            return Inertia::render('CMSPage',[
                'pageContext' => $page,
                'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
            ]);
        }else{
            return redirect()->back();
        }

    }
}
