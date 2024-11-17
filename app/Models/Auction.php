<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Auction extends Model
{
    use HasFactory;

    protected $table = 'auction';

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'minimum_bid',
        'current_bid',
        'category_id',
        'creator_id',
        'buyer_id',
        'picture',
        'status',
    ];

    protected $casts = [
        'title' => 'string',
        'description' => 'string',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'minimum_bid' => 'integer',
        'current_bid' => 'integer',
        'created_at' => 'datetime',
        'updaded_at' => 'datetime',
        'status' => 'string',
    ];

    public function category() : BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * The relationship with the User model for the creator of the auction.
     */
    public function creator() : BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * The relationship with the User model for the buyer of the auction.
     */
    public function buyer() : BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function comments() : HasMany {
        return $this->hasMany(Comment::class);
    }

    public function reports() : HasMany {
        return $this->hasMany(Report::class);
    }

    public function bids() : HasMany {
        return $this->hasMany(Bid::class);
    }

    public function highestBid(): HasOne
    {
        return $this->hasOne(Bid::class)->orderBy('amount', 'desc');
    }

    public function rating() : HasOne {
        return $this->hasOne(Rating::class);
    }

    public function transaction() : HasOne {
        return $this->hasOne(Transaction::class);
    }
}
