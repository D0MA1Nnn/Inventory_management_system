<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductUIController extends Controller
{
    public function index()
    {
        return view('index');
    }
}