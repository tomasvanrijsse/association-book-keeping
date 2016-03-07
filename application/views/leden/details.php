<?php $this->load->view('segments/header'); ?>
<div class="container">
    <div class="row">
        <?php print_account_switch(); ?>
        <div class="span6">
            <h3><?php echo $lid->naam; ?></h3>
        </div>
    </div>
    <div class="row">
        <div class="span6">
            <p class="lead">Betalingen</p>
            <?php echo $betalingen; ?>
        </div>
        <div class="span6">
            <p class="lead">Woonruimte</p>
            <table class="table table-striped">
                <tbody>
                    <?php 
                    foreach($lidruimtes as $lidruimte): /* @var $inkomen inkomen */ ?>
                        <tr <?php echo ($lidruimte->einddatum?'class="past"':''); ?>>
                            <td><?php echo $lidruimte->ruimte->name(); ?></td>
                            <td>
                                <?php echo $lidruimte->startdatum; 
                                if($lidruimte->einddatum){
                                    echo ' - '.$lidruimte->einddatum;
                                }
                                ?>
                            </td>
                            <td>
                                <i data-url="/leden/deleteRuimte/<?php echo $lidruimte->id;?>" onclick="_remove(this);" class="icon-remove"></i>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <thead>
                    <tr>
                        <th>Ruimte</th>
                        <th>Sinds</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
            <hr>
            <button class="btn btn-form">Verhuizen</button> 
            <form action="/leden/addRuimte" method="POST">
                <input type="hidden" name="lid_id" value="<?php echo $lid->id; ?>"/>
                <div class="control-group">
                    <label class="control-label" for="type">Ruimte</label>
                    <div class="controls">
                        <select name="ruimte_id">
                            <?php foreach($ruimtes as $ruimte): ?>
                            <option value="<?php echo $ruimte->id; ?>"><?php echo $ruimte->name(); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <button type="submit" class="btn">Opslaan</button>
                    </div>
                </div>
            </form>
            <p class="lead">Inkomen</p>
            <table class="table table-striped">
                <tbody>
                    <?php 
                    foreach($inkomens as $inkomen): /* @var $inkomen inkomen */ ?>
                        <tr <?php echo ($inkomen->einddatum?'class="past"':''); ?>>
                            <td><?php echo $inkomen->naam; ?></td>
                            <td>
                                <?php echo $inkomen->startdatum; 
                                if($inkomen->einddatum){
                                    echo ' - '.$inkomen->einddatum;
                                }
                                ?>
                            </td>
                            <td><?php echo prijsify($inkomen->bedrag); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <thead>
                    <tr>
                        <th>Naam</th>
                        <th>Sinds</th>
                        <th>Bedrag</th>
                    </tr>
                </thead>
            </table>
            <button class="btn btn-form">Nieuw inkomen toevoegen</button>
            <form action="/leden/addInkomen" method="POST">
                <input type="hidden" name="lid_id" value="<?php echo $lid->id; ?>"/>
                <div class="control-group">
                    <label class="control-label" for="inputNaam">Naam</label>
                    <div class="controls">
                        <input type="text" name="naam" id="inputNaam"/>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="inputBedrag">Bedrag</label>
                    <div class="controls">
                        <input type="text" name="bedrag" id="inputBedrag"/>
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