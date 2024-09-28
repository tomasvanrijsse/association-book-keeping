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
                    De laatste import was op: {{ $lastImport->format('d-m-Y') }}<br/>
                    De laatste transactie is van : {{ $lastTransaction->format('d-m-Y') }}
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
                <h3>Automatische incasso's</h3>
                <div class="well">
                    Er zijn {{ $mandatesWithoutBudget }} automatische incasso's die gekoppeld kunnen worden aan een budget.
                    <a href="/mandates">Automatische incasso's</a>
                </div>
            </div>

            <div class="span6">
                <h3>Export</h3>
                <div class="well">
                    <form action="/export" method="get">
                        @csrf
                        <select name="year">
                            @for($i=2020; $i<=\Carbon\Carbon::now()->year; $i++)
                                <option @selected($i == \Carbon\Carbon::now()->year - 1) value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                        <div class="control-group">
                            <div class="controls">
                                <button type="submit" class="btn btn-primary">Download</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>
