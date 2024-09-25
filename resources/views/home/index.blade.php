<x-layout>
    <x-slot:title>
        Home
    </x-slot:title>

        <div class="container">
        <div class="row">
            <div class="span6">
                <h3>Import</h3>
                <div class="well">
                    <form action="/import" method="POST" enctype="multipart/form-data" >
                        @csrf
                        <div class="control-group">
                            <label class="control-label" for="type">CAMT053 XML bestand</label>
                            <div class="controls">
                                <input type="file" value="" id="inputFile" name="bankExport" accept=".xml">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="controls">
                                <button type="submit" class="btn btn-primary">Importeren</button>
                            </div>
                        </div>
                    </form>
                    De laatste import was op: {{ $lastImport }}<br/>
                    De laatste transactie is van : {{ $lastTransaction }}
                    @if ($errors->any())
                        <div class="alert alert-error">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                    @if(session('home_import_success'))
                       <div class="alert alert-success"> {{ session('home_import_success') }}</div>
                    @endif
                    @if(session('home_import_notice'))
                       <div class="alert"> {{ session('home_import_notice') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layout>
