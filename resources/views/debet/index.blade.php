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
                    <li class="dropable {{ ($budget->id==$activeBudget?->id?'current':'') }}" data-id="{{ $budget->id }}" data-saldo="{{ round($budget->saldo) }}">
                        <span><a href="/debit/{{ ($budget->id) }}">{{ $budget->naam }}</a></span>
                        <span class="clearfix saldo">{!! prijsify($budget->saldo) !!}</span>
                    </li>
                @endforeach
                </ul>
                <hr>
                <button class="btn btn-form">Nieuw budget toevoegen</button>
                <form action="/budgets" id="addBudget" method="POST">
                    @csrf
                    <input type="hidden" name="redirectUrl" value="/debit">
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
                <h3>@if($activeBudget)
                        {{ $activeBudget->naam }} transacties
                        <button id="budgetDelete" data-budget_id="{{$activeBudget->id}}">
                            <i class="icon-trash"></i>
                        </button>
                    @else
                        Ongecategoriseerde transacties
                    @endif
                </h3>
                <div class="table thead">
                    <span style="width:76px">Datum</span>
                    <span style="width:206px">Beschrijving</span>
                    <span style="width:140px">Naar</span>
                    <span style="width:50px">Bedrag</span>
                </div>
                <ul id="transactions" class="dropable table">
                @foreach($transactions as $transaction)
                    <li data-id="{{ $transaction->id }}" data-bedrag="{{ round($transaction->bedrag) }}">
                        <span>{{ $transaction->datum }}</span>
                        <span title="{{ $transaction->description }}">{{ $transaction->description }}</span>
                        <span title="{{ $transaction->van_naam }}">{{ $transaction->van_naam }}</span>
                        <span title="{{ prijsify($transaction->bedrag) }}">&euro; {{ round($transaction->bedrag) }}</span>
                    </li>
                @endforeach
                </ul>
                <div class="page_navigation pagination"></div>
            </div>
        </div>
    </div>
</x-layout>
