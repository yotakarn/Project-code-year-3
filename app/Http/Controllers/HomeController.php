<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class HomeController extends Controller
{
    public function view(): View
    {
        return view('home.view'); 
    }
}