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
                <h3>{{ $activePeriod ? $activePeriod->title : 'Groepen' }}</h3>
            </div>
            <div class="span6 clearfix">
                <ul class="nav nav-pills pull-right" style="margin-top:15px;">
                    <li class="active"><a href="/credit">Groeperen</a></li>
                    <li><a href="/contribution-periods/allocate">Groepen verdelen</a></li>
                    <li class="disabled"><a href="#">of</a></li>
                    <li><a href="/credit/transactions">Transacties</a></li>
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
                @foreach($contributionPeriods as $contributionPeriod)
                    <li class="dropable {{ ($contributionPeriod->id==$activePeriod?->id?'current':'')}}"
                        data-saldo="{{ round ($contributionPeriod->balance ?? 0) }}" data-id="{{ $contributionPeriod->id }}">
                        <span><a href="/credit/{{ $contributionPeriod->id }}">{{ $contributionPeriod->title }}</a></span>
                        <span>{{ Number::currency($contributionPeriod->credit, 'EUR') }}</span>
                        <span class="clearfix saldo">
                        @if(round($contributionPeriod->balance)==0 && $contributionPeriod->credit>0)
                            &#10003;&nbsp;
                        @else
                            {{ Number::currency($contributionPeriod->balance, 'EUR') }}
                        @endif
                        </span>
                    </li>
                @endforeach
                </ul>
                <div class="page_navigation pagination"></div>
                <hr>
                <button class="btn btn-form">Nieuwe groep toevoegen</button>
                <form action="/credit" id="addGroep" method="POST">
                    @csrf
                    <div class="control-group">
                        <label class="control-label" for="inputNaam">Naam</label>
                        <div class="controls">
                            <input type="text" name="title" id="inputNaam" value="{{ $newContributionPeriodDate->translatedFormat('F Y') }}"/>
                            <select name="month">
                                @for($i=1; $i<=12;$i++)
                                    <option @selected($i==$newContributionPeriodDate->month) value="{{ $i }}">
                                        {{ \Carbon\Carbon::create(month:$i)->translatedFormat('F') }}
                                    </option>
                                @endfor
                            </select>
                            <select name="year">
                                @for($i=2020; $i<=\Carbon\Carbon::now()->year; $i++)
                                    <option @selected($i == $newContributionPeriodDate->year) value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
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
                    <li data-id="{{ $transaction->id }}" data-bedrag="{{ round($transaction->amount) }}">
                        <span>{{ $transaction->date->format('d-m-Y') }}</span>
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
</x-layout>>
