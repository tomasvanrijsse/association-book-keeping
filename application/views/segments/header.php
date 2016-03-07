<!doctype html>
<html lang="en">
<head>
    <title><?php print_title(); ?></title>
    <?php print_description(); ?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <?php print_stylesheets(); ?>
</head>
<body>
    <!--[if lt IE 7]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
            <ul class="nav">
                <?php $pages = array(
                    array('name'=>'<i class="icon-home"></i> Home','url'=>'home'),
                    array('name'=>'<i class="icon-minus-sign"></i> Debet','url'=>'debet'),
                    array('name'=>'<i class="icon-plus-sign"></i> Credit','url'=>'credit'),
                    array('name'=>'<i class="icon-user"></i> Leden','url'=>'leden'),
                   // array('name'=>'Gedeelde posten','url'=>'gedeeldeposten'),
                   // array('name'=>'Ruimtes','url'=>'ruimtes'),
                );
                foreach($pages as $page): ?>
              <li class="<?php echo ($this->uri->segment(1)==$page['url']?'active':'') ?>">
                <a href="/<?php echo $page['url']; ?>"><?php echo $page['name']; ?></a>
              </li>
              <?php endforeach; ?>
            </ul>
            <!--<ul class="nav" style="float:right;">
                <li class="<?php echo ($this->uri->segment(1)=='import'?'active':'') ?>">
                    <a href="/import">Import</a>
                </li>
            </ul>-->
        </div>
      </div>
    </div>