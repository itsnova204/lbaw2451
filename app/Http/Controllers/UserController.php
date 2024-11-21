<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        //Check if user is logged in and is admin
        if ($user && $user->isAdmin()) {
            $users = User::all();
            return view('pages.user.index', compact('users'));
        }
        abort(403);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $currentUser = auth()->user();
        // Check if the current logged-in user is the same as the user being shown
        if ($currentUser && $currentUser->id === $user->id) {
            // Return the account view for the current user
            return view('pages.user.account', compact('user'));
        } else {
            if ($user->is_deleted) {
                return view('pages.user.deleted');
            }
            // Return the profile view for a different user
            return view('pages.user.profile', compact('user'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $currentUser = auth()->user();
        if (!$currentUser) {
            return redirect()->route('index')->with('error', 'You are not logged in!');
        }
        // Check if the logged-in user is the one trying to edit their profile, or if they are an admin
        if ($currentUser && $currentUser->id !== $user->id && !$currentUser->isAdmin()) {
            return redirect()->route('index')->with('error', 'You do not have permission to edit this user\'s profile.');
        }

        // If the user is authorized, return the edit view
        return view('pages.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $currentUser = auth()->user();
        if (!$currentUser) {
            return redirect()->route('index')->with('error', 'You are not logged in!');
        }
        // Check if the logged-in user is the one trying to edit their profile, or if they are an admin
        if ($currentUser && $currentUser->id !== $user->id && !$currentUser->isAdmin()) {
            return redirect()->route('index')->with('error', 'You do not have permission to edit this user\'s profile.');
        }

        // Validate the request data, restricting to only username, profile_picture, and address
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id, // Exclude current user's username from uniqueness check
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Optional image upload validation
            'address' => 'nullable|string|max:255', // Optional string field for address
        ]);

        // Update the user's profile with the validated data
        $user->update($validated);

        // If the logged-in user is updating their own profile, redirect them to their profile page
        // If an admin is updating someone else's profile, redirect them to the users' index page
        $redirectRoute = ($currentUser->id === $user->id) ? route('index') : route('');

        return redirect($redirectRoute)->with('success', 'User profile updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $currentUser = auth()->user();
        if (!$currentUser) {
            return redirect()->route('auctions.index')->with('error', 'You are not logged in!');
        }
        // Check if the logged-in user is the one trying to delete their profile, or if they are an admin
        if ($currentUser->id !== $user->id && !$currentUser->isAdmin()) {
            return redirect()->route('index')->with('error', 'You do not have permission to delete this user\'s profile.');
        }

        // Delete the user's profile
        $user->deleteUser();

        // If the logged-in user is deleting their own profile, redirect them to the login page
        // If an admin is deleting someone else's profile, redirect them to the users' index page
        $redirectRoute = ($currentUser->id === $user->id) ? route('login') : route('user.index');

        return redirect($redirectRoute)->with('success', 'User profile deleted successfully.');
    }

    public function create() {
        $currentUser = auth()->user();
        if (!$currentUser) {
            return redirect()->route('index')->with('error', 'You are not logged in!');
        }
        if (!$currentUser->isAdmin()) {
            return redirect()->route('index')->with('error', 'You do not have permission to create a new user.');
        }
        return view('pages.user.create');
    }

    public function storeUser(Request $request)
    {
        Log::info("HERE!");
        $currentUser = auth()->user();
        if (!$currentUser) {
            return redirect()->route('auctions.index')->with('error', 'You are not logged in!');
        }
        if (!$currentUser->isAdmin()) {
            return redirect()->route('auctions.index')->with('error', 'You do not have permission to create a new user.');
        }

        // Validate the request data, restricting to only username, email, and password
        $validated = $request->validate([
            'username' => 'required|alpha_num|unique:users,username|min:3|max:20',
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|min:8|confirmed'
        ]);

        $is_admin = $request->has('is_admin');

        DB::enableQueryLog();

        // Create the new user with the validated data
        User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_admin' => $is_admin,
        ]);

        $queries = DB::getQueryLog();
        Log::info($queries);

        return redirect()->route('user.index')->with('success', 'User created successfully.');
    }
}
