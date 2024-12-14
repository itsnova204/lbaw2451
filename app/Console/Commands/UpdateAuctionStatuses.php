<?php

namespace App\Console\Commands;

use App\Models\Auction;
use App\Models\Bid;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateAuctionStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-auction-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update statuses of auctions that have ended';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        $affected = Auction::where('end_date', '<=', $now)
            ->where('status', 'active') // Assuming ACTIVE is the status of ongoing auctions
            ->get();// Assuming COMPLETED represents ended auctions

        foreach ($affected as $auction) {
            // Set the status to 'ended' once the auction is past its end date
            if (Bid::where('auction_id', $auction->id)->count() === 0) {
                $auction->status = 'ended';
                $auction->save();
                
                //auction ended with no bids, notify the creator
                $this->info("Auction '{$auction->title}' has been updated to 'ended'.");
                event(new AuctionEnded($auction, $auction->creator, $auction->title));
                continue;
            }
            $bid = Bid::where('auction_id', $auction->id)->where('amount', $auction->current_bid)->first();

            $auction->status = 'ended';
            $auction->buyer_id = $bid->user_id;
            $auction->save();

            // Notify the creator of the auction that it has ended
            event(new AuctionEnded($auction, $auction->creator, $auction->title));

            // Notify the buyer of the auction that they have won
            // event(new AuctionWon($auction, $bid->user, $auction->title)); //TODO: Implement this event

            // Notify all other bidders that they have lost
            $bidders = Bid::where('auction_id', $auction->id)->where('user_id', '!=', $bid->user_id)->get();
            foreach ($bidders as $bidder) {
                event(new AuctionLost($auction, $bidder->user, $auction->title));
            }

            $transaction = Transaction::create([
                'amount' => $auction->current_bid,
                'auction_id' => $auction->id,
            ]);

            $this->info("Auction '{$auction->title}' has been updated to 'ended', and the respective transaction has been created.");
        }

        $this->info("$affected auctions have been updated to ENDED.");


    }
}
