<?php $this->load->view('segments/header'); ?>
<div class="container">
    <div class="row">
        <?php print_account_switch(); ?>
        <div class="span6">
            <h3>Budget balans</h3>
        </div>
        <div class="span6">
            <ul class="nav nav-pills pull-right" style="margin-top:15px;">
                <li class="active"><a href="/credit/bedragen">Bedragen</a></li>
                <li><a href="/credit/transacties">Transacties</a></li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="span5">
            <div class="table thead">
                <span style="width:248px">Naam</span>
                <span style="width:100px">Huidige stand</span>
            </div>
            <ul id="budgetten" class="table">
            <?php foreach($budgetten as $budget): /* @var $budget budget */ ?>
                <li id="saldo<?php echo $budget->id; ?>" data-saldo="<?php echo number_format( $budget->saldo,2,'.',''); ?>">
                    <span><?php echo $budget->naam; ?></span>
                    <span class="clearfix saldo"><?php echo prijsify($budget->saldo); ?></span>
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
        <div class="span3" style="text-align:center;">
            <div class="table thead">
                <span class="center" style="float:none;">Verdeelbaar</span>
            </div>
            <div style="margin:30px auto 10px;background-image:url('/img/coinpile.png');height:224px;width:102px;">
                <div id="pilehider" class="s1"></div>
            </div>
            <p id="vrijsaldo" data-vrijsaldo="<?php echo $vrijSaldo; ?>"><?php echo prijsify($vrijSaldo); ?></p>
            <button class="btn btn-primary" id="saveboekingen">Opslaan</button>
        </div>
    </div>
    <div class="row">
        <div class="span12">
            <hr>
            <button class="btn btn-form">Nieuw budget toevoegen</button>
            <form action="/credit/addBudget" id="addBudget" method="POST">
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
    </div>
</div>
<?php $this->load->view('segments/footer'); ?>