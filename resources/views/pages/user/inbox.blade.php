@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Notifications Inbox</h1>
        @foreach ($notifications as $notification)
            <div class="notification-item {{ $notification->hidden ? 'hidden' : '' }}">
                <p>{{ $notification->content }}</p>
                <small>{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</small>
                @if (!$notification->hidden)
                    <form action="{{ route('notifications.markAsRead') }}" method="POST" onsubmit="event.preventDefault(); markAsRead('{{ $notification->id }}');">
                        @csrf
                        <button type="submit">Mark as read</button>
                    </form>
                @endif
            </div>
        @endforeach

        {{-- Pagination links --}}
        {{-- {{ $notifications->links() }} --}}
    </div>

    <script>
        function markAsRead(id) {
            console.log('Full UUID:', id); // Log the full UUID to the console
            fetch('{{ route('notifications.markAsRead') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.error);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
@endsection