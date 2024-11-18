@extends('layouts.app')


@section('content')

    <!-- Profile Section -->
    <section class="max-w-5xl mx-auto mt-8 bg-white p-6 rounded-lg shadow-lg flex space-x-8">
        <!-- Profile Info -->
        <div class="flex-none w-48">
            <div class="bg-gray-300 w-32 h-32 rounded-full mx-auto mb-4" style="background-image: url('{{ asset($user->profile_picture) }}'); background-size: cover;"></div>
            <div class="text-center">
                <h2 class="text-lg font-semibold">{{ $user->username }}</h2>
                <p class="text-gray-500 text-sm">#{{ $user->id }}</p>
                <p class="text-gray-600 mt-2">{{ $user->bio ?? 'No biography available.' }}</p>
                <div class="flex justify-center items-center mt-4">
                    @php
                        $rating = round($user->ratingsReceived->avg('rating') ?? 0);
                    @endphp
                    @for ($i = 1; $i <= 5; $i++)
                        <span class="{{ $i <= $rating ? 'text-yellow-400' : 'text-gray-300' }} text-lg">&#9733;</span>
                    @endfor
                </div>
                <p class="text-sm text-gray-400 mt-2">Accession Date: {{ $user->created_at->format('d.m.Y') }}</p>
                <a href="{{ route('logout') }}" class="text-blue-500 hover:underline mt-4 inline-block">Log Out</a>
            </div>
        </div>

        <!-- Auction Sections -->
        <div class="flex-1 space-y-8">
            <!-- Navigation Tabs -->
            <div class="flex justify-between">
                <div class="flex space-x-6">
                    <button class="text-gray-600 font-semibold px-3 py-1.5 border-b-2 border-transparent hover:border-gray-800">Wishlist</button>
                    <button class="text-gray-600 font-semibold px-3 py-1.5 border-b-2 border-transparent hover:border-gray-800">Payment Methods</button>
                    <button class="text-gray-600 font-semibold px-3 py-1.5 border-b-2 border-transparent hover:border-gray-800">Invoices and Receipts</button>
                </div>
                <button class="text-white bg-blue-600 px-4 py-2 rounded-lg hover:bg-blue-500">Create New Auction</button>
            </div>

            <!-- Auction Lists -->
            <div class="grid grid-cols-3 gap-4">
                <!-- Active Auctions -->
                <div class="bg-gray-100 p-4 rounded-lg shadow">
                    <h3 class="text-gray-800 font-semibold mb-4">Active Auctions</h3>
                    <ul class="space-y-4">
                        @foreach ($user->auctionsCreated as $auction)
                        <li class="flex space-x-3">
                            <div class="w-16 h-16 bg-gray-300 rounded" style="background-image: url('{{ asset($auction->image_url) }}'); background-size: cover;"></div>
                            <div class="flex-1">
                                <h4 class="text-sm font-semibold">{{ $auction->name }}</h4>
                                <p class="text-xs text-gray-500">Starting price: ${{ $auction->starting_price }}</p>
                                <p class="text-xs text-gray-500">Ends: {{ $auction->end_date->format('d.m.Y') }}</p>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('auctions.active', $user->id) }}" class="text-blue-500 hover:underline block mt-2 text-center">View More</a>
                </div>

                <!-- Won Auctions -->
                <div class="bg-gray-100 p-4 rounded-lg shadow">
                    <h3 class="text-gray-800 font-semibold mb-4">Won Auctions</h3>
                    <ul class="space-y-4">
                        @foreach ($user->auctionsBought as $auction)
                        <li class="flex space-x-3">
                            <div class="w-16 h-16 bg-gray-300 rounded" style="background-image: url('{{ asset($auction->image_url) }}'); background-size: cover;"></div>
                            <div class="flex-1">
                                <h4 class="text-sm font-semibold">{{ $auction->name }}</h4>
                                <p class="text-xs text-gray-500">Final price: ${{ $auction->final_price }}</p>
                                <p class="text-xs text-gray-500">Purchased on: {{ $auction->purchase_date->format('d.m.Y') }}</p>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('auctions.won', $user->id) }}" class="text-blue-500 hover:underline block mt-2 text-center">View More</a>
                </div>

                <!-- My Auction Offers -->
                <div class="bg-gray-100 p-4 rounded-lg shadow">
                    <h3 class="text-gray-800 font-semibold mb-4">My Auction Offers</h3>
                    <ul class="space-y-4">
                        @foreach ($user->bids as $bid)
                        <li class="flex space-x-3">
                            <div class="w-16 h-16 bg-gray-300 rounded" style="background-image: url('{{ asset($bid->auction->image_url) }}'); background-size: cover;"></div>
                            <div class="flex-1">
                                <h4 class="text-sm font-semibold">{{ $bid->auction->name }}</h4>
                                <p class="text-xs text-gray-500">Your highest offer: ${{ $bid->amount }}</p>
                                <p class="text-xs text-gray-500">Current highest bid: ${{ $bid->auction->current_highest_bid }}</p>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('auctions.offers', $user->id) }}" class="text-blue-500 hover:underline block mt-2 text-center">View More</a>
                </div>
            </div>
        </div>
    </section>



@endsection
