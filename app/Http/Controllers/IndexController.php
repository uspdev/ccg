<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Uspdev\Replicado\Pessoa;

class IndexController extends Controller
{
    public function __construct() {
       $this->middleware('auth')->except(['index']);
    }

    public function index()
    {     
        return view('index');
    }
}
