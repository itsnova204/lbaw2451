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
            <div class="rectangle-div">
                <a href="{{route('admin.reports')}}" class="admin-links"><h2>Manage Reports</h2></a>
            </div>
        </div>
    </div>

    <div class="rectangle-div">
        <h2>Send Global Notification</h2>
        <form action="{{ route('send-global-notification') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="message">Notification Message:</label>
                <input type="text" id="message" name="message" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Send Notification</button>
        </form>
    </div>

@endsection