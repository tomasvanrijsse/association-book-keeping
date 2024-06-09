<x-layout>
    <x-slot:title>
        Credit | Transacties groeperen
    </x-slot:title>
    <x-slot:styleSheets>
        <link rel="stylesheet" href="/css/credit_groepen.css"/>
        <link rel="stylesheet" href="/css/smoothness/jquery-ui-1.10.1.custom.min.css"/>
    </x-slot:styleSheets>
    <x-slot:scripts>
        <script src="/js/libs/jquery-ui-1.10.1.min.js"></script>
        <script src="/js/libs/jquery.pajinate.js"></script>
        <script src="/js/credit_groepen.js"></script>
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
        <div class="row">
            <div class="span6">
                <h3>{{ $activeGroup ? $activeGroup->naam : 'Groepen' }}</h3>
            </div>
            <div class="span6 clearfix">
                <ul class="nav nav-pills pull-right" style="margin-top:15px;">
                    <li class="active"><a href="/credit/groepen">Groeperen</a></li>
                    <li><a href="/credit/groepen_verdelen">Groepen verdelen</a></li>
                    <li class="disabled"><a href="#">of</a></li>
                    <li><a href="/credit/transacties">Transacties</a></li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="span5">
                <div class="table thead">
                    <span style="width:170px">Naam</span>
                    <span style="width:80px">Saldo</span>
                    <span style="width:80px">Rest</span>
                </div>
                <ul id="budgetten" class="table">
                @foreach($creditGroups as $creditGroup)
                    <li class="dropable {{ ($creditGroup->id==$activeGroup?->id?'current':'')}}"
                        data-saldo="{{ round ($creditGroup->saldo) }}" data-id="{{ $creditGroup->id }}">
                        <span><a href="/credit/{{ $creditGroup->id }}">{{ $creditGroup->naam }}</a></span>
                        <span>{!! prijsify($creditGroup->credit) !!}</span>
                        <span class="clearfix saldo">
                        @if(round($creditGroup->saldo)==0 && $creditGroup->credit>0)
                            &#10003;&nbsp;
                        @else
                            {!! prijsify($creditGroup->saldo) !!}
                        @endif
                        </span>
                    </li>
                @endforeach
                </ul>
                <div class="page_navigation pagination"></div>
                <hr>
                <button class="btn btn-form">Nieuwe groep toevoegen</button>
                <form action="/credit/addGroep" id="addGroep" method="POST">
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
                <div class="table thead">
                    <span style="width:76px">Datum</span>
                    <span style="width:206px">Beschrijving</span>
                    <span style="width:140px">Van</span>
                    <span style="width:50px">Bedrag</span>
                </div>
                <ul id="transactions" class="dropable table">
                @foreach($transactions as $transaction)
                    <li data-id="{{ $transaction->id }}" data-bedrag="{{ round($transaction->bedrag) }}">
                        <span>{{ $transaction->datum }}</span>
                        <span title="{{ $transaction->description }}">{{ $transaction->description }}</span>
                        <span title="{{ $transaction->van_naam }}">{{ $transaction->van_naam }}</span>
                        <span>&euro; {{ round($transaction->bedrag) }}</span>
                    </li>
                @endforeach
                </ul>
                <div class="page_navigation pagination"></div>
            </div>
        </div>
    </div>
</x-layout>>
