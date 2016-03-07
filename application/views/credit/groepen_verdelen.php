<?php $this->load->view('segments/header'); ?>
<div class="container">
    <div class="row">
        <?php print_account_switch(); ?>
        <div class="span6">
            <h3>Budget balans</h3>
        </div>
        <div class="span6">
            <ul class="nav nav-pills pull-right" style="margin-top:15px;">
                <li><a href="/credit/groepen">Groeperen</a></li>
                <li class="active"><a href="/credit/groepen_verdelen">Groepen verdelen</a></li>
                <li class="disabled"><a href="#">of</a></li>
                <li><a href="/credit/transacties">Transacties</a></li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div id="overlay"></div>
        <div class="span6">
            <div class="table thead">
                <span style="width:211px">Naam</span>
                <span style="width:100px">Stand v. toen</span>
                <span style="width:100px">Target</span>
            </div>
            <ul id="budgetten" class="table">
            <?php foreach($budgetten as $budget): /* @var $budget budget */ ?>
                <li id="saldo<?php echo $budget->id; ?>" data-saldo="<?php echo number_format( $budget->saldo,2,'.',''); ?>">
                    <span><?php echo $budget->naam; ?></span>
                    <span class="saldo"><?php echo prijsify($budget->saldo); ?></span>
                    <span class="clearfix target" data-id="<?php echo $budget->id; ?>" data-target="<?php echo $budget->target; ?>" >
                        <?php if($budget->target) echo prijsify($budget->target); ?>
                    </span>
                </li>
            <?php endforeach; ?>
            </ul>
        </div>
        <div class="span2">
            <div class="table thead">
                <span class="center" style="float:none;">Bedrag erbij</span>
            </div>
            <ul class="table boeking">
            <?php foreach($budgetten as $budget): /* @var $budget budget */ ?>
                <li id="boeking<?php echo $budget->id; ?>">
                    <span>€</span><input name="boeking[<?php echo $budget->id; ?>]" data-id="<?php echo $budget->id; ?>" step="any" type="number" min="0" value="0"/>
                </li>
            <?php endforeach; ?>
            </ul>
        </div>
        <div class="span2">
            <div class="table thead">
                <span class="center" style="float:none;">Nieuwe stand</span>
            </div>
            <ul class="table stand">
            <?php foreach($budgetten as $budget): /* @var $budget budget */ ?>
                <li id="stand<?php echo $budget->id; ?>">
                    <span>€</span><input type="number" value="<?php echo ($budget->saldo?number_format( $budget->saldo,2,'.',''):0); ?>"  data-id="<?php echo $budget->id; ?>" step="any"/>
                </li>
            <?php endforeach; ?>
            </ul>
        </div>
        <div class="span2" style="text-align:center;">
            <div class="table thead">
                <select id="creditgroep">
                    <?php foreach($creditgroups as $k => $creditgroup): /* @var $creditgroup creditgroep */ ?>
                    <option value="<?php echo $creditgroup->id ?>" <?php echo ($k==0?'selected':'');?>><?php 
                    if(round($creditgroup->saldo)==0 && $creditgroup->credit>0){
                        echo '&#10003;&nbsp;';
                    }
                    echo $creditgroup->naam ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="margin:30px auto 10px;background-image:url('/img/coinpile.png');height:224px;width:102px;">
                <div id="pilehider" class="s1"></div>
            </div>
            <p id="vrijsaldo" data-vrijsaldo=""></p>
            <button class="btn btn-primary" id="saveboekingen">Opslaan</button>
        </div>
    </div>
</div>
<?php $this->load->view('segments/footer'); ?>