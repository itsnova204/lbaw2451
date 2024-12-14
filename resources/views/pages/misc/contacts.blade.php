@extends('layouts.app')

@section('title', 'Contacts')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex flex-col md:flex-row bg-white p-6 rounded-lg shadow">
        <div id="map" class="w-full md:w-1/2 h-64 md:h-auto rounded-lg mb-4 md:mb-0"></div>
        <div class="md:ml-6 w-full md:w-1/2">
            <h1 class="text-3xl font-bold mb-4">Contacts</h1>
            <p>If you have any questions or need further information, please feel free to contact us:</p>
            <ul class="list-disc pl-5 mt-4">
                <li><strong>Email:</strong> <a href="mailto:info@auctionpear.com" class="text-blue-500">info@auctionpear.com</a></li>
                <li><strong>Phone:</strong> (123) 456-7890</li>
                <li><strong>Address:</strong> 123 Main Street, Anytown, USA</li>
            </ul>
            <div class="mt-6">
                <h2 class="text-2xl font-bold mb-2">Office Hours</h2>
                <p>Monday - Friday: 9:00 AM - 5:00 PM</p>
                <p>Saturday: 10:00 AM - 2:00 PM</p>
                <p>Sunday: Closed</p>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var map = L.map('map').setView([40.7128, -74.0060], 13); // Coordinates for New York City

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        L.marker([40.7128, -74.0060]).addTo(map)
            .bindPopup('123 Main Street, Anytown, USA')
            .openPopup();
    });
</script>
@endsection