<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comment';
    protected $fillable = [
        'text',
        'auction_id',
        'user_id',
    ];

    // The attributes that should be cast to native types
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Define relationships

    // A comment belongs to an auction
    public function auction() : BelongsTo
    {
        return $this->belongsTo(Auction::class, 'auction_id');
    }

    // A comment is made by a user
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
