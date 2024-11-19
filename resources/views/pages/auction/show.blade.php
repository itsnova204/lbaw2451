@extends('layouts.app')

@section('title', $auction->title)

@section('content')
    <div class="auction-details">
        <div class="auction-image-bids">
            <img src="{{ asset('storage/images/a1.webp')}}" alt="{{ $auction->title }}" class="auction-image">
            <div class="bids">
                <h2>Bids</h2>
                <p><strong>Number of Bids:</strong> {{ $auction->bids()->count() }}</p>
                <ul>
                    @foreach ($auction->bids()->get() as $bid)
                        <li class="bid-item">
                            <div class="bid-info">
                                <span class="bid-username">{{ $bid->user->username }}</span>
                                <span class="bid-amount">${{ number_format($bid->amount, 2) }}</span>
                            </div>
                            <div class="bid-date">{{ $bid->created_at->format('Y-m-d H:i') }}</div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="auction-body">
            <div class="auction-title-bid">
                <span>
                    {{ $auction->title }}
                </span>
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
                      <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                    </svg>
                </span>
            </div>
            <div class="auction-description">
                <p>{{ $auction->description }}</p>
            </div>
            <div class="auction-info">
                <p><strong>Start Date:</strong> {{ $auction->start_date }}</p>
                <p><strong>End Date:</strong> {{ $auction->end_date }}</p>
                <p><strong>Status:</strong> {{ ucfirst($auction->status) }}</p>
            </div>
            <div class="auction-bidding">
                <p><strong>Starting Bid:</strong> ${{ number_format($auction->minimum_bid, 2) }}</p>
                <p><strong>Current Bid:</strong> ${{ number_format($auction->current_bid, 2) }}</p>
                <div class="auction-countdown">
                    <p><strong>Time Remaining:</strong> <span id="countdown" data-end-date="{{ $auction->end_date }}"></span></p>
                </div>
                <form action="{{ route('auctions.bids.store', $auction) }}" method="post">
                    @csrf
                    <input type="hidden" name="auction_id" value="{{ $auction->id }}">
                    <div class="bid-input">
                        <label for="amount">Bid Amount:</label>
                        <input type="number" name="amount" id="amount" step="0.01" min="{{ $auction->current_bid + 0.01 }}" required>
                    </div>
                    <button type="submit">Place Bid</button>
                </form>
            </div>
        </div>
    </div>
@endsection