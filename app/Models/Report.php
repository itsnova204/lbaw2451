<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use HasFactory;

    protected $table = 'report';

    protected $fillable = [
        'reason',
        'status',
        'auction_id',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Define relationships

    // A report belongs to an auction
    public function auction() : BelongsTo
    {
        return $this->belongsTo(Auction::class, 'auction_id');
    }

    // A report is made by a user
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function status() : string
    {
        $statuses = [
            'not_processed' => 'Not Processed',
            'discarded' => 'Discarded',
            'processed' => 'Processed',
        ];
        return $statuses[$this->status];
    }
}
