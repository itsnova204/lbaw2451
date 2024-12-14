<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;


class AuctionEdited implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $user_to_be_notified;

    // Here you create the message to be sent when the event is triggered.
    public function __construct($auction, $user_to_be_notified, $auction_title) {
        $this->user_to_be_notified = $user_to_be_notified;
        $this->message = 'The auction ' . $auction_title . ' has been edited';
    }

    // You should specify the name of the channel created in Pusher.
    public function broadcastOn() {
        return 'notifications-' . $this->user_to_be_notified['id'];
    }

    // You should specify the name of the generated notification.
    public function broadcastAs() {
        return 'notifications';
    }
}
