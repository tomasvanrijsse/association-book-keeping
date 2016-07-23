<?php $this->load->view('segments/header'); ?>
<div class="container">
    <div class="row">
        <div class="span6">
            <h3>Leden</h3>
        </div>
    </div>
    <div class="row">
        <div class="span6">
            <table class="table table-striped table-hover">
                <tbody>
                    <?php 
                    foreach($leden as $lid): /* @var $lid lid */ ?>
                        <tr onclick="document.location='/leden/details/<?php echo $lid->id ?>'">
                            <td><?php echo $lid->naam; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <thead>
                    <tr>
                        <th>Naam</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="span6">
            <div class="control-group">
                <div class="controls">
                    Voeg een lid toe
                </div>
            </div>
            <form action="/leden/add" method="POST">
                <div class="control-group">
                    <label class="control-label" for="type">Naam</label>
                    <div class="controls">
                        <input type="text" value="" id="inputNaam" name="naam">
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