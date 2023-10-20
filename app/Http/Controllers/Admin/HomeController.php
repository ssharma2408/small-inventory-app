<?php

namespace App\Http\Controllers\Admin;
use DB;

class HomeController
{
    public function index()
    {
       		
		return view('home');
    }
}
