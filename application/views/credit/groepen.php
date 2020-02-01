<?php $this->load->view('segments/header'); ?>
<div class="container">
    <?php if($this->session->flashdata('error')!=''):?>
    <div class="row">
        <div class="span12">
            <div class="alert alert-error" style="margin: 10px 0;">
            <?php echo $this->session->flashdata('error');?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <div class="row">
        <?php print_account_switch(); ?>
        <div class="span6">
            <h3><?php if($groep){ echo $groep->naam; }else{ echo 'Groepen';}?></h3>
        </div>
        <div class="span6 clearfix">
            <ul class="nav nav-pills pull-right" style="margin-top:15px;">
                <li class="active"><a href="/credit/groepen">Groeperen</a></li>
                <li><a href="/credit/groepen_verdelen">Groepen verdelen</a></li>
                <li class="disabled"><a href="#">of</a></li>
                <li><a href="/credit/transacties">Transacties</a></li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="span5">
            <div class="table thead">
                <span style="width:170px">Naam</span>
                <span style="width:80px">Saldo</span>
                <span style="width:80px">Rest</span>
            </div>
            <ul id="budgetten" class="table">
            <?php foreach($creditgroups as $creditgroup): /* @var $creditgroup creditgroup */ ?>
                <li class="dropable <?php echo ($creditgroup->id==$active_groep?'current':''); ?>" data-saldo="<?php echo round ($creditgroup->saldo); ?>" data-id="<?php echo $creditgroup->id; ?>">
                    <span><a href="/credit/groep_detail/<?php echo $creditgroup->id; ?>"><?php echo $creditgroup->naam; ?></a></span>
                    <span><?php  echo prijsify($creditgroup->credit); ?></span>
                    <span class="clearfix saldo"><?php
                    if(round($creditgroup->saldo)==0 && $creditgroup->credit>0){
                        echo '&#10003;&nbsp;';
                    } else {
                        echo prijsify($creditgroup->saldo);
                    }
                    ?></span>
                </li>
            <?php endforeach; ?>
            </ul>
            <div class="page_navigation pagination"></div>
            <hr>
            <button class="btn btn-form">Nieuwe groep toevoegen</button>
            <form action="/credit/addGroep" id="addGroep" method="POST">
                <div class="control-group">
                    <label class="control-label" for="inputNaam">Naam</label>
                    <div class="controls">
                        <input type="text" name="naam" id="inputNaam"/>
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <button type="submit" class="btn">Opslaan</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="span7">
            <div class="table thead">
                <span style="width:76px">Datum</span>
                <span style="width:206px">Beschrijving</span>
                <span style="width:140px">Van</span>
                <span style="width:50px">Bedrag</span>
            </div>
            <ul id="transactions" class="dropable table">
            <?php foreach($transacties as $k => $transactie): /* @var $transactie transactie */ ?>
                <li data-id="<?php echo $transactie->id; ?>" data-bedrag="<?php echo round($transactie->bedrag); ?>">
                    <span><?php echo $transactie->datum_nl; ?></span>
                    <span title="<?php echo $transactie->description; ?>"><?php echo $transactie->description; ?></span>
                    <span title="<?php echo $transactie->van_naam; ?>"><?php echo $transactie->van_naam; ?></span>
                    <span>&euro; <?php echo round($transactie->bedrag); ?></span>
                </li>
            <?php endforeach; ?>
            </ul>
            <div class="page_navigation pagination"></div>
        </div>
    </div>
</div>
<?php $this->load->view('segments/footer'); ?>
