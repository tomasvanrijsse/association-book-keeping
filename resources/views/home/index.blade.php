@extends('layouts.default')

@section('content')
    <div class="container">
    <div class="row">
        <div class="span6">
            <h3>Import</h3>
            <div class="well">
                <form action="/import/upload" method="POST" enctype="multipart/form-data" >
                    <div class="control-group">
                        <label class="control-label" for="type">CSV bestand</label>
                        <div class="controls">
                            <input type="file" value="" id="inputFile" name="csvfile">
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="controls">
                            <button type="submit" class="btn btn-primary">Importeren</button>
                        </div>
                    </div>
                </form>
                De laatste import was op: <?php echo $last_import; ?><br/>
                De laatste transactie is van : <?php echo $last_transaction; ?>
                <?php /*if($this->session->flashdata('home_import_error')){
                   echo '<div class="alert alert-error">'.$this->session->flashdata('home_import_error').'</div>';
                }?>
                <?php if($this->session->flashdata('home_import_success')){
                   echo '<div class="alert alert-success">'.$this->session->flashdata('home_import_success').'</div>';
                }?>
                <?php if($this->session->flashdata('home_import_notice')){
                   echo '<div class="alert">'.$this->session->flashdata('home_import_notice').'</div>';
                }*/ ?>
            </div>
        </div>
        <div class="span6">
            <h3>Tegengstelde transacties</h3>
            <div class="well">
                Er zijn <?php echo ($aantalCounter?$aantalCounter: 'geen');?> suggesties.<br/>
                Bekijk de <a href="/transacties/counter">suggesties</a> voor tegengestelde transacties<br/>
                <br/>
                <a href="/home/rescanCounter">Zoek opnieuw naar suggesties</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="span6">
            <h3>Saldo per budget</h3>
            <div class="well">
                <a href="/budget-targets" class="btn btn-default">Naar overzicht</a>
            </div>
        </div>
        <div class="span6">
            
        </div>
    </div>
</div>
@endsection

@section('title')
Home
@endsection