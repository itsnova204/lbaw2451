@extends('layouts.app')

@section('title', 'Create Report')

@section('content')
    <div class="container rectangle-div">
        <h1>Report an Auction</h1>
        <form action="{{ route('report.store') }}" method="POST">
            @csrf
            <input type="hidden" name="auction_id" value="{{ $auction->id }}">
            <input type="hidden" name="user_id" value="{{ Auth::id() }}">
            <div class="form-group">
                <label for="reason">Reason</label>
                <textarea name="reason" id="reason" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Create Report</button>
        </form>
    </div>
@endsection