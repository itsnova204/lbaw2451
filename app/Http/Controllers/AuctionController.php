<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;


class AuctionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $auctions = Auction::where('status', 'active')->orderBy('created_at', 'desc')->get();
        $categories = Category::all();
        return view('pages.auction.index', compact('auctions', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Auction::class);
        $categories = Category::all();
        return view('pages.auction.create', compact('categories'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::debug('Creating auction');
        $this->authorize('create', Auction::class);
        Log::debug($request);
        // Validate the request data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'minimum_bid' => 'required|numeric|min:0',
            'end_date' => 'required|date|after:now',
            'category_id' => 'required|integer',
        ]);
        Log::debug('Validation passed');
        Log::debug($validated);
        // Create the auction
        Auction::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'minimum_bid' => $validated['minimum_bid'],
            'current_bid' => $validated['minimum_bid'],
            'end_date' => $validated['end_date'],
            'user_id' => Auth::id(), // Owner of the auction
            'start_date' => now(),
            'status' => 'active',
            'category' => $validated['category_id'],
            'creator_id' => Auth::id(),
        ]);

        return redirect()->route('auctions.index')->with('success', 'Auction created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Auction $auction)
    {
        $this->authorize('view', $auction);
        $user = Auth::user();

        return view('pages.auction.show', compact('auction', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Auction $auction)
    {
        $this->authorize('update', $auction);
        $categories = Category::all();

        return view('pages.auction.edit', compact('auction', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Auction $auction)
    {
        $this->authorize('update', $auction);

        // Validate the request data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'end_date' => 'required|date|after:now',
            'category_id' => 'required|integer|exists:category,id',
        ]);

        // Update the auction
        $auction->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'end_date' => $validated['end_date'],
            'category_id' => $validated['category_id'],
        ]);

        $user = Auth::user();
        return redirect()->route('auction.show', $auction)->with('success', 'Auction updated successfully.');
    }

    /**
     * Cancel an auction
     */
    public function cancel(Auction $auction)
    {
        $this->authorize('cancel', $auction);

        $auction->update([
            'status' => 'canceled',
        ]);

        return redirect()->route('auctions.index')->with('success', 'Auction cancelled successfully.');

    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return redirect()->back()->with('error', 'Search query cannot be empty.');
        }

        // Call the search function in the Auction model
        $results = Auction::search($query)->all();

        $categories = Category::all();

        return view('pages.auction.search', compact('results', 'query', 'categories'));
    }

    public function myAuctions()
    {
        // Fetch auctions belonging to the authenticated user
        $auctions = Auction::where('user_id', auth()->id())->get();

        return view('pages.auction.my_auctions', compact('auctions'));
    }

    public function biddingHistory(Auction $auction)
    {
        // Retrieve bids for this auction, ordered by the bid amount or created_at
        $bids = $auction->bids()->orderBy('created_at', 'desc')->get();

        return view('pages.auction.bidding_history', compact('auction', 'bids'));
    }

    public function apiIndex()
    {
        $auctions = Auction::select('auction.title', 'auction.description', 'auction.start_date', 'auction.end_date', 'auction.status', 'auction.minimum_bid', 'auction.current_bid', 'category.name as category_name', 'users.username as user_name')
            ->join('category', 'auction.category_id', '=', 'category.id')
            ->join('users', 'auction.creator_id', '=', 'users.id')
            ->get();

        return response()->json($auctions);
    }

    public function apiShow(Auction $auction)
    {
        $auction = Auction::select('auction.title', 'auction.description', 'auction.start_date', 'auction.end_date', 'auction.status', 'auction.minimum_bid', 'auction.current_bid', 'category.name as category_name', 'users.username as user_name')
            ->join('category', 'auction.category_id', '=', 'category.id')
            ->join('users', 'auction.creator_id', '=', 'users.id')
            ->where('auction.id', $auction->id)
            ->get();

        return response()->json($auction);
    }

    public function filter(Request $request)
{
    // Sanitize inputs and set defaults for the filters
    $sortBy = $request->input('sort_by'); // Default to 'lowest'
    $categoryId = $request->input('category_id'); // Category ID from the request
    $minPrice = $request->input('min_price'); // Default min price
    $maxPrice = $request->input('max_price'); // Default max price

    try {
        $auctions = Auction::query(); // Start with the auction query

        // Apply category filter if category_id is provided
        if ($categoryId) {
            $auctions->where('category_id', $categoryId);
        }

        // Apply price filters
        if ($minPrice) {
            $auctions->where('current_bid', '>=', $minPrice);
        }
        if ($maxPrice) {
            $auctions->where('current_bid', '<=', $maxPrice);
        }

        // Apply sorting
        if ($sortBy === 'highest') {
            $auctions->orderBy('current_bid', 'desc');
        } elseif ($sortBy === 'lowest') {
            $auctions->orderBy('current_bid', 'asc');
        } elseif ($sortBy === 'soonest') {
            $auctions->orderBy('end_date', 'asc');
        }

        $auctions = $auctions->get(); // Get the filtered auctions

        return response()->json([
            'status' => 'success',
            'auctions' => $auctions
        ]);
    } catch (\Exception $e) {
        // Return error response in case of failure
        return response()->json([
            'status' => 'error',
            'message' => 'An error occurred: ' . $e->getMessage()
        ], 500);
    }
}


}
