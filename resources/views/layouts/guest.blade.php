<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Favicon base -->
        <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon/favicon-96x96.png') }}">
        <link rel="shortcut icon" href="{{ asset('favicon/favicon.ico') }}">

        <!-- Web App Manifest -->
        <link rel="manifest" href="{{ asset('favicon/manifest.json') }}">

        <!-- PWA Icons -->
        <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon/web-app-manifest-192x192.png') }}">
        <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('favicon/web-app-manifest-512x512.png') }}">

        <!-- Mobile Web App Meta -->
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="theme-color" content="#ffffff">

        <!-- Scripts -->
        {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
        <link rel="stylesheet" href="{{ asset('/build/assets/app.css') }}">
    </head>
    <body class="font-sans text-gray-900 antialiased">


        

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">

            <div class="w-full sm:max-w-md mb-6 relative">
                 <div class="flex justify-center">
                <a href="/">
                    <x-application-logo class="w-48 fill-current text-gray-500" />
                </a>
            </div>

            <div class="absolute top-50 right-0">
                @if(Route::is('login'))
                    <a
                        href="{{ route('register') }}"
                        class="block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                        {{ __('Register') }}
                    </a>
                @endif
            </div>
            </div>

           


            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>

        <script src="{{ asset('/build/assets/app.js') }}" defer></script>
    </body>
</html>
