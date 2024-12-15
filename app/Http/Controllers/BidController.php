<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

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

            if ($user->balance < $request->amount) {
                return redirect()->back()->with('error', 'Insufficient balance to place the bid.');
            }

            // Create a new bid
            $bid = Bid::create([
                'user_id' => auth()->id(),
                'auction_id' => $request->auction_id,
                'amount' => $request->amount,
            ]);

            // Update the current bid on the auction
            $auction = $bid->auction;
            $user->balance -= $bid->amount;
            $user->save();  // Save the updated balance

            $auction->current_bid = $bid->amount;
            $auction->save();  // Save the auction with the new current bid

            // Return the updated user information, including balance, to update the frontend
            return redirect()->back()->with('success', 'Bid placed successfully!');
        } catch (QueryException $exception) {
            // Handle specific PostgreSQL error codes or messages
            if (str_contains($exception->getMessage(), 'User % already has the highest bid on auction %')) {
                return response()->json(['error' => 'You already have the highest bid on this auction.'], 400);
            }

            if (str_contains($exception->getMessage(), 'Administrators are not allowed to place bids')) {
                return response()->json(['error' => 'Administrators cannot place bids.'], 403);
            }

            if (str_contains($exception->getMessage(), 'You cannot place a bid if you already have the highest bid')) {
                return redirect()->back()->with('error', 'You cannot place a bid if you already have the highest bid.');
            }

            // For other database errors
            Log::error('An error occurred while placing the bid: ' . $exception->getMessage());
            return response()->json(['error' => 'An error occurred while placing the bid. Please try again later.'], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function withdraw($auctionId, $bidId)
    {
        try {
            $bid = Bid::findOrFail($bidId);
            $user = User::find(auth()->id());

            Log::info('User ID: ' . Auth::id());
            Log::info('Bid User ID: ' . $bid->user_id);
            //Ensure the authenticated user owns the bid
            if ($bid->user_id !== Auth::id()) {
                return redirect()->back()->with('error', 'You are not authorized to withdraw this bid.');
            }

            //Perform the withdrawal logic
            $bid->delete();

            //Update the auction's current highest bid if necessary
            $auction = Auction::findOrFail($auctionId);
            $highestBid = $auction->bids()->orderBy('amount', 'desc')->first();
            $auction->current_bid = $highestBid ? $highestBid->amount : $auction->minimum_bid;
            $auction->save();
            $user->balance += $bid->amount;
            $user->save();

            return redirect()->back()->with('success', 'Bid withdrawn successfully!');
        } catch (QueryException $exception) {
            Log::error('An error occurred while withdrawing the bid: ' . $exception->getMessage());
            return redirect()->back()->with('error', 'An error occurred while withdrawing the bid. Please try again later.');
        }
    }
}
