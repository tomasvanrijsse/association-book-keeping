<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title')</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link href="/css/bootstrap.css" rel="stylesheet">
    <link href="/css/datepicker.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
    @stack('stylesheets')
</head>
<body>

@yield('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="/js/libs/bootstrap.min.js"></script>
<script src="/js/libs/bootstrap-datepicker.js"></script>
<script src="/js/default.js"></script>
@stack('scripts')
</body>
</html>