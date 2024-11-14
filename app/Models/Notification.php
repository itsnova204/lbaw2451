<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

//CREATE TYPE notif_type AS ENUM ('generic', 'new_bid', 'bid_surpassed', 'auction_end', 'new_comment', 'report');
enum NotifType: string {
    case Generic = 'generic';
    case NewBid = 'new_bid';
    case BidSurpassed = 'bid_surpassed';
    case AuctionEnd = 'auction_end';
    case NewComment = 'new_comment';
    case Report = 'report';
}

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notification';

    protected $fillable = [
        'text',
        'type',
        'receiver_id',
    ];

    protected $casts = [
        'text' => 'string',
        'type' => NotifType::class,
        'receiver_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user() : BelongsTo {
        return $this->belongsTo(User::class, 'receiver_id');
    }

}
