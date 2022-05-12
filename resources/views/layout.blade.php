<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield("title")</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" integrity="sha512-Oy+sz5W86PK0ZIkawrG0iv7XwWhYecM3exvUtMKNJMekGFJtVAhibhRPTpmyTj8+lJCkmWfnpxKgT2OopquBHA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
            <i class="bi bi-speedometer"></i>
            Tablero
        </x-navbar_item>
        <x-navbar_item href="accounts">
            <i class="bi bi-whatsapp"></i>
            Cuentas
        </x-navbar_item>
    </x-navbar>
    <div class="p-3">
        @yield("content")
    </div>
</body>

</html>
