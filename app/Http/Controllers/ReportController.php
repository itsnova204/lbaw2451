<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Report::class);
        $reports = Report::all();
        return view('pages.admin.report_index', compact('reports'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Auction $auction)
    {
        $this->authorize('report', $auction);
        return view('pages.report.create', compact('auction'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::debug('Creating report');
        Log::debug($request);
        $validated = $request->validate([
            'reason' => 'required|string',
            'auction_id' => 'required|integer',
            'user_id' => 'required|integer',
        ]);
        Log::debug('Validation passed');
        $this->authorize('report', Auction::find($validated['auction_id']));
        Log::debug('Authorization passed');
        Report::create([
            'reason' => $validated['reason'],
            'auction_id' => $validated['auction_id'],
            'user_id' => $validated['user_id'],
        ]);
        Log::debug('Report created');

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

    public function discard(Report $report)
    {
        $this->authorize('update', $report);
        $report->status = 'discarded';
        $report->save();

        return redirect()->route('admin.reports')->with('success', 'Report discarded successfully');
    }

    public function process(Report $report)
    {
        $this->authorize('update', $report);
        $report->status = 'processed';
        $report->save();

        return redirect()->route('admin.reports')->with('success', 'Report resolved successfully');
    }
}
