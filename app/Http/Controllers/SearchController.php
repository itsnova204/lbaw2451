<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        // Perform your search logic here and get results
        $results = []; // Replace with actual search results

        return view('search', compact('results'));
    }
}
