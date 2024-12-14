<?php

namespace App\Http\Controllers;

use App\Events\GlobalNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function sendGlobalNotification(Request $request)
    {
        // Check if the authenticated user is an admin
        if (!Auth::user()->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Validate the request
        $request->validate([
            'message' => 'required|string|max:255',
        ]);

        // Get the message from the request
        $message = $request->input('message');

        // Dispatch the event
        event(new GlobalNotification($message));

        return redirect()->back()->with('success', 'Message sent successfully');
    }
}