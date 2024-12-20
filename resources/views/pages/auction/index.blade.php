@extends('layouts.app')

@section('title', 'Cards')

@section('content')

    <div class="main-page">
        <div class="filter-section">
            <div id="sort-by">
                <label for="sort-by">Sort By:</label>
                <select name="sort-by" id="sort-select">
                    <option value="">Select Price</option>
                    <option value="lowest">Lowest Price</option>
                    <option value="highest">Highest Price</option>
                    <option value="soonest">Ending Soonest</option>
                </select>
            </div>
            <div class="category">
                <label for="category">Category:</label>
                <select name="category" id="category">
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{$category->id}}">{{$category->name}}</option>
                    @endforeach
                </select>
            </div>

            <!-- will add price display inm the future// for know just a simple range -->
            <div class="entry-price">
                <label for="price">Entry Price:</label>
                <input type="range" id="entry-price-range" name="price" min="0" max="10000" step="100" value="0">
                <span id="entry-price-value">0</span>
            </div>

            <div class="current-bid">
                <label for="price">Current Bid:</label>
                <input type="range" id="current-bid-range" name="price" min="0" max="10000" step="100" value="0">
                <span id="current-bid-value">0</span>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="apply-filters">Apply Filters</button>
            <a href="#" id="clear-filters" class="clear-filters">Clean filters</a>

        </div>
        <div class="cards-container">
            @foreach($auctions as $auction)
                @include('partials.card', ['auction' => $auction])
            @endforeach

        </div>

    </div>
@endsection