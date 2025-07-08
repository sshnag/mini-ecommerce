<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Tiffany | Login</title>

    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('style')
</head>
<body class="luxury-home">
    @include('layouts.partials.navigation')

    <main class="flex-grow">
        @yield('content')
    </main>

    @include('layouts.partials.footer')
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    @stack('scripts')
</body>
</html>
