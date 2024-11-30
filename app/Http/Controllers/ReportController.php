<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Report::class);
        return view('pages.report.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('report', Auction::class);
        return view('pages.report.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('report', Auction::class);
        $validated = $request->validate([
            'reason' => 'required|string',
            'auction_id' => 'required|integer',
            'user_id' => 'required|integer',
        ]);
        Report::create([
            'reason' => $validated['reason'],
            'auction_id' => $validated['auction_id'],
            'user_id' => $validated['user_id'],
        ]);

        return redirect()->route('auctions.index')->with('success', 'Report submitted successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        //
    }
}
