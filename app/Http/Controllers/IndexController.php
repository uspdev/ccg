<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Uspdev\Wsfoto;
use Uspdev\Replicado\Pessoa;

class IndexController extends Controller
{
    public function __construct() {
       $this->middleware('auth')->except(['index']);
    }

    public function index()
    {
        if (Auth::check()) {
            $wsFotoUser = array('foto' => Wsfoto::obter(Auth::user()->id));

            return view('index', compact('wsFotoUser'));
        } 
        
        return view('index');
    }
}
