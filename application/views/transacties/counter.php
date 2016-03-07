<?php $this->load->view('segments/header'); ?>
<div class="container">
    <div class="row">
        <div class="span6">
            <h3>Tegengestelde transacties</h3>
        </div>
        <div class="span6">
            <ul class="nav nav-pills pull-right" style="margin-top:15px;">
                <li <?php echo ($status==1?'class="active"':'');?> data-status="1" ><a href="/transacties/counter/open">Nieuw</a></li>
                <li <?php echo ($status==2?'class="active"':'');?> data-status="2"><a href="/transacties/counter/verwerkt">Verwerkt</a></li>
                <li <?php echo ($status==0?'class="active"':'');?> data-status="0"><a href="/transacties/counter/verborgen">Verborgen</a></li>
            </ul>
        </div>
    </div>
    <?php foreach($transactieSets as $k => $set):
    if($k>0)echo '<hr><hr>';
    ?>
    <div class="row suggestion" data-id="<?php echo $set->id ;?>">
        <div class="span8">
            <h4><?php echo $set->account->naam; ?> transacties</h4>
            <p>overeenkomsten gevonden op <?php echo $set->gewicht_naam; ?></p>
        </div>
        <div class="span4 hidesuggestion">
            <?php if($status!=0): ?><a href="#" class="hidelink">verberg suggestie</a><?php endif; ?>
        </div>
        <div class="span6">
            <div class="well">
                <strong>Credit (bij)</strong>
                <ul class="table credit">
                <?php foreach($set->transacties as $t):
                    if($t->type!='credit')continue;
                    if($t->sug_status==1){
                        $class = 'btn-inverse';
                        $btnclass = 'icon-white';
                        $liclass = 'class="strike"';
                    } else {
                        $class = ($t->status==0?'disabled':'');
                        $btnclass = '';
                        $liclass = '';
                    } ?>
                    <li data-id="<?php echo $t->id; ?>" <?php echo $liclass ?>>
                        <span>Van: <?php echo $t->van_naam; ?></span>
                        <span><?php echo $t->datum_nl; ?></span>
                        <span><a class="btn btn-mini <?php echo $class;?>" href="#"><i class="<?php echo $btnclass; ?> icon-arrow-right"></i></a></span>
                        <span title="<?php echo $t->description; ?>" class="clear"><?php echo $t->description; ?></span>
                        <span><?php echo prijsify($t->bedrag); ?></span>
                    </li>
                <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="span6">
            <div class="well">
                <strong>Debet (af)</strong>
                <ul class="table debet">
                <?php foreach($set->transacties as $t):
                    if($t->type!='debet')continue;
                    if($t->sug_status==1){
                        $class = 'btn-inverse';
                        $btnclass = 'icon-white';
                        $liclass = 'class="strike"';
                    } else {
                        $class = ($t->status==0?'disabled':'');
                        $btnclass = '';
                        $liclass = '';
                    } ?>
                    <li data-id="<?php echo $t->id; ?>" <?php echo $liclass ?>>
                        <span><a class="btn btn-mini <?php echo $class;?>" href="#"><i class="<?php echo $btnclass; ?> icon-arrow-left"></i></a></span>
                        <span>Naar: <?php echo $t->van_naam; ?></span>
                        <span><?php echo $t->datum_nl; ?></span>
                        <span title="<?php echo $t->description; ?>"><?php echo $t->description; ?></span>
                        <span><?php echo prijsify($t->bedrag); ?></span>
                    </li>
                <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="span12" style="text-align: right;">
           <button type="button" style="opacity: 0.5" class="btn btn-primary">Opslaan</button>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php $this->load->view('segments/footer'); ?>