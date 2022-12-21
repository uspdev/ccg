<?php

namespace App\Http\Controllers;

class IndexController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    public function index()
    {
        return view('index');
    }
}
