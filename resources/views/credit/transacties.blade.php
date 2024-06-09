<x-layout>
    <x-slot:title>
        Transacties verdelen
    </x-slot:title>

    <div class="container">
        <div class="row">
            <div class="span6">
                <h3>Budget balans</h3>
            </div>
            <div class="span6 clearfix">
                <ul class="nav nav-pills pull-right" style="margin-top:15px;">
                    @if(getSetting(SETTING_USECREDITGROUPS))
                    <li><a href="/credit/groepen">Groeperen</a></li>
                    <li><a href="/credit/groepen_verdelen">Groepen verdelen</a></li>
                    <li class="disabled"><a href="#">of</a></li>
                    @else
                    <li><a href="/credit/bedragen">Bedragen</a></li>
                    @endif
                    <li class="active"><a href="/credit/transacties">Transacties</a></li>
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
                @foreach($budgetten as $budget)
                    <li class="dropable" data-id="{{ $budget->id }}">
                        <span>{{ $budget->naam }}</span>
                        <span class="clearfix saldo">{{ prijsify($budget->saldo) }}</span>
                    </li>
                @endforeach
                </ul>
                <hr>
                <button class="btn btn-form">Nieuw budget toevoegen</button>
                <form action="/credit/addBudget" id="addBudget" method="POST">
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
                    <span style="width:140px">Naar</span>
                    <span style="width:50px">Bedrag</span>
                </div>
                <ul id="transactions" class="dropable table">
                @foreach($transacties as $k => $transactie)
                    <li data-id="{{$transactie->id }}">
                        <span>{{$transactie->datum_nl }}</span>
                        <span title="{{$transactie->description }}">{{$transactie->description }}</span>
                        <span title="{{$transactie->van_naam }}">{{$transactie->van_naam }}</span>
                        <span>&euro; {{round($transactie->bedrag) }}</span>
                    </li>
                @endforeach
                </ul>
                <div class="page_navigation pagination"></div>
            </div>
        </div>
    </div>
</x-layout>
