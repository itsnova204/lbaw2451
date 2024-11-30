<?php

namespace App\Policies;

use App\Models\Auction;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AuctionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any auctions.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the auction.
     */
    public function view(?User $user, Auction $auction): bool
    {
        return $auction->status === 'active';
    }

    /**
     * Determine whether the user can create auctions.
     */
    public function create(User $user): bool
    {
        return $user !== null || !$user->isAdmin();
    }

    /**
     * Determine whether the user can update the auction.
     */
    public function update(User $user, Auction $auction): bool
    {
        return $user->id === $auction->creator_id;
    }

    /**
     * Determine whether the user can delete the auction.
     */
    public function delete(User $user, Auction $auction): bool
    {
        return false;
    }

    /**
     * Determine whether the user can cancel the auction.
     */
    public function cancel(User $user, Auction $auction): bool
    {
        return $user->id === $auction->creator_id || $user->isAdmin();
    }

    public function viewAdmin(User $user): bool
    {
        return $user->isAdmin();
    }
}