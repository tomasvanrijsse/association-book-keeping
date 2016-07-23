<div class="table thead">
    <span style="width:76px">Datum</span>
    <span style="width:260px">Beschrijving</span>
    <span style="width:76px">Bedrag</span>
</div>
<ul id="betalingen" class="table">
<?php 
foreach($transacties as $transactie): /* @var $transactie transactie */ ?>
    <li>
        <span><?php echo $transactie->datum_nl; ?></span>
        <span class="singleline" title="<?php echo $transactie->description; ?>"><?php echo $transactie->description; ?></span>
        <span><?php echo prijsify($transactie->bedrag); ?></span>
    </li>
<?php endforeach; ?>
</ul>
<div class="page_navigation pagination"></div>