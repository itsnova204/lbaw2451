<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\Auction;

class AuctionFollowedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $follower;
    protected $auction;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $follower, Auction $auction)
    {
        $this->follower = $follower;
        $this->auction = $auction;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'follower_id' => $this->follower->id,
            'follower_name' => $this->follower->name,
            'auction_id' => $this->auction->id,
            'auction_title' => $this->auction->title,
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'follower_id' => $this->follower->id,
            'follower_name' => $this->follower->name,
            'auction_id' => $this->auction->id,
            'auction_title' => $this->auction->title,
        ]);
    }
}