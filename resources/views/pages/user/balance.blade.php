@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="rectangle-div">
        <h1>My Balance</h1>
        <h2>You have a balance of {{$user->balance}}€</h2>
        <a href=""><button>Deposit</button></a>
    </div>
    <div class="rectangle-div">
        <h1>My Transactions</h1>
        <h2>Buying:</h2>
        @foreach($user->buyerTransactions()->get() as $transaction)
            @php($auction = $transaction->auction)
            <div class="rectangle-div transaction red">
                <span>{{$auction->title}}</span>
                <span>{{$transaction->created_at}}</span>
                <span>{{$transaction->amount}}€</span>
            </div>
        @endforeach

        <h2>Selling:</h2>
        @foreach($user->sellerTransactions()->get() as $transaction)
            @php($auction = $transaction->auction)
            <div class="rectangle-div transaction green">
                <span>{{$auction->title}}</span>
                <span>{{$transaction->created_at}}</span>
                <span>{{$transaction->amount}}€</span>
            </div>
        @endforeach
    </div>

@endsection