<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'category_id',
        'creator_id',
        'buyer_id',
        'picture',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'created_at' => 'datetime',
        'updaded_at' => 'datetime',
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

}
