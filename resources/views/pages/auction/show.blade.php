@extends('layouts.app')

@section('title', $auction->title)

@section('content')
    <div class="auction-details">
        <div class="auction-image-bids">
            <div class="auction-image-div">
                @if($auction->picture)
                    <img src="{{ asset('storage/' . $auction->picture) }}" alt="{{ $auction->title }}" class="auction-image">
                @else
                    <img src="https://via.placeholder.com/700" alt="{{ $auction->title }}" class="auction-image">
                @endif
            </div>
            <div class="bids">
                <h2><a href="{{ url()->current() }}/bids">Bids</a></h2>
                <p><strong>Number of Bids:</strong> {{ $auction->bids()->count() }}</p>
                <ul>
                    @php
                        $bids = $auction->bids()->get()->reverse()->take(3);
                    @endphp
                    @foreach ($bids as $bid)
                        <li class="bid-item">
                            <div class="bid-info">
                                <span class="bid-username">{{ $bid->user->getUsername() }}</span>
                                <span class="bid-amount">${{ number_format($bid->amount, 2) }}</span>
                            </div>
                            @if($bid->user_id === Auth::id())
                                <form action="{{ route('bids.withdraw', ['auction' => $auction->id, 'bid' => $bid->id]) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">Withdraw Bid</button>
                                </form>
                            @endif
                            <div class="bid-date">{{ $bid->created_at->format('Y-m-d H:i') }}</div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="auction-body">
            <div class="auction-title-bid">
                <div id="auction-name-star">
                    <span>
                    {{ $auction->title }}
                    </span>
                    <span>
                        @if($auction->followers()->where('user_id', auth()->id())->exists())
                            <form action="{{ route('auction.unfollow', $auction) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-secondary">Followed</button>
                        </form>
                        @else
                            <form action="{{ route('auction.follow', $auction) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">Follow</button>
                        </form>
                        @endif
                    </span>
                </div>
                <div id="auction-seller-report">
                    <span class="auction-seller">
                        Seller: <a href="{{url('/user/' . $auction->creator->id)}}">{{$auction->creator->getUsername()}}</a>
                    </span>
                    <span>
                        <a href="{{ route('report.create', $auction) }}">Report Auction</a>
                    </span>
                </div>
                @if($user && $auction->creator_id == $user->id)
                    <a href="{{ route('auction.edit', $auction) }}" class="edit-auction">Edit Auction</a>
                    <a href="{{route('auction.cancel', $auction)}}" class="delete-auction">Cancel Auction</a>
                @endif
            </div>
            <div class="auction-description">
                <p>{{ $auction->description }}</p>
            </div>
            <div class="auction-info">
                <div class="left">
                    <p><strong>Start Date:</strong> {{ $auction->start_date }}</p>
                    <p><strong>End Date:</strong> {{ $auction->end_date }}</p>
                </div>
                <div class="right">
                    <p><strong>Status:</strong> {{ ucfirst($auction->status) }}</p>
                    <div class="auction-countdown">
                        <p><strong>Time Remaining:</strong> <span id="countdown" data-end-date="{{ $auction->end_date }}"></span></p>
                    </div>
                </div>
            </div>
            <div class="auction-bidding">
                <div class="bidding-division">
                    <h4><strong>Starting Bid</strong></h4>
                    <h5>${{ number_format($auction->minimum_bid, 2) }}</h5>
                </div>
                <div class="vertical"></div>
                <div class="bidding-division">
                    <h4><strong>Current Bid</strong></h4>
                    <h5>${{ number_format($auction->current_bid, 2) }}</h5>
                </div>
                @if($auction->status === 'active')
                    <div class="vertical"></div>
                    <form action="{{ route('auctions.bids.store', $auction) }}" method="post">
                        @csrf
                        <input type="hidden" name="auction_id" value="{{ $auction->id }}">
                        <div class="bid-input">
                            <label for="amount"><h4><strong>Bid</strong></h4></label>
                            <input type="number" name="amount" id="amount" step="0.01" min="{{ $auction->current_bid + 0.01 }}" required>
                            <button type="submit">Place Bid</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection



