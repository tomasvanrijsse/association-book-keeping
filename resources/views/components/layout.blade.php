<!doctype html>
<html lang="en">
<head>
    <title>{{ $title ?? 'Association Book Keeping' }}</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <link rel="stylesheet" href="/css/bootstrap.css" />
    <link rel="stylesheet" href="/css/datepicker.css" />
    <link rel="stylesheet" href="/css/style.css" />

    {{ $styleSheets ?? '' }}
</head>
<body>
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <ul class="nav">
                <?php $pages = [
                    array('name'=>'Home','url'=>'home', 'icon'=> 'icon-homeController'),
                    array('name'=>'Debet','url'=>'debet', 'icon'=> 'icon-minus-sign'),
                    array('name'=>'Credit','url'=>'credit', 'icon'=> 'icon-plus-sign'),
                    array('name'=>'Budgetten','url'=>'budgetten', 'icon'=> 'icon-list'),
                ];?>
                @foreach($pages as $page)
                <li class="{{-- ($this->uri->segment(1)==$page['url']?'active':'') --}}">
                    <a href="/{{ $page['url'] }}"><i class="{{ $page['icon'] }}"></i> {{ $page['name'] }}</a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

{{ $slot }}

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="/js/libs/bootstrap.min.js"></script>
<script src="/js/libs/bootstrap-datepicker.js"></script>
<script src="/js/default.js"></script>

{{ $scripts ?? '' }}
</body>
</html>
