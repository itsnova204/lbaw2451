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
        <a href="{{ url('/cards') }}" class="logo">
            <div class="logo-img">not</div>
            <span>AuctionPeer</span>
        </a>
        </div>
        <div class="about">
            <span>About</span>
            <span>Contact</span>
            <span>FAQ</span>
            <span>Services</span>
            <div class="select-wrapper">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#424242" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-round">
                        <circle cx="12" cy="8" r="5" />
                        <path d="M20 21a8 8 0 0 0-16 0" />
                    </svg>
                <!-- <label for="fruits">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-round">
                        <circle cx="12" cy="8" r="5" />
                        <path d="M20 21a8 8 0 0 0-16 0" />
                    </svg>
                    User
                </label>

                <select id="user" name={{ Auth::user()->name }}>
                    <option value="Profile">Profile</option>
                    <option value="Logout">Logout</option>
                </select> -->
            </div>
        </div>
    </div>

    <div class="search-social">
        <div class="search-container">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="search-icon">
                <circle cx="11" cy="11" r="8" />
                <path d="m21 21-4.3-4.3" />
            </svg>
            <form action="{{ route('search.results') }}" method="GET">
                @csrf
    
                    <input type="search" name="query" id="query" class="form-control" placeholder="Search auctions" required>

            </form>
            </div>
        
        <div class="socials">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#424242" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-facebook">
                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#424242" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-instagram">
                <rect width="20" height="20" x="2" y="2" rx="5" ry="5" />
                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
                <line x1="17.5" x2="17.51" y1="6.5" y2="6.5" />
            </svg>
        </div>
    </div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>