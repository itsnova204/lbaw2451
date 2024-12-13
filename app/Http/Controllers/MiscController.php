<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MiscController extends Controller
{
    public function about()
    {
        return view('pages.misc.about');
    }

    public function features()
    {
        return view('pages.misc.features');
    }
}
