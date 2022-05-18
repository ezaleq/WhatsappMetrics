<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield("title")</title>
    <link rel="stylesheet" rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/kendo.common.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/kendo.metro.min.css') }}">
    <script src="{{ URL::asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ URL::asset('js/kendo.all.min.js') }}"></script>
    <script src="{{ URL::asset('js/kendo.messages.es-AR.js') }}"></script>
</head>

<body>
    <x-navbar>
        <x-navbar_item href="/">
            <i class="fa fa-line-chart"></i>
            Tablero
        </x-navbar_item>
        <x-navbar_item href="/accounts">
            <i class="fa fa-whatsapp"></i>
            Cuentas
        </x-navbar_item>
    </x-navbar>
    <div class="p-3">
        @yield("content")
    </div>
</body>

</html>
