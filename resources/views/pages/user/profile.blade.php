@extends('layouts.app')

@section('content')
<section class="max-w-full mx-auto mt-8 bg-white p-6 rounded-lg shadow-lg flex space-x-8">
    <!-- profile info -->
    <div class="flex-none w-48">
        <div class="bg-gray-300 w-32 h-32 rounded-full mx-auto mb-4"
            style="background-image: url('{{ asset("storage/" . $user->profile_picture) }}'); background-size: cover;">
        </div>
        <div class="text-center">
            <h2 class="text-lg font-semibold">{{ $user->username }}</h2>
            <div class="flex justify-center items-center mt-4">
                @php
                    $rating = round($user->ratingsReceived->avg('score') ?? 0);
                @endphp
                @for ($i = 1; $i <= 5; $i++)
                    <span class="{{ $i <= $rating ? 'text-yellow-400' : 'text-gray-300' }} text-lg">&#9733;</span>
                @endfor
            </div>
            <p class="text-sm text-gray-400 mt-2">Accession Date: {{ $user->created_at->format('d.m.Y') }}</p>
        </div>
    </div>

    <!-- rating form -->
    @auth
    <div class="flex-1 space-y-8">
        <h3 class="text-gray-800 font-semibold mb-4">Rate {{ $user->username }}</h3>
       <form method="POST" action="{{ route('ratings.store', $user->id) }}">
    @csrf
    <!-- Hidden Auction ID -->
    <input type="hidden" name="auction_id" value="{{ $auction->id ?? '' }}">
    <!-- Score Field -->
    <div class="flex items-center space-x-2">
        <label for="score" class="font-medium">Score:</label>
        <select id="score" name="score" required class="border border-gray-300 rounded">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>
    </div>

    <!-- Comment Field -->
    <div class="mt-4">
        <label for="comment" class="block font-medium">Comment:</label>
        <textarea id="comment" name="comment" rows="4" class="w-full border border-gray-300 rounded"></textarea>
    </div>

    

    <!-- Submit Button -->
    <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">Submit Rating</button>
</form>

    </div>
    @endauth
</section>
@endsection
