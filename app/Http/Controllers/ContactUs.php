<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactUs extends Controller
{
    public function index(){
        return Inertia::render('ContactUs');
    }
}
