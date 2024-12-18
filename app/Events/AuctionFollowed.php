<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class AuctionFollowed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $auction_owner_id;
    public $auction;
    public $user_to_be_notified;

    // Here you create the message to be sent when the event is triggered.
    public function __construct($follower, $auction_owner_id, $auction) {
        $this->auction = $auction;
        $this->auction_owner_id = $auction_owner_id;
        $this->user_to_be_notified = $auction_owner_id;
        $this->message = $follower->username . ' just followed your auction: ' . $auction->title;
    }

    // You should specify the name of the channel created in Pusher.
    public function broadcastOn() {
        return 'presense-user.' . $this->auction_owner_id['id'];
    }

    // You should specify the name of the generated notification.
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
