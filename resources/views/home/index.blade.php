<x-layout>
    <x-slot:title>
        Home
    </x-slot:title>

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
                    De laatste import was op: {{ $last_import }}<br/>
                    De laatste transactie is van : {{ $last_transaction }}
                    @if(session('home_import_error'))
                       <div class="alert alert-error"> {{ session('home_import_error') }}</div>
                    @endif
                    @if(session('home_import_success'))
                       <div class="alert alert-success"> {{ session('home_import_success') }}</div>
                    @endif
                    @if(session('home_import_notice'))
                       <div class="alert"> {{ session('home_import_notice') }}</div>
                    @endif
                </div>
            </div>
            <div class="span6">
                <h3>Tegengestelde transacties</h3>
                <div class="well">
                    Er zijn {{ ($aantalCounter ?: 'geen') }} suggesties.<br/>
                    Bekijk de <a href="/transacties/counter">suggesties</a> voor tegengestelde transacties<br/>
                    <br/>
                    <a href="/home/rescanCounter">Zoek opnieuw naar suggesties</a>
                </div>
            </div>
        </div>
    </div>
</x-layout>
