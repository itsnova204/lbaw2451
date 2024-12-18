<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\User;
use App\Models\Auction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class RatingController extends Controller
{
    public function create($receiverId)
    {
        
    $receiver = User::findOrFail($receiverId);

    // Pass an auction related to this user (example logic)
    $auction = Auction::where('creator_id', $receiverId)->latest()->first();

    if (!$auction) {
        return back()->withErrors(['auction' => 'No auction associated with this seller.']);
    }

    Log::info("Auction found: ", $auction->toArray());
    return view('pages.user.profile', compact('receiver','auction'));
    }


    public function store(Request $request, $receiverId)
    {
        
            Log::info('Before validation', $request->all());
            Log::info('Receiver ID: ' . $receiverId);

            $request->validate([
                'score' => 'required|integer|min:1|max:5',
                'comment' => 'nullable|string|max:500',
                'auction_id' => 'required|exists:auctions,id',
            ]);

            Log::info('Validation passed');


        // Retrieve the auction with auction_id
        $auction = Auction::findOrFail($request->input('auction_id'));

        // Check if the receiver is the creator of the auction (updated to creator_id)
        if ($auction->creator_id != $receiverId) {
            return back()->withErrors(['receiver' => 'The receiver must be the creator of the auction.']);
        }

        // Prevent self-rating
        if ($auction->creator_id == auth()->id()) {
            return back()->withErrors(['self_review' => 'You cannot rate your own auction.']);
        }

        // Create the rating
        Rating::create([
            'score' => $request->input('score'),
            'comment' => $request->input('comment'),
            'rater_id' => auth()->id(),
            'receiver_id' => $receiverId,
            'auction_id' => $request->input('auction_id'),
        ]);



        return redirect()->route('auction.show', $auction->id)
            ->with('success', 'Rating submitted successfully.');
    }
}
