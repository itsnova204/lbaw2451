@extends('layouts.app')


@section('content')

    <div class="rectangle-div">
        <h1>Admin Panel</h1>
        <div id="admin-links">
            <div class="rectangle-div">
                <a href="{{ route('user.index') }}" class="admin-links"><h2>Users</h2></a>
            </div>
            <div class="rectangle-div">
                <a href="{{route('categories.index')}}" class="admin-links"><h2>Manage Categories</h2></a>
            </div>
            <div class="rectangle-div">
                <a href="{{route('admin.auctions')}}" class="admin-links"><h2>Manage Auctions</h2></a>
            </div>
        </div>
    </div>

@endsection