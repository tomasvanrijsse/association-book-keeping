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
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <ul class="nav">
                <?php $pages = array(
                        array('name'=>'<i class="icon-home"></i> Home','url'=>''),
                        array('name'=>'<i class="icon-minus-sign"></i> Debet','url'=>'debet'),
                        array('name'=>'<i class="icon-plus-sign"></i> Credit','url'=>'credit'),
                        array('name'=>'<i class="icon-user"></i> Leden','url'=>'leden'),
                        // array('name'=>'Gedeelde posten','url'=>'gedeeldeposten'),
                        // array('name'=>'Ruimtes','url'=>'ruimtes'),
                );
                foreach($pages as $page): ?>
                <li class="<?php echo (Request::segment(1)==$page['url']?'active':'') ?>">
                    <a href="/<?php echo $page['url']; ?>"><?php echo $page['name']; ?></a>
                </li>
                <?php endforeach; ?>
            </ul>
            {{--<ul class="nav" style="float:right;">
                <li class="<?php echo (Request::segment(1)=='import'?'active':'') ?>">
                    <a href="/import">Import</a>
                </li>
            </ul>--}}
        </div>
    </div>
</div>

@yield('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="/js/libs/bootstrap.min.js"></script>
<script src="/js/libs/bootstrap-datepicker.js"></script>
<script src="/js/default.js"></script>
@stack('scripts')

</body>
</html>