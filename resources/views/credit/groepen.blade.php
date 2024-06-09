<x-layout>
    <x-slot:title>
        Groepen
    </x-slot:title>

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
                <h3>{{ $groep ? $groep->naam : 'Groepen' }}</h3>
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
                @foreach($creditgroups as $creditgroup)
                    <li class="dropable {{ ($creditgroup->id==$active_groep?'current':'')}}"
                        data-saldo="{{ round ($creditgroup->saldo) }}" data-id="{{ $creditgroup->id }}">
                        <span><a href="/credit/groep_detail/{{ $creditgroup->id }}">{{ $creditgroup->naam }}</a></span>
                        <span>{{ prijsify($creditgroup->credit) }}</span>
                        <span class="clearfix saldo">
                        @if(round($creditgroup->saldo)==0 && $creditgroup->credit>0)
                            &#10003;&nbsp;
                        @else
                            {{ prijsify($creditgroup->saldo) }}
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
                @foreach($transacties as $transactie)
                    <li data-id="{{ $transactie->id }}" data-bedrag="{{ round($transactie->bedrag) }}">
                        <span>{{ $transactie->datum_nl }}</span>
                        <span title="{{ $transactie->description }}">{{ $transactie->description }}</span>
                        <span title="{{ $transactie->van_naam }}">{{ $transactie->van_naam }}</span>
                        <span>&euro; {{ round($transactie->bedrag) }}</span>
                    </li>
                @endforeach
                </ul>
                <div class="page_navigation pagination"></div>
            </div>
        </div>
    </div>
</x-layout>>
