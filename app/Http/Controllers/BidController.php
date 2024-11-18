<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class BidController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validation for the bid (for example, ensuring the amount is provided)
            $request->validate([
                'auction_id' => 'required|exists:auctions,id',
                'amount' => 'required|numeric|min:0',
            ]);

            // Create a new bid
            $bid = Bid::create([
                'user_id' => auth()->id(),  // Assuming the logged-in user is placing the bid
                'auction_id' => $request->auction_id,
                'amount' => $request->amount,
            ]);

            return response()->json(['message' => 'Bid placed successfully!'], 201);
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
