<!--
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token 
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles 
        <link href="{{ url('css/milligram.min.css') }}" rel="stylesheet">
        <link href="{{ url('css/app.css') }}" rel="stylesheet">
        <script type="text/javascript">
            // Fix for Firefox autofocus CSS bug
            // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
        </script>
        <script type="text/javascript" src={{ url('js/app.js') }} defer>
        </script>
    </head>
    <body>
        <main>
            <header>
                <h1><a href="{{ url('/cards') }}">Thingy!</a></h1>
                @if (Auth::check())
                    <a class="button" href="{{ url('/logout') }}"> Logout </a> <span>{{ Auth::user()->name }}</span>
                @endif
            </header>
            <section id="content">
                @yield('content')
            </section>
        </main>
    </body>
    
</html>
-->

<!-- resources/views/profile.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans antialiased">
    <!-- Navbar -->
    <header class="bg-white shadow-sm p-4 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <img src="logo.png" alt="Logo" class="h-8 w-auto">
            <h1 class="text-lg font-semibold">AuctionPeer</h1>
        </div>
        <div class="flex items-center space-x-8">
            <a href="#" class="text-gray-600 hover:text-black">About</a>
            <a href="#" class="text-gray-600 hover:text-black">Contact</a>
            <a href="#" class="text-gray-600 hover:text-black">FAQ</a>
            <a href="#" class="text-gray-600 hover:text-black">Services</a>
            <button class="text-gray-600 hover:text-black">
                <i class="fas fa-user-circle text-2xl"></i>
            </button>
        </div>
    </header>

    <!-- Search Bar -->
    <div class="bg-gray-100 p-4">
        <div class="max-w-xl mx-auto">
            <input type="text" placeholder="Search items" class="w-full p-3 rounded-lg border border-gray-300 focus:ring focus:ring-indigo-300">
        </div>
    </div>

    <!-- Profile Section -->
    <section class="max-w-10xl mx-auto mt-8 bg-white p-6 rounded-lg shadow-lg flex space-x-8">
        <!-- Profile Info -->
        <div class="flex-none w-48">
            <div class="bg-gray-300 w-32 h-32 rounded-full mx-auto mb-4"></div>
            <div class="text-center">
                <h2 class="text-lg font-semibold">Profile Name</h2>
                <p class="text-gray-500 text-sm">#0967g</p>
                <p class="text-gray-600 mt-2">Brief description or biography (who you are, interests, specialty in auctions).</p>
                <div class="flex justify-center items-center mt-4">
                    <span class="text-yellow-400 text-lg">&#9733;</span>
                    <span class="text-yellow-400 text-lg">&#9733;</span>
                    <span class="text-yellow-400 text-lg">&#9733;</span>
                    <span class="text-yellow-400 text-lg">&#9733;</span>
                    <span class="text-gray-300 text-lg">&#9733;</span>
                </div>
                <p class="text-sm text-gray-400 mt-2">Accession Date: 02.10.2024</p>
                <a href="#" class="text-blue-500 hover:underline mt-4 inline-block">Log Out</a>
            </div>
        </div>

        <!-- Auction Sections -->
        <div class="flex-1 space-y-8">
            <!-- Navigation Tabs -->
            <div class="flex justify-between">
                <div class="flex space-x-6">
                    <button class="text-gray-600 font-semibold px-3 py-1.5 border-b-2 border-transparent hover:border-gray-800">Wishlist</button>
                    <button class="text-gray-600 font-semibold px-3 py-1.5 border-b-2 border-transparent hover:border-gray-800">Payment Methods</button>
                    <button class="text-gray-600 font-semibold px-3 py-1.5 border-b-2 border-transparent hover:border-gray-800">Invoices and Receipts</button>
                </div>
                <button class="text-white bg-blue-600 px-4 py-2 rounded-lg hover:bg-blue-500">Create New Auction</button>
            </div>

            <!-- Auction Lists -->
            <div class="grid grid-cols-3 gap-4">
                <!-- Active Auctions -->
                <div class="bg-gray-100 p-4 rounded-lg shadow">
                    <h3 class="text-gray-800 font-semibold mb-4">Active Auctions</h3>
                    <ul class="space-y-4">
                        @for ($i = 0; $i < 3; $i++)
                        <li class="flex space-x-3">
                            <div class="w-16 h-16 bg-gray-300 rounded"></div>
                            <div class="flex-1">
                                <h4 class="text-sm font-semibold">Name of product</h4>
                                <p class="text-xs text-gray-500">Starting price: $500 | Current price: $800</p>
                                <p class="text-xs text-gray-500">Ends: 12.10.2024</p>
                            </div>
                        </li>
                        @endfor
                    </ul>
                    <a href="#" class="text-blue-500 hover:underline block mt-2 text-center">View More</a>
                </div>

                <!-- Won Auctions -->
                <div class="bg-gray-100 p-4 rounded-lg shadow">
                    <h3 class="text-gray-800 font-semibold mb-4">Won Auctions</h3>
                    <ul class="space-y-4">
                        @for ($i = 0; $i < 3; $i++)
                        <li class="flex space-x-3">
                            <div class="w-16 h-16 bg-gray-300 rounded"></div>
                            <div class="flex-1">
                                <h4 class="text-sm font-semibold">Name of product</h4>
                                <p class="text-xs text-gray-500">Final price: $650</p>
                                <p class="text-xs text-gray-500">Purchased on: 12.10.2024</p>
                            </div>
                        </li>
                        @endfor
                    </ul>
                    <a href="#" class="text-blue-500 hover:underline block mt-2 text-center">View More</a>
                </div>

                <!-- My Auction Offers -->
                <div class="bg-gray-100 p-4 rounded-lg shadow">
                    <h3 class="text-gray-800 font-semibold mb-4">My Auction Offers</h3>
                    <ul class="space-y-4">
                        @for ($i = 0; $i < 3; $i++)
                        <li class="flex space-x-3">
                            <div class="w-16 h-16 bg-gray-300 rounded"></div>
                            <div class="flex-1">
                                <h4 class="text-sm font-semibold">Name of product</h4>
                                <p class="text-xs text-gray-500">Highest offer: $600</p>
                                <p class="text-xs text-gray-500">Highest bid: $650</p>
                            </div>
                        </li>
                        @endfor
                    </ul>
                    <a href="#" class="text-blue-500 hover:underline block mt-2 text-center">View More</a>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
