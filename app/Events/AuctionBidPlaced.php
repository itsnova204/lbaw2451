<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class AuctionBidPlaced implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $user_to_be_notified;
    public $bidder;
    public $bidAmount;
    public $auction;

    public function __construct($bidder, $bidAmount, $user_to_be_notified, $auction) {
        $this->bidder = $bidder;
        $this->bidAmount = $bidAmount;
        $this->auction = $auction;
        $this->user_to_be_notified = $user_to_be_notified;
        $this->message = $bidder->username . ' placed a bid of ' . $bidAmount . ' on auction: ' . $auction->title;
    }

    public function broadcastOn() {
        return 'presense-user.' . $this->user_to_be_notified['id'];
    }

    public function broadcastAs() {
        return 'notifications';
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message,
        ];
    }
}