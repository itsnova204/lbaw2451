@extends('layouts.app')

@section('title', 'Cards')

@section('content')

    <div class="main-page">
        <div class="filter-section">
            <div class="category">
                <label for="category">Category:</label>
                <select name="category" id="category">
                    <option value="">Select Category</option>
                    <option value="cars">Cars</option>
                    <option value="eletronics">Eletronics</option>
                    <option value="clothing">Clothing</option>
                    <option value="accessories">Accessories</option>
                </select>
            </div>
            <div class="brand">
                <!--option are in brandUpdate.js -->
                <!-- more options will be added in the future-->
                <label for="brand">Brand:</label>
                <select name="brand" id="brand">
                    <option value="">Select Brand</option>
                </select>
            </div>
            <div class="auction-type">
                <label for="auctionType">Auction Type:</label>
                <select name="auctiontype" id="auctiontype">
                    <option value="">Select Type</option>
                    <option value="cars">New</option>
                    <option value="eletronics">Used</option>
                    <option value="clothing">Refurbished</option>
                </select>
            </div>
            <!-- <div class="date">
                <div class="date-from">
                <label for="date_from">Date From:</label>
                <input type="date" name="date_from" id="date_from">
                </div>
                <div class="date-to">
                <label for="date_to">Date To:</label>
                <input type="date" name="date_to" id="date_to">
                </div>
            </div> -->

            <!-- will add price display inm the future// for know just a simple range -->
            <div class="entry-price">
                <label for="price">Entry Price:</label>
                <input type="range" id="price-range" name="price" min="0" max="10000" step="100" value="0">
            </div>

            <div class="current-bid">
                <label for="price">Current Bid:</label>
                <input type="range" id="price-range" name="price" min="0" max="10000" step="100" value="0">
            </div>

            <!-- Submit Button -->
            <button type="submit">Apply Filters</button>
            <a href="#" id="clear-filters" class="clear-filters">Clean filters</a>

        </div>

    </div>

@endsection