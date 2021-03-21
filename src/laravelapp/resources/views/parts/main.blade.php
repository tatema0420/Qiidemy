<!-- Stored in resources/views/parts/main.blade.php -->
<html>
    <head>
        <title>Qiidemy</title>
        <link href="{{ asset('/css/app.css') }}" rel="stylesheet" type="text/css">
        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js"></script>
    </head>
    <body>
        
        @include('parts.header')


        <div class="container">
            @yield('content')
        </div>

        @include('parts.footer')
    </body>
</html>