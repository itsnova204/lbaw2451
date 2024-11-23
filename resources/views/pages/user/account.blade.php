@extends('layouts.app')


@section('content')


<section class="max-w-full mx-auto mt-8 bg-white p-6 rounded-lg shadow-lg flex space-x-8">
    <!-- profile info -->
    <div class="flex-none w-48">
        <div class="bg-gray-300 w-32 h-32 rounded-full mx-auto mb-4" style="background-image: url('{{ asset($user->profile_picture) }}'); background-size: cover;"></div>
        <div class="text-center">
            <h2 class="text-lg font-semibold">{{ $user->username }}</h2>
            <div class="flex justify-center items-center mt-4">
                @php
                    $rating = round($user->ratingsReceived->avg('rating') ?? 0);
                @endphp
                @for ($i = 1; $i <= 5; $i++)
                    <span class="{{ $i <= $rating ? 'text-yellow-400' : 'text-gray-300' }} text-lg">&#9733;</span>
                @endfor
            </div>
            <p class="">Joined in: {{ $user->created_at->format('d.m.Y') }}</p>
            <a href="{{ route('user.edit', $user) }}" > Edit Profile </a>
            <a href="{{ route('logout') }}" class="mt-4 inline-block">Log Out</a>
        </div>
    </div>

    <!-- auction sections -->
    <div class="flex-1 space-y-8">

        <button onClick="window.location='{{ route('auctions.create') }}'">Create New Auction</button>

        <!-- auction lists -->
        <div class="grid grid-cols-3 gap-4">

            <!-- active auctions -->
            <div class="bg-gray-100 p-4 rounded-lg shadow">
                <h3 class="text-gray-800 font-semibold mb-4">My Active Auctions</h3>
                <ul class="space-y-4">
                    @if ($user->auctionsCreated->isEmpty())
                        <p> This user has not created any auctions at the moment. </p>
                    @else
                        @foreach ($user->paginatedAuctionsCreated(3) as $auction)
                        <li class="flex space-x-3">
                            <div class="w-16 h-16 bg-gray-300 rounded" style="background-image: url('{{ asset($auction->image_url) }}'); background-size: cover;"></div>
                            <a href="{{route('auction.show', $auction)}}">
                            <div class="flex-1">
                                <h4 class="text-sm font-semibold">{{ $auction->title }}</h4>
                                <p class="text-xs text-gray-500">Starting price: ${{ $auction->minimum_bid }}</p>
                                <p class="text-xs text-gray-500">Ends: {{ $auction->end_date->format('d.m.Y') }}</p>
                            </div>
                            </a>
                        </li>
                        @endforeach
                        <a href="{{ route('user.auctions', $user) }}" > See all {{ $user->auctionsCreated->count() }} </a>
                    @endif
                </ul>
            </div>

            <!-- won auctions -->
            <div class="bg-gray-100 p-4 rounded-lg shadow">
                <h3 class="text-gray-800 font-semibold mb-4">My Won Auctions</h3>
                <ul class="space-y-4">
                    @if ($user->auctionsBought->isEmpty())
                        <p> This user has not won any auctions at the moment. </p>
                    @else
                        @foreach ($user->paginatedAuctionsBought(3) as $auction)
                        <li class="flex space-x-3">
                            <div class="w-16 h-16 bg-gray-300 rounded" style="background-image: url('{{ asset($auction->image_url) }}'); background-size: cover;"></div>
                            <div class="flex-1">
                                <h4 class="text-sm font-semibold">{{ $auction->title }}</h4>
                                <p class="text-xs text-gray-500">Final price: ${{ $auction->current_bid }}</p>
                                <p class="text-xs text-gray-500">Purchased on: {{ $auction->purchase_date->format('d.m.Y') }}</p>
                            </div>
                        </li>
                        @endforeach
                        <a href="{{ route('user.won-auctions', $user) }}" > See all {{ $user->auctionsBought->count() }} </a>
                    @endif
                </ul>
            </div>

            <!-- my auction offers -->
            <div class="bg-gray-100 p-4 rounded-lg shadow">
                <h3 class="text-gray-800 font-semibold mb-4">My Auction Offers</h3>
                <ul class="space-y-4">
                    @if ($user->bids->isEmpty())
                        <p> This user has no placed bids at the moment. </p>
                    @else
                        @foreach ($user->paginatedBids(3) as $bid)
                        <li class="flex space-x-3">
                            <div class="w-16 h-16 bg-gray-300 rounded" style="background-image: url('{{ asset($bid->auction->image_url) }}'); background-size: cover;"></div>
                            <div class="flex-1">
                                <h4 class="text-sm font-semibold">{{ $bid->auction->title }}</h4>
                                <p class="text-xs text-gray-500">Your highest offer: ${{ $bid->amount }}</p>
                                <p class="text-xs text-gray-500">Current highest bid: ${{ $bid->auction->current_highest_bid }}</p>
                            </div>
                        </li>
                        @endforeach
                        <a href="{{ route('user.bids', $user) }}" > See all {{ $user->bids->count() }} </a>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</section>



@endsection
