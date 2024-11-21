@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <div class="rectangle-div">
        <h1>Users</h1>

        <table border="1">
            <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
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
                        <a href="">Edit</a>
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