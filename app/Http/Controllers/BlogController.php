<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Post;

class BlogController extends Controller
{

    protected $post = '';

    public function __construct(Post $post)
    {
        $this->post = $post;
    }    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function gridBlogs(Request $request)
    {
        $posts = $this->post->where('post_category','like','%Hot Updates%')->take(4)->get();

        $blogs = [];

        foreach($posts as $key => $blog){
            $temp = [];
            $temp['pubDate']    = $blog['pubDate'];
            $temp['id']    = $blog['id'];
            $temp['post_title'] = $blog['post_title'];
            $temp['post_url'] = $blog['post_url'];
            $temp['post_category'] = explode(',',$blog['post_category']);

            $temp['post_image'] = '/uploads/'.$blog['post_image'];
            
            $temp['post_content'] = substr($this->remove_html_comments(strip_tags($blog['post_content'])),0,150);
            $blogs[] = $temp;
        }
        return $this->sendResponse($blogs, 'Grid Posts list');        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function recentBlogs(Request $request)
    {
        $posts = $this->post->latest()->take(5)->get();
        $blogs = [];
        foreach($posts as $key => $blog){
            $temp = [];
            $temp['pubDate']    = $blog['pubDate'];
            $temp['id']    = $blog['id'];
            $temp['post_title'] = $blog['post_title'];
            $temp['post_url'] = $blog['post_url'];
            $temp['post_category'] = explode(',',$blog['post_category']);
            $temp['post_image'] = '/uploads/'.$blog['post_image'];

            $blogs[] = $temp;
        }
        return $this->sendResponse($blogs, 'Grid Posts list');        
    }    

/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function popularBlogs(Request $request)
    {
        $posts = $this->post->where('post_category','like','%Business%')->take(5)->get();
        $blogs = [];
        foreach($posts as $key => $blog){
            $temp = [];
            $temp['pubDate']    = $blog['pubDate'];
            $temp['id']    = $blog['id'];
            $temp['post_url'] = $blog['post_url'];
            $temp['post_title'] = $blog['post_title'];
            $temp['post_category'] = explode(',',$blog['post_category']);

            $temp['post_image'] = '/uploads/'.$blog['post_image'];

            $blogs[] = $temp;
        }
        return $this->sendResponse($blogs, 'Grid Posts list');        
    }   
    
/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function mainBlogs(Request $request)
    {
        $posts = $this->post->where('post_category','like','%Economics%')->take(8)->get();
        $blogs = [];
        foreach($posts as $key => $blog){
            $temp = [];
            $temp['pubDate']    = $blog['pubDate'];
            $date = substr($blog['pubDate'],5,2);
            $month = substr($blog['pubDate'],8,3);
            $temp['post_url'] = $blog['post_url'];

            $temp['date'] = $date;
            $temp['month'] = $month;

            $temp['id']    = $blog['id'];
            $temp['post_title'] = $blog['post_title'];

            $temp['post_category'] = explode(',',$blog['post_category']);

            $temp['post_image'] = '/uploads/'.$blog['post_image'];

            $temp['post_content'] = substr($this->remove_html_comments(strip_tags($blog['post_content'])),0,300);    

            $blogs[] = $temp;
        }
        return $this->sendResponse($blogs, 'Grid Posts list');        
    }       

/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function featuredBlogs(Request $request)
    {
        $posts = $this->post->where('post_category','like','%Stockwatch%')->take(1)->get();
        $blogs = [];
        foreach($posts as $key => $blog){
            $temp = [];
            $temp['pubDate']    = $blog['pubDate'];
            $date = substr($blog['pubDate'],5,2);
            $month = substr($blog['pubDate'],8,3);
            $temp['post_url'] = $blog['post_url'];

            $temp['date'] = $date;
            $temp['month'] = $month;

            $temp['id']    = $blog['id'];
            $temp['post_title'] = $blog['post_title'];

            $temp['post_category'] = explode(',',$blog['post_category']);

            $temp['post_image'] = '/uploads/'.$blog['post_image'];

            $temp['post_content'] = substr($this->remove_html_comments(strip_tags($blog['post_content'])),0,120);    

            $blogs[] = $temp;
        }
        return $this->sendResponse($blogs, 'Grid Posts list');        
    }       


    public function remove_html_comments($content = '') {
        return preg_replace('/<!--(.|\s)*?-->/', '', $content);
    }

}
