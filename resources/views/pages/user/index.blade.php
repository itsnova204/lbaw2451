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
                        <!-- Example actions: Edit, Delete -->
                        <a href="{{route('user.edit', $user)}}">Edit</a>
                        <form action="{{route('user.destroy', $user)}}" method="POST" >
                            @csrf
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
                @endif
            @endforeach
            </tbody>
        </table>
    </div>

@endsection