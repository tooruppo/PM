<?php

namespace App\Http\Controllers\Admin;  // Adminを追加

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; //　追加

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
        $this->middleware('auth:admin');  //変更
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // return view('home');
        return view('admin.home');  //変更
    }
}
