<?php

namespace App\Http\Controllers;

use App\Events\GlobalNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification; 

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


    public function markAsRead(Request $request)
    {
        $user = Auth::user();
        $id = $request->input('id');

        // Validate the UUID
        if (!preg_match('/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/', $id)) {
            return response()->json(['error' => 'Invalid notification ID.'], 400);
        }

        $notification = Notification::findOrFail($id);

        // Ensure the authenticated user is the receiver of the notification
        if ($user->id !== $notification->receiver_id) {
            return response()->json(['error' => 'You are not authorized to perform this action.'], 403);
        }

        $notification->hidden = true;
        $notification->save();

        return response()->json(['success' => 'Notification marked as read.']);
    }
}