<!doctype html>
<html lang="en">
<head>
    <title>{{ $title ?? 'Association Book Keeping' }}</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    {{ $styleSheets }}
</head>
<body>
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <ul class="nav">
                <?php $pages = [
                    array('name'=>'<i class="icon-homeController"></i> Home','url'=>'homeController'),
                    array('name'=>'<i class="icon-minus-sign"></i> Debet','url'=>'debet'),
                    array('name'=>'<i class="icon-plus-sign"></i> Credit','url'=>'credit'),
                    array('name'=>'<i class="icon-list"></i> Budgetten','url'=>'budgetten'),
                ];?>
                @foreach($pages as $page)
                <li class="{{ ($this->uri->segment(1)==$page['url']?'active':'') }}">
                    <a href="/{{ $page['url'] }}">{{ $page['name'] }}</a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

{{ $slot }}

{{ $scripts }}
</body>
</html>
