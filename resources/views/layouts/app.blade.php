<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(auth()->check())
        <meta name="user-id" content="{{ auth()->user()->id }}">
    @endif

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="{{ url('css/milligram.min.css') }}" rel="stylesheet">
    <link href="{{ url('css/app.css') }}" rel="stylesheet">
    <script type="text/javascript">
        // Fix for Firefox autofocus CSS bug
        // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
    </script>
    <script type="text/javascript" src="{{ url('js/app.js') }}" defer></script>
    <script type="text/javascript" src="{{ url('js/clearFilters.js') }}" defer></script>
    <script type="text/javascript" src="{{ url('js/ajaxFilters.js') }}" defer></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js" defer></script>
</head>
<body>
    <div id="app">
        <div id="notifications-container" class="fixed class="fixed bottom-4 right-4 z-50 space-y-4"></div>
        <main>
            <header>
                @include('layouts.header')
            </header>
            <section id="content">
                @yield('content')
            </section>
        </main>

        <footer class="footer bg-light text-center py-3">
            <div class="container">
                <p>Contact us at: <a href="mailto:info@auctionpeer.com">info@auctionpeer.com</a></p>
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</p>
            </div>
        </footer>
    </div>

    @if(auth()->check())
        <!-- Client-Side JavaScript for Pusher Notifications -->
        <script>
            const notificationSound = new Audio('{{ asset('storage/sounds/mixkit-correct-answer-tone-2870.wav') }}');
            document.addEventListener('DOMContentLoaded', function () {
                const userId = document.querySelector('meta[name="user-id"]').getAttribute('content');
                const notificationsContainer = document.getElementById('notifications-container');

                Pusher.logToConsole = true;
  
  

                var pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
                    cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
                    encrypted: true
                });

                var channel = pusher.subscribe('presense-user.' + userId);
                channel.bind('notifications', function(data) {
                    displayNotification(data.message, notificationSound);
                });

                var channelGLOBAL = pusher.subscribe('GLOBAL');
                channelGLOBAL.bind('GLOBAL', function(data) {
                    displayNotification(data.message, notificationSound);
                });
                
            });

            function displayNotification(message, notificationSound) {
                const notificationsContainer = document.getElementById('notifications-container');

                const notification = document.createElement('div');
                notification.classList.add('notification');

                notificationSound.play();

                notification.innerHTML = `
                    <div class="message">${message}</div>
                    <span class="close-btn">&times;</span>
                `;

                notificationsContainer.appendChild(notification);

                notification.querySelector('.close-btn').addEventListener('click', () => {
                    notification.classList.add('slide-out');
                    setTimeout(() => {
                        notification.remove();
                    }, 500); 
                });

                setTimeout(() => {
                    notification.classList.add('slide-out');
                    setTimeout(() => {
                        notification.remove();
                    }, 500); 
                }, 15000);
            }

            //displayNotification('Welcome to AuctionPeer', notificationSound);
        </script>
    @endif
    <script>
        const baseUrl = "{{ url('/') }}";
    </script>
</body>
</html>