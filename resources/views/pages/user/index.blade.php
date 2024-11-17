<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
</head>
<body>
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
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->username }}</td>
            <td>{{ $user->email }}</td>
            <td><img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture" width="50" height="50"></td>
            <td>
                <!-- Example actions: Edit, Delete -->
                <a href="">Edit</a>
                <form action="" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>