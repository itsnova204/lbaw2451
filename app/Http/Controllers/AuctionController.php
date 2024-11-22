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
        return redirect()->route('login')->with('error', 'You must be logged in to create an auction.');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Auction::class);
        // Validate the request data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'minimum_bid' => 'required|numeric|min:0',
            'end_date' => 'required|date|after:now',
            'category_id' => 'required|integer|exists:category,id',
        ]);

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
            'category_id' => $validated['category_id'],
            'creator_id' => Auth::id(),
        ]);

        return redirect()->route('auction.index')->with('success', 'Auction created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Auction $auction)
    {
        $this->authorize('view', $auction);

        return view('pages.auction.show', compact('auction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Auction $auction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Auction $auction)
    {
        //
    }

    /**
     * Cancel an auction
     */
    public function cancel(Auction $auction)
    {
        $this->authorize('cancel', $auction);
        return redirect()->route('auction.index')->with('error', 'You cannot delete an auction you do not own');
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

    public function filter(Request $request) { 

        $sortBy = $request->input('sort_by');
        $categoryId = $request->input('category_id'); 
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');

        $query = Auction::where('status','active'); 

        if ($categoryId) { 
            $query->where('category_id',$categoryId);
        }

        if ($minPrice) { 
            $query->where('current_bid','>=',$minPrice);
        }

        if ($maxPrice) { 
            $query->where('current_bid','<=',$maxPrice);
        }

        $auctions = $query->get();

        return response()->json([
            'status' => 'success',
            'auctions' => $auctions,
        ]);
    }
}
