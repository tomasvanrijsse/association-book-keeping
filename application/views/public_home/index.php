<?php $this->load->view('segments/publicheader'); ?>
<div class="container">
    <div class="row">
        <div class="span7">
            <h3>Status quo</h3>
            <div class="well">
                <ul class="budgettable table">
                    <li>
                        <span><strong>Budget</strong></span>
                        <span class="saldo"><strong>Saldo</strong></span>
                        <span class="target"><strong>Increment</strong></span>
                    </li>
                <?php foreach($budgetten as $budget): if($budget->account_id==2) continue; /* @var $budget budget */ ?>
                    <li>
                        <span><?php echo $budget->naam; ?></span>
                        <span class="saldo"><?php echo prijsify($budget->saldo); ?></span>
                        <span class="target">
                            <?php if($budget->target):
                            echo '&euro;'.$budget->target;
                            endif; ?>
                        </span>
                    </li>
                <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="span5">
            <h3 style="color:white;">.</h3>
            <div class="well">
                <table>
                    <tr>
                        <td>De laatste import was op: </td>
                        <td><?php echo $last_import; ?></td>
                    </tr>
                    <tr>
                        <td>De laatste transactie is van: </td>
                        <td><?php echo $last_transaction; ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('segments/footer');
