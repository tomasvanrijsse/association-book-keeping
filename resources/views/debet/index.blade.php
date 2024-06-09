<x-layout>
    <x-slot:title>
        Debet transacties
    </x-slot:title>
    <x-slot:styleSheets>
        <link rel="stylesheet" href="/css/debet.css"/>
        <link rel="stylesheet" href="/css/smoothness/jquery-ui-1.10.1.custom.min.css"/>
    </x-slot:styleSheets>
    <x-slot:scripts>
        <script src="/js/libs/jquery-ui-1.10.1.min.js"></script>
        <script src="/js/libs/jquery.pajinate.js"></script>
        <script src="/js/debet.js"></script>
    </x-slot:scripts>


    <div class="container">
        @if(session('error')!='')
        <div class="row">
            <div class="span12">
                <div class="alert alert-error" style="margin: 10px 0;">
                {{ session('error') }}
                </div>
            </div>
        </div>
        @endif
        <div class="row" id="dragcontainment">
            <div class="span5">
                <h3>Budget balans</h3>
                <div class="table thead">
                    <span style="width:248px">Naam</span>
                    <span style="width:100px">Bedrag</span>
                </div>
                <ul id="budgetten" class="table">
                @foreach($budgets as $budget)
                    <li class="dropable {{ ($budget->id==$active_budget?'current':'') }}" data-id="{{ $budget->id }}" data-saldo="{{ round($budget->saldo) }}">
                        <span><a href="/debet/{{ ($budget->id) }}">{{ $budget->naam }}</a></span>
                        <span class="clearfix saldo">{!! prijsify($budget->saldo) !!}</span>
                    </li>
                @endforeach
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
                <h3>{{ $transacties_title }}</h3>
                <div class="table thead">
                    <span style="width:76px">Datum</span>
                    <span style="width:206px">Beschrijving</span>
                    <span style="width:140px">Naar</span>
                    <span style="width:50px">Bedrag</span>
                </div>
                <ul id="transactions" class="dropable table">
                @foreach($transacties as $transactie)
                    <li data-id="{{ $transactie->id }}" data-bedrag="{{ round($transactie->bedrag) }}">
                        <span>{{ $transactie->datum }}</span>
                        <span title="{{ $transactie->description }}">{{ $transactie->description }}</span>
                        <span title="{{ $transactie->van_naam }}">{{ $transactie->van_naam }}</span>
                        <span title="{{ prijsify($transactie->bedrag) }}">&euro; {{ round($transactie->bedrag) }}</span>
                    </li>
                @endforeach
                </ul>
                <div class="page_navigation pagination"></div>
            </div>
        </div>
    </div>
</x-layout>
