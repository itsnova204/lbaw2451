<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\AuctionBidPlaced;
use App\Events\AuctionBidWithdrawn;
use App\Events\AuctionCancelled;
use App\Events\AuctionEdited;
use App\Events\AuctionEnded;
use App\Events\AuctionFollowed;
use App\Events\GlobalNotification;
use App\Listeners\SendAuctionBidWithdrawnNotification;
use App\Listeners\SendAuctionCancelledNotification;
use App\Listeners\SendAuctionEditedNotification;
use App\Listeners\SendAuctionEndedNotification;
use App\Listeners\SendAuctionFollowedNotification;
use App\Listeners\SendGlobalNotification;
use App\Listeners\SendAuctionBidPlacedNotification;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        AuctionBidPlaced::class => [
            SendAuctionBidPlacedNotification::class,
        ],

        AuctionBidWithdrawn::class => [
            SendAuctionBidWithdrawnNotification::class,
        ],

        AuctionCancelled::class => [
            SendAuctionCancelledNotification::class,
        ],

        AuctionEdited::class => [
            SendAuctionEditedNotification::class,
        ],

        AuctionEnded::class => [
            SendAuctionEndedNotification::class,
        ],

        AuctionFollowed::class => [
            SendAuctionFollowedNotification::class,
        ],

        GlobalNotification::class => [
            SendGlobalNotification::class,
        ],
    ];



    public function boot()
    {
        parent::boot();
    }
}