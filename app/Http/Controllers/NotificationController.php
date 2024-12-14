<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    function follow(Request $request) {
        event(new AuctionFollowed($request->id));
    }

    function auctionEnded(Request $request) {
        event(new AuctionEnded($request->id));
    }

}
