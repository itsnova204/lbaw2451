@extends('layouts.app')

@section('title', 'Login')

@section('content')
<section class="flex justify-center items-start p-10 h-screen bg-gray-100">
        <div class="w-full max-w-xl bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-semibold text-center text-gray-800">Login</h2>
            <p class="text-sm text-gray-600 text-center mt-1">Sign in to your AuctionPeer account</p>

            <!-- login form -->
            <form method="POST" action="{{ route('login') }}" class="mt-6">
                @csrf

                <!-- email input -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" placeholder="Enter your email"
                        class="mt-1 w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-indigo-300"
                        value="{{ old('email') }}" required>
                    @error('email')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- password input -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password" placeholder="Enter your password"
                        class="mt-1 w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-indigo-300"
                        required>
                    @error('password')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- remember me -->
                <div class="flex items-center justify-between mb-6">
                    <label for="remember" class="inline-flex items-start">
                        <input type="checkbox" name="remember" id="remember" class="form-checkbox h-4 w-4 text-indigo-600">
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>
                </div>

                <!-- submit button -->
                <button type="submit"
                    class="flex justify-center items-center w-full bg-blue-600 text-white font-semibold p-3 rounded-lg shadow hover:bg-blue-500 transition">
                    Login
                </button>
            </form>

            <!-- divider -->
            <div class="flex items-center justify-between mt-6">
                <span class="w-full border-t border-gray-300"></span>
                <span class="px-3 text-sm text-gray-500">OR</span>
                <span class="w-full border-t border-gray-300"></span>
            </div>

            <!-- sign up link -->
            <div class="text-center mt-6">
                <p class="text-sm text-gray-600">Don't have an account?
                    <a href="{{ route('register') }}" class="text-blue-500 hover:underline">Sign up</a>
                </p>
            </div>
        </div>
    </section>
@endsection