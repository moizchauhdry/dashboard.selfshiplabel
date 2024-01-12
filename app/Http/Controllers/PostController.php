<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Inertia\Inertia;
use File;

class PostController extends Controller
{
    //
    public function index(Request $request , $post_url)
    {
        $post = Post::where('post_url','=',$post_url)->first();

        return Inertia::render('post', ['post' => $post]);
    }    

    //
    public function list(Request $request)
    {
        $pages = Post::OrderBy('id','DESC')->paginate(15);

        return Inertia::render('pages/list', ['pages' => $pages]);
    }        

    //
    public function edit(Request $request,$id)
    {
        $post = Post::findOrFail($id);

        return Inertia::render('pages/edit', ['post' => $post]);
    }        

    //
    public function update(Request $request)
    {

        if(isset($request->id)){
            $post = Post::find($request->id);
        } 

        $post->post_title = $request->post_title;
        $post->post_content = $request->post_content;
        $post->save();

        return redirect('pages/list')->with('message', 'Page Has Been Updated Updated Successfully.');
    }        

    public function upload_image(Request $request){
        $request->validate([
            'file' => 'nullable|mimes:jpg,jpeg,png,csv,txt,xlx,xls,pdf|max:2048'
         ]);        

         if($request->file()) {
            $file_name = time().'_'.$request->file->getClientOriginalName();
            $file_path = $request->file('file')->storeAs('uploads', $file_name);
            File::move(storage_path('app/uploads/'.$file_name), public_path('../uploads/'.$file_name) );
        }         
        $file_name = \URL::to('/').'/uploads/'.$file_name;
        $arr = array(
                'status'=>200,
                'responseText'=>'Successfully Uploaded',
                'location'=>$file_name
            );
        echo json_encode($arr);
        // echo 'fine';
    }

    //
    public function add(Request $request)
    {
        return Inertia::render('pages/add');
    }        

    //
    public function save(Request $request)
    {
        $post  = new Post;
        $post->post_title = $request->post_title;
        $post->post_url   =   $this->slugify($request->post_title);
        $post->post_content = $request->post_content;
        $post->pubDate = date('F j, Y h:i:S');        
        $post->post_status = 'Published';    
        $post->save();

        return redirect('pages/list')->with('message', 'Page Has Been Created Successfully.');
    }          
    
    public function slugify($text)
    {
        $text = preg_replace('/[^\\pL\d]+/u', '-', $text); 
        $text = trim($text, '-');
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = strtolower($text);
        $text = preg_replace('/[^-\w]+/', '', $text);
        return $text;
    }


}
