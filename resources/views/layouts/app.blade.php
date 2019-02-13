<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous"> <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <script>
        window.App = {!! json_encode([
            'signedIn' => Auth()->check(),
            'user' => Auth::user()
        ]) !!};
    </script>
    <style>
        body { padding-bottom:100px; }
        .centered{
            margin: 0 auto;
        }
        .help-block {
            color: red;
        }
        .level {
            display: flex;
            align-items: center;
        }
        .flex {
            flex: 1;
        }
        .mr-1 {
            margin-right: 1em;
        }
        [v-cloak] { display: none; }
    </style>

</head>
<body>
    <div id="app">
        @include('layouts.nav')
        <!-- <div class="col-md-8">
            @include('flash-message')
        </div> -->
        <flash message="{{ session('flash') }}"></flash>
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
