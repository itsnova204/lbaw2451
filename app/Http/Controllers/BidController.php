<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BidController extends Controller
{
    public function index($auctionId)
    {
        // Retrieve the auction with its bids
        $auction = Auction::with('bids.user')->findOrFail($auctionId);

        // Return the view with the auction and its bids
        return view('pages.auction.bidding_history', compact('auction'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validation for the bid (for example, ensuring the amount is provided)
            $request->validate([
                'auction_id' => 'required|exists:auction,id',
                'amount' => 'required|numeric|min:0',
            ]);

            $auction = Auction::findOrFail($request->auction_id);
            $user = auth()->user();

            if (!$user) {
                return redirect()->route('login')->with('error', 'You must be logged in to place a bid.');
            }

            if ($user->isAdmin() || $user->id === $auction->creator_id) {
                return redirect()->back()->with('error', 'You cannot bid on this auction.');
            }

            if ($request->amount <= $auction->current_bid) {
                return response()->json(['error' => 'Bid amount must be higher than the current bid.'], 400);
            }

            // Create a new bid
            $bid = Bid::create([
                'user_id' => auth()->id(),  // Assuming the logged-in user is placing the bid
                'auction_id' => $request->auction_id,
                'amount' => $request->amount,
            ]);

            // Update the current bid on the auction
            $auction = $bid->auction;
            $auction->current_bid = $bid->amount;
            // Save the auction with the new current bid
            $auction->save();

            //we dont need this, we need to return to the place we were, no?
            return redirect()->back()->with('success', 'Bid placed successfully!');
            //return response()->json(['message' => 'Bid placed successfully!'], 201);
        } catch (QueryException $exception) {
            // Handle specific PostgreSQL error codes or messages
            if (str_contains($exception->getMessage(), 'User % already has the highest bid on auction %')) {
                return response()->json(['error' => 'You already have the highest bid on this auction.'], 400);
            }

            if (str_contains($exception->getMessage(), 'Administrators are not allowed to place bids')) {
                return response()->json(['error' => 'Administrators cannot place bids.'], 403);
            }

            if (str_contains($exception->getMessage(), 'You cannot place a bid if you already have the highest bid')) {
                return response()->json(['error' => 'You cannot place a bid if you already have the highest bid.'], 400);
            }
            // For other database errors
            // Log the error for further investigation
            Log::error('An error occurred while placing the bid: ' . $exception->getMessage());
            return response()->json(['error' => 'An error occurred while placing the bid. Please try again later.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bid $bid)
    {
        //not for this project phase
    }
}
