<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Models\Post;

class SearchContoller extends Controller
{
    protected $api_key = 'eeb2d697583a3add8d4c7b38874a52bb';
    
    protected $post = '';

    public function __construct(Post $post)
    {
        $this->post = $post;
    }    

    public function index($keyword=null,Request $request){
        
        $url = 'https://financialmodelingprep.com/api/v3/search?query='.$keyword.'&limit=10&exchange=NASDAQ&apikey='.$this->api_key;
        $searchResults = json_decode(file_get_contents($url));

        return Inertia::render('Search',['searchResults' => $searchResults , 'keyword' => $keyword]);
    }

    public function posts(Request $request){
        
        $posts = $this->post->orderBy('id','DESC')->paginate('16');
        return Inertia::render('ResearchReports',['posts' => $posts]);
    }



}
