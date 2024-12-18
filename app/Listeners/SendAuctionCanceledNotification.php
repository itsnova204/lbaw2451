<?php

namespace App\Listeners;

use App\Events\AuctionCanceled;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class SendAuctionCanceledNotificationNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(AuctionCanceled $event)
    {
        // Retrieve the necessary data from the event
        $receiver_id = $event->user_to_be_notified->id;
        $type = 'auction_canceled';
        $content = $event->message;
        $link = '/auction/' . $event->auction->id;
        $created_at = now();

        // Insert the notification into the database
        DB::table('notifications')->insert([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'receiver_id' => $receiver_id,
            'type' => $type,
            'content' => $content,
            'link' => $link,
            'created_at' => $created_at,
            'hidden' => false,
        ]);
    }
}