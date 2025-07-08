<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Tiffany Jewels') }}</title>

    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('style')
</head>
<body class="luxury-home">
    @include('layouts.partials.navigation')

    <main class="py-5">
        @yield('content')
    </main>
<footer>
    @include('layouts.partials.footer')
    </footer>
</body>
</html>
