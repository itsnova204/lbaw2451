<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    use HasFactory;

    protected $table = 'rating';

    protected $fillable = [
        'score',
        'comment',
        'auction_id',
        'rater_id',
        'receiver_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // A rating belongs to an auction
    public function auction() : BelongsTo
    {
        return $this->belongsTo(Auction::class, 'auction_id');
    }

    // A rating is given by a user (the rater)
    public function rater() : BelongsTo
    {
        return $this->belongsTo(User::class, 'rater_id');
    }

    // A rating is received by a user (the receiver)
    public function receiver() : BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
