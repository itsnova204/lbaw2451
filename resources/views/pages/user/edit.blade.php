@extends('layouts.app')

@section('content')

<h1>Edit Profile</h1>
<div class="rectangle-div">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('user.update', $user) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- username -->
        <div>
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $user->username) }}" required>
            @error('username')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- profile picture -->
        <div>
            <label for="profile_picture" class="form-label">Profile Picture</label>
            <input type="file" name="profile_picture" id="profile_picture" class="form-control @error('profile_picture') is-invalid @enderror">
            @error('profile_picture')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- address -->
        <div>
            <label for="address" class="form-label">Address</label>
            <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror">{{ old('address', $user->address) }}</textarea>
            @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>


        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>
@endsection
