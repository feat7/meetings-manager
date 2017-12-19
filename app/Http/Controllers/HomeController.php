<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function contacts()
    {
        return view('dashboard.contacts');
    }

    public function meetings()
    {
        return view('dashboard.meetings');
    }

    public function meeting($meeting)
    {
        return view('dashboard.meeting', compact('meeting'));
    }
}
