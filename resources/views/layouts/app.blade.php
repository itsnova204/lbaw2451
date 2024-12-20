
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <link href="{{ url('css/milligram.min.css') }}" rel="stylesheet">
        <link href="{{ url('css/app.css') }}" rel="stylesheet">
        <script type="text/javascript">
            // Fix for Firefox autofocus CSS bug
            // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
        </script>
        <script type="text/javascript" src={{ url('js/app.js') }} defer>
        </script>
      
        <script type="text/javascript" src={{ url('js/clearFilters.js') }} defer></script>
        <script type="text/javascript" src={{ url('js/ajaxFilters.js') }} defer></script>
    </head>
    <body>
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
    </body>
    
</html>

<script>
    const baseUrl = "{{ url('/') }}";
</script>

