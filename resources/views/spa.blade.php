<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(config('EVENT_TYPE_THEME') == 'flofest')
    <title>Flofest</title>
    @else
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="{{ asset('img/logo-1-150x150.png') }}" sizes="32x32" />
    <link rel="icon" href="{{ asset('img/logo-1-300x300.png') }}" sizes="192x192" />
    <link rel="apple-touch-icon-precomposed" href="{{ asset('img/logo-1-300x300.png') }}" />
    <meta name="msapplication-TileImage" content="{{ asset('img/logo-1-300x300.png') }}" />
    @endif

    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous"> -->
    <link href="{{ asset('css/normalize.css?v=1.1') }}" rel="stylesheet">
    <link href="{{ asset('css/appv1.css?v=1.1') }}" rel="stylesheet">
</head>

<body class="theme-{{ config('EVENT_TYPE_THEME', 'htf') }}">
    <div id="app">

        <app></app>
    </div>
    <script>
        window.locale = '{{ app()->getLocale() }}';

        window.appConfigs = {
            'theme': "{{ config('EVENT_TYPE_THEME', 'htf') }}"
        }
    </script>

    <script src="{{ mix('js/appv2.js') }}"></script>
</body>

</html>