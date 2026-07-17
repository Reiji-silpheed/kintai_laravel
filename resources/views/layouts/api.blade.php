<html>
    <head>
        <title>@yield('title')</title>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    </head>
    <body>
        <div id="app">
            @include('header.header')
            <div class="container">
                <h1>@yield('title')</h1>
                @section('content')
                @show
            </div>
        </div>
    </body>
</html>
