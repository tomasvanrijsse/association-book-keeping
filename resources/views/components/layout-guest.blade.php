<!doctype html>
<html lang="en">
<head>
    <title>Login - Association Book Keeping</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="/css/bootstrap.css" />

    {{ $styleSheets ?? '' }}
</head>
<body>

{{ $slot }}

{{ $scripts ?? '' }}
</body>
</html>
