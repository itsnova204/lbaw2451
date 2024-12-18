<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div class="header-container">
    <div class="header-info">
        <div class="logo">
        <a href="{{ url('/auction') }}" class="logo">
            <div class="logo-img">AP</div>
            <span>AuctionPeer</span>
        </a>
        </div>
        <div class="about">
            <span><a href="{{ route('misc.about') }}">About</a></span>
            <span>Contact</span>
            <span><a href="{{ route('faq') }}">FAQ</a></span>
            <span>Services</span>
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.index') }}" class="admin-panel">Admin Panel</a>
                @endif
                <form action="{{ route('logout') }}" method="GET" id="logout-form">
                    @csrf
                    <span class="submit-button" onclick="document.getElementById('logout-form').submit();">Logout</span>
                </form>
                @if(!auth()->user()->isAdmin())
                        <a href="{{route('user.followed', auth()->user())}}" class="followed">Followed</a>
                        <a href="{{route('user.balance', auth()->user())}}">{{auth()->user()->balance}}€</a>
                    @endif

                    <a href="{{ route('inbox') }}">
                        <div class="select-wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#424242" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-inbox">
                                <path d="M4 4h16v16H4z" />
                                <path d="M22 12H2" />
                                <path d="M7 12l5 5 5-5" />
                            </svg>
                        </div>
                    </a>

                    <a href="{{ route('user.show', auth()->user()) }}">
                    <div class="select-wrapper">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#424242" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-round">
                            <circle cx="12" cy="8" r="5" />
                            <path d="M20 21a8 8 0 0 0-16 0" />
                        </svg>
                    </div>
                </a>
            @endauth

            @guest
                <a href="{{ route('login') }}" class="login-button">Login</a>
            @endguest
        </div>
    </div>

    <div class="search-social">
        <div class="search-container">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="search-icon">
                <circle cx="11" cy="11" r="8" />
                <path d="m21 21-4.3-4.3" />
            </svg>
            <form action="{{ route('search.results', ['query' => request('query')]) }}" method="GET">
                @csrf
                    <input type="search" name="query" id="query" class="form-control" placeholder="Search auctions" required>
            </form>
            </div>
    </div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
</body>

</html>