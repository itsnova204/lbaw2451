@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $user->username }}'s Bids</h1>

    @if($bids->isEmpty())
        <p>No bids found.</p>
    @else
        <div class="grid grid-cols-3 gap-4">
            @foreach ($bids as $bid)
                <div class="bg-gray-100 p-4 rounded shadow">
                    <div class="w-full h-48 bg-gray-300 rounded" style="background-image: url('{{ asset($bid->auction->image_url) }}'); background-size: cover;"></div>
                    <h3 class="mt-2 text-lg font-semibold">{{ $bid->auction->name }}</h3>
                    <p class="text-sm text-gray-500">Your bid: ${{ $bid->amount }}</p>
                    <p class="text-sm text-gray-500">Current highest bid: ${{ $bid->auction->current_highest_bid }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $bids->links() }}
        </div>
    @endif
</div>
@endsection
