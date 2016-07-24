@extends('layouts.default')

@section('content')
<div class="container">
    <div class="row">
        <div class="span6">
            <h3>Prive potjes</h3>
            <div class="well">
                <ul class="budgettable table">
                <?php foreach($budgetten as $budget): if($budget->account_id==1) continue;/* @var $budget budget */ ?>
                    <li>
                        <span>{{ $budget->naam }}</span>
                        <span class="saldo"><?php echo $budget->present()->saldo; ?></span>
                        <?php if($budget->budgetTarget):?>
                        <span class="target">
                            <span class="icon icon-asterisk"></span>
                        </span>
                        <div class="target">
                            <?php $target = $budget->budgetTarget;
                            /* @var $target budgettarget */
                            switch($target->type):
                                case 'vast':
                                    echo 'Saldo moet elke maand staan op &euro;'.$target->bedrag;
                                    break;
                                case 'increment':
                                    echo 'Saldo moet elke maand verhogen met &euro;'.$target->bedrag;
                                    break;
                                case 'target':
                                    echo 'Saldo moet op '.$target->datum.' staan op &euro;'.$target->bedrag;
                                    break;
                            endswitch; ?>
                        </div>
                        <?php endif ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            </div>
        </div>
        <div class="span6">
            <h3>Zakelijke potjes</h3>
            <div class="well">
                <ul class="budgettable table">
                <?php foreach($budgetten as $budget): if($budget->account_id==2) continue; /* @var $budget budget */ ?>
                    <li>
                        <span><?php echo $budget->naam; ?></span>
                        <span class="saldo"><?php echo $budget->present()->saldo; ?></span>
                        <?php if($budget->budgetTarget):?>
                        <span class="target">
                            <span class="icon icon-asterisk"></span>
                        </span>
                        <div class="target">
                            <?php $target = $budget->budgetTarget;
                            /* @var $target budgettarget */
                            switch($target->type):
                                case 'vast':
                                    echo 'Saldo moet elke maand staan op &euro;'.$target->bedrag;
                                    break;
                                case 'increment':
                                    echo 'Saldo moet elke maand verhogen met &euro;'.$target->bedrag;
                                    break;
                                case 'target':
                                    echo 'Saldo moet op '.$target->datum.' staan op &euro;'.$target->bedrag;
                                    break;
                            endswitch; ?>
                        </div>
                        <?php endif ?>
                    </li>
                <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="span6">
            <div class="well">
                <table>
                    <tr>
                        <td>De laatste import was op: </td>
                        <td><?php echo $last_import; ?></td>
                    </tr>
                    <tr>
                        <td>De laatste transactie is van :</td>
                        <td><?php echo $last_transaction; ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="span6">
            
        </div>
    </div>
</div>

@endsection

@push('stylesheets')
<link href="/css/public_home.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="/js/public_home.js"></script>
@endpush