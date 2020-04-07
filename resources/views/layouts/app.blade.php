<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    @yield('css')
    <title>@yield('title')</title>
</head>
<body>
@include('layouts.navbar')
<div class="container mt-3">
    @yield('content')
</div>
</body>
@include('layouts.script')
@yield('script')
</html>