@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <div class="rectangle-div">
        <div id="user-crate">
            <h1>Auctions</h1>
        </div>
        <table border="1" id="user-table">
            <thead>
            <tr>
                <th>ID<br><input type="text" id="filter-id" placeholder="Filter ID"></th>
                <th>Title<br><input type="text" id="filter-username" placeholder="Filter Title"></th>
                <th>Start Date<br><input type="text" id="filter-email" placeholder="Filter Start Date"></th>
                <th>End Date<br><input type="text" id="filter-date" placeholder="Filter End Date"></th>
                <th>Status<br><input type="text" id="filter-status" placeholder="Filter Status"></th>
                <th>Current Bid<br><input type="text" id="filter-bid" placeholder="Filter Current Bid"></th>
                <th>Category<br><input type="text" id="filter-category" placeholder="Filter Category"></th>
                <th>Creator<br><input type="text" id="filter-creator" placeholder="Filter Creator"></th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($auctions as $auction)
                <tr>
                    <td>{{ $auction->id }}</td>
                    <td>{{ $auction->title }}</td>
                    <td>{{ $auction->start_date }}</td>
                    <td>{{ $auction->end_date }}</td>
                    <td>{{ $auction->status }}</td>
                    <td>{{ $auction->current_bid }}</td>
                    <td>{{ $auction->category->name }}</td>
                    <td><a href="{{route('user.show', $auction->creator)}}">{{ $auction->creator->username }}</a></td>
                    <td id="auction-actions" ">
                        <form action="{{route('auction.cancel', $auction)}}" method="POST">
                            @csrf
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection