@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
    <div class="rectangle-div reset">
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <label for="email" class="">Email</label>
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="email" name="email" required>
            <label for="password" class="">Password</label>
            <input type="password" name="password" required>
            <label for="password_confirmation" class="">Confirm Password</label>
            <input type="password" name="password_confirmation" required>
            <button type="submit">Reset Password</button>
        </form>
    </div>

@endsection