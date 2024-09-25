<x-layout>
    <x-slot:title>
        Credit | Transacties verdelen
    </x-slot:title>
    <x-slot:styleSheets>
        <link rel="stylesheet" href="/css/credit_transacties.css"/>
        <link rel="stylesheet" href="/css/smoothness/jquery-ui-1.10.1.custom.min.css"/>
    </x-slot:styleSheets>
    <x-slot:scripts>
        <script src="/js/libs/jquery-ui-1.10.1.min.js"></script>
        <script src="/js/libs/jquery.pajinate.js"></script>
        <script src="/js/credit_transacties.js"></script>
    </x-slot:scripts>

    <div class="container">
        <div class="row">
            <div class="span6">
                <h3>Budget balans</h3>
            </div>
            <div class="span6 clearfix">
                <ul class="nav nav-pills pull-right" style="margin-top:15px;">
                    <li><a href="/credit">Groeperen</a></li>
                    <li><a href="/contribution-periods/allocate">Groepen verdelen</a></li>
                    <li class="disabled"><a href="#">of</a></li>
                    <li class="active"><a href="/credit/transactions">Transacties</a></li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="span5">
                <div class="table thead">
                    <span style="width:248px">Naam</span>
                    <span style="width:100px">Huidige stand</span>
                </div>
                <ul id="budgetten" class="table">
                @foreach($budgets as $budget)
                    <li class="dropable" data-id="{{ $budget->id }}">
                        <span>{{ $budget->title }}</span>
                        <span class="clearfix saldo">{{ Number::currency($budget->balance, 'EUR') }}</span>
                    </li>
                @endforeach
                </ul>
            </div>
            <div class="span7">
                <div class="table thead">
                    <span style="width:76px">Datum</span>
                    <span style="width:206px">Beschrijving</span>
                    <span style="width:140px">Naar</span>
                    <span style="width:50px">Bedrag</span>
                </div>
                <ul id="transactions" class="dropable table">
                    @foreach($transactions as $transaction)
                        <li data-id="{{ $transaction->id }}" data-bedrag="{{ round($transaction->amount) }}">
                            <span>{{ $transaction->date }}</span>
                            <span title="{{ $transaction->description }}">{{ $transaction->description }}</span>
                            <span title="{{ $transaction->related_party_name }}">{{ $transaction->related_party_name }}</span>
                            <span>&euro; {{ round($transaction->amount) }}</span>
                        </li>
                    @endforeach
                </ul>
                <div class="page_navigation pagination"></div>
            </div>
        </div>
    </div>
</x-layout>
