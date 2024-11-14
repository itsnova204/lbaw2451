<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'auction_id',
    ];

    protected $casts = [
        'amount' => 'int',
        'auction_id' => 'int',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function auction(): BelongsTo {
        return $this->belongsTo(Auction::class);
    }

    //NOT WORKING!!!!!!!!!!!Q
    public function buyer(): BelongsTo
    {
        return $this->auction->belongsTo(User::class, 'buyer_id');
    }

    public function seller(): BelongsTo
    {
        return $this->auction->belongsTo(User::class, 'creator_id');
    }
}
