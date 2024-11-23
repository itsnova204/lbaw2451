@extends('layouts.app')

@section('title', 'Edit Auction')

@section('content')
    <div class="container rectangle-div">
        <h1>Edit Auction</h1>
        <form action="{{ route('auction.update', $auction->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ $auction->title }}" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required>{{ $auction->description }}</textarea>
            </div>
            <div class="form-group">
                <label for="end_date">End Date</label>
                <input type="datetime-local" class="form-control" id="end_date" name="end_date" value="{{ $auction->end_date->format('Y-m-d\TH:i') }}" required>
            </div>
            <div class="form-group">
                <label for="category_id">Category</label>
                <select class="form-control" id="category_id" name="category_id" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $auction->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Auction</button>
        </form>
    </div>
@endsection