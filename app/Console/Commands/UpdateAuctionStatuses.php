<?php

namespace App\Console\Commands;

use App\Models\Auction;
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
            ->update(['status' => 'ended']); // Assuming COMPLETED represents ended auctions

        $this->info("$affected auctions have been updated to ENDED.");
    }
}
