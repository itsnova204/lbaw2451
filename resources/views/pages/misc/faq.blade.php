@extends('layouts.app')

@section('title', 'FAQ')

@section('content')
<div class="container mx-auto p-4">
    <div class="bg-white p-6 rounded-lg shadow">
        <h1 class="text-3xl font-bold mb-4">Frequently Asked Questions</h1>
        <div class="faq-item mb-4">
            <h2 class="text-2xl font-semibold mb-2">What is AuctionPeer?</h2>
            <p>AuctionPeer is an online auction platform where users can bid on various items and services.</p>
        </div>
        <div class="faq-item mb-4">
            <h2 class="text-2xl font-semibold mb-2">How do I create an account?</h2>
            <p>You can create an account by clicking on the "Register" button at the top right corner of the homepage and filling out the registration form.</p>
        </div>
        <div class="faq-item mb-4">
            <h2 class="text-2xl font-semibold mb-2">How do I place a bid?</h2>
            <p>To place a bid, simply navigate to the auction item you are interested in and enter your bid amount in the provided field, then click "Place Bid".</p>
        </div>
        <div class="faq-item mb-4">
            <h2 class="text-2xl font-semibold mb-2">What payment methods are accepted?</h2>
            <p>We accept various payment methods including credit/debit cards, PayPal, and bank transfers.</p>
        </div>
        <div class="faq-item mb-4">
            <h2 class="text-2xl font-semibold mb-2">How do I contact customer support?</h2>
            <p>You can contact our customer support team by emailing us at <a href="mailto:support@auctionpeer.com" class="text-blue-500">support@auctionpeer.com</a>.</p>
        </div>
    </div>
</div>
@endsection