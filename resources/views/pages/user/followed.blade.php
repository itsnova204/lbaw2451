@extends('layouts.app')

@section('title', 'Followed')

@section('content')
    <div class="rectangle-div">
        <h1>{{ $user->username }}'s Followed Auctions</h1>

        @if($followedAuctions->count() === 0)
            <p>No followed auctions found.</p>
        @else
            <div class="grid grid-cols-3 gap-4">
                @foreach ($followedAuctions as $auction)
                    <a href="{{ route('auction.show', $auction) }}">
                        <div class="bg-gray-100 p-4 rounded shadow">
                            <div class="w-full h-48 bg-gray-300 rounded" style="background-image: url('{{ asset($auction->image_url) }}'); background-size: cover;"></div>
                            <h3 class="mt-2 text-lg font-semibold">{{ $auction->title }}</h3>
                            <p class="text-sm text-gray-500">Current Bid: ${{ $auction->current_bid }}</p>
                        </div>
                    </a>
                @endforeach
            </div>

        @endif
    </div>
@endsection