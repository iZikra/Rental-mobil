<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-[#FAFAFA]">
        <div class="min-h-screen flex flex-col justify-center items-center pt-6 sm:pt-0 gap-8">
            
            <div>
                <a href="/">
                    <img src="{{ asset('img/logo1.png') }}" alt="Logo" class="h-24 w-auto object-contain">
                </a>
            </div>

            <div class="w-full sm:max-w-[400px] bg-white border border-gray-200 rounded-lg shadow-sm p-8 animate-page">
                {{ $slot }}
            </div>

            <div class="bg-gray-200 p-1 rounded-lg flex items-center shadow-inner">
                
                <!-- <a href="{{ route('login') }}" 
                   class="{{ request()->routeIs('login') ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }} px-8 py-1.5 rounded-md text-sm font-bold transition-all duration-200">
                   Sign in
                </a> -->

                <a href="{{ route('register') }}" 
                   class="{{ request()->routeIs('register') ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-700' }} px-8 py-1.5 rounded-md text-sm font-bold transition-all duration-200">
                   Register
                </a>
            </div>

        </div>
    </body>
</html>