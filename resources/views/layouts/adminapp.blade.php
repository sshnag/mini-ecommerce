<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.partials.styles')
</head>
<body>
    @include('layouts.partials.navigation')

    <main class="py-4">
        @yield('content')
    </main>

    @include('layouts.partials.footer')
    @include('layouts.partials.scripts')
</body>
</html>
