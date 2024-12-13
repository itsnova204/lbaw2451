@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <div class="rectangle-div">
        <div id="user-crate">
            <h1>Users</h1>
            <h3><a href="{{ route('user.create') }}">Create User</a></h3>
        </div>
        <table border="1" id="user-table">
            <thead>
            <tr>
                <th>ID<br><input type="text" id="filter-id" placeholder="Filter ID"></th>
                <th>Username<br><input type="text" id="filter-username" placeholder="Filter Username"></th>
                <th>Email<br><input type="text" id="filter-email" placeholder="Filter Email"></th>
                <th>Profile Picture</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                @if(!$user->is_deleted)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->email }}</td>
                    <td><img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture" width="50" height="50"></td>
                    <td class="table-flex-row">
                        <!-- Edit Button -->
                        <a href="{{ route('user.edit', $user) }}" class="btn btn-primary btn-sm">Edit</a>

                        <!-- Delete Button -->
                        <form action="{{ route('user.destroy', $user) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>

                        <!-- Block/Unblock Button -->
                        @if($user->status === 'active')
                            <form action="{{ route('users.block', $user) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm">Block</button>
                            </form>
                        @else
                            <form action="{{ route('users.unblock', $user) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Unblock</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endif
            @endforeach
            </tbody>
        </table>
    </div>
@endsection