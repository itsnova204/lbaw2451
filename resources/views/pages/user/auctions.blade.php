@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $user->username }}'s Created Auctions</h1>

    @if($auctions->isEmpty())
        <p>No auctions found.</p>
    @else
        <div class="grid grid-cols-3 gap-4">
            @foreach ($auctions as $auction)
                <div class="bg-gray-100 p-4 rounded shadow">
                    <div class="w-full h-48 bg-gray-300 rounded" style="background-image: url('{{ asset($auction->image_url) }}'); background-size: cover;"></div>
                    <h3 class="mt-2 text-lg font-semibold">{{ $auction->name }}</h3>
                    <p class="text-sm text-gray-500">Starting price: ${{ $auction->starting_price }}</p>
                    <p class="text-sm text-gray-500">Ends: {{ $auction->end_date->format('d.m.Y') }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $auctions->links() }} <!-- Pagination links -->
        </div>
    @endif
</div>
@endsection
