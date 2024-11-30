@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <div class="rectangle-div">
        <div id="user-crate">
            <h1>Categories</h1>
            <h3><a href="{{ route('categories.create') }}">Create Category</a></h3>
        </div>
        <table border="1" id="user-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
            </tr>
            </thead>
            <tbody>
            @foreach($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection