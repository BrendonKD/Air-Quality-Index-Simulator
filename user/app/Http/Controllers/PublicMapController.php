<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicMapController extends Controller
{
    public function show()
    {
        return view('map');
    }
}