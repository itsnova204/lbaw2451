@extends('layouts.app')

@section('title', $auction->title)

@section('content')
    <div class="bids" style="height: 100%">
        <div id="bid-and-button">
            <h1>Bids</h1>
            <button class="back-button" onclick="window.history.back()">Back</button>
        </div>
        <h2 id="hist-bid-num"><strong>Number of Bids:</strong> {{ $auction->bids()->count() }}</h2>
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
                    <div class="bid-date">{{ $bid->created_at->format('Y-m-d H:i') }}</div>
                </li>
            @endforeach
        </ul>
    </div>
@endsection