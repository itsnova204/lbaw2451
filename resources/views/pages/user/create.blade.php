@extends('layouts.app')

@section('title', 'Create User')

@section('content')
    <div class="rectangle-div">
        <h1>Create User</h1>
        <form action="{{ route('user.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-gorup">
                <label for="password-confirm">Confirm Password</label>
                <input id="password-confirm" type="password" name="password_confirmation" required>
            </div>
            <div class="form-group">
                <label for="is_admin">Is Admin</label>
                <input type="checkbox" id="is_admin" name="is_admin">
            </div>
            <button type="submit" class="submit-button">Create</button>
        </form>
    </div>
@endsection