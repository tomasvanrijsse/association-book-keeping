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
    <div class="row" id="dragcontainment">
        <?php print_account_switch('/debet'); ?>
        <div class="span5">
            <h3>Budget balans</h3>
            <div class="table thead">
                <span style="width:248px">Naam</span>
                <span style="width:100px">Bedrag</span>
            </div>
            <ul id="budgetten" class="table">
            <?php foreach($budgetten as $budget): /* @var $budget budget */ ?>
                <li class="dropable <?php echo ($budget->id==$active_budget?'current':''); ?>" data-id="<?php echo $budget->id; ?>" data-saldo="<?php echo round($budget->saldo); ?>">
                    <span><a href="/debet/detail/<?php echo rawurlencode($budget->naam); ?>"><?php echo $budget->naam; ?></a></span>
                    <span class="clearfix saldo"><?php echo prijsify($budget->saldo); ?></span>
                </li>
            <?php endforeach; ?>
            </ul>
            <hr>
            <button class="btn btn-form">Nieuw budget toevoegen</button>
            <form action="/debet/addBudget" id="addBudget" method="POST">
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
            <h3><?php echo $transacties_title; ?></h3>
            <div class="table thead">
                <span style="width:76px">Datum</span>
                <span style="width:206px">Beschrijving</span>
                <span style="width:140px">Naar</span>
                <span style="width:50px">Bedrag</span>
            </div>
            <ul id="transactions" class="dropable table">
            <?php foreach($transacties as $k => $transactie): /* @var $transactie transactie */ ?>
                <li data-id="<?php echo $transactie->id; ?>" data-bedrag="<?php echo round($transactie->bedrag); ?>">
                    <span><?php echo $transactie->datum_nl; ?></span>
                    <span title="<?php echo $transactie->description; ?>"><?php echo $transactie->description; ?></span>
                    <span title="<?php echo $transactie->van_naam; ?>"><?php echo $transactie->van_naam; ?></span>
                    <span title="<?php echo prijsify($transactie->bedrag); ?>">&euro; <?php echo round($transactie->bedrag); ?></span>
                </li>
            <?php endforeach; ?>
            </ul>
            <div class="page_navigation pagination"></div>
        </div>
    </div>
</div>
<?php $this->load->view('segments/footer'); ?>