<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;

class PageController extends Controller
{
    /**
     * Show the home of site.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('site.home');
    }
}
