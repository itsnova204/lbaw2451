<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'profile_picture',
        'birth_date',
        'address',
        'is_deleted',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
        'birth_date' => 'date',
    ];

    public function comments() : HasMany {
        return $this->hasMany(Comment::class);
    }

    public function reports() : HasMany {
        return $this->hasMany(Report::class);
    }

    public function auctions() : HasMany {
        return $this->hasMany(Auction::class);
    }

    public function bids() : HasMany {
        return $this->hasMany(Bid::class);
    }

    public function ratingsGiven() : HasMany {
        return $this->hasMany(Rating::class, 'rater_id');
    }

    public function ratingsReceived() : HasMany {
        return $this->hasMany(Rating::class, 'receiver_id');
    }

    public function notifications() : HasMany {
        return $this->hasMany(Notification::class);
    }

    public function auctionsBought(): HasMany
    {
        return $this->hasMany(Auction::class, 'buyer_id');
    }

    public function buyerTransactions(): HasManyThrough
    {
        return $this->hasManyThrough(Transaction::class, Auction::class, 'buyer_id', 'auction_id', 'id', 'id');
    }

    public function auctionsCreated(): HasMany
    {
        return $this->hasMany(Auction::class, 'creator_id');
    }

    public function sellerTransactions(): HasManyThrough
    {
        return $this->hasManyThrough(Transaction::class, Auction::class, 'creator_id', 'auction_id', 'id', 'id');
    }
}
