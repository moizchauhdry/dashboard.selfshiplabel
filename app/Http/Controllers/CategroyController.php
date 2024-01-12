<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Inertia\Inertia;
use File;

class CategroyController extends Controller
{

    //
    public function add(Request $request)
    {
        return Inertia::render('categories/add');
    }        

    //
    public function save(Request $request)
    {
        $category = new Category;
        $category->name = $request->input('name');
        $category->slug = $this->slugify($request->input('name'));
        $category->status = $request->input('status');
        $category->save();

        return Redirect::route('category_list');
    }        

    //
    public function list(Request $request)
    {
        $categories = Category::OrderBy('id','DESC')->paginate(15);

        return Inertia::render('categories/list', ['categories' => $categories]);
    }        

    //
    public function edit(Request $request,$id)
    {
        $post = Post::findOrFail($id);

        return Inertia::render('blogs/edit', ['post' => $post]);
    }        

    //
    public function update(Request $request)
    {

        $request->validate([
            'post_image' => 'nullable|mimes:jpg,jpeg,png,csv,txt,xlx,xls,pdf|max:2048'
         ]);        
         if(isset($request->id)){
            $post = Post::find($request->id);
        } 
        if($request->file()) {
            $file_name = time().'_'.$request->post_image->getClientOriginalName();
            $file_path = $request->file('post_image')->storeAs('uploads', $file_name);
            File::move(storage_path('app/uploads/'.$file_name), public_path('../uploads/'.$file_name) );
            $post_image = $file_name;
        }else{
            $post_image = $request->post_image;
        }         

        $post->post_title = $request->post_title;
        $post->post_title = $request->post_title;
        $post->post_image = $post_image;
        $post->save();

        return redirect()->back()
            ->with('message', 'Blog Has Been Updated Updated Successfully.');
    }        

    //
    public function delete(Request $request,$id)
    {
        $post = Category::find($id);
        if($post != null)
            $post->delete();
            
        return redirect()->back()
            ->with('message', 'Category Has Been Deleted');
    }        


    
    public function slugify($text)
    {
        // Swap out Non "Letters" with a -
        $text = preg_replace('/[^\\pL\d]+/u', '-', $text); 
    
        // Trim out extra -'s
        $text = trim($text, '-');
    
        // Convert letters that we have left to the closest ASCII representation
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    
        // Make text lowercase
        $text = strtolower($text);
    
        // Strip out anything we haven't been able to convert
        $text = preg_replace('/[^-\w]+/', '', $text);
    
        return $text;
    }
}
