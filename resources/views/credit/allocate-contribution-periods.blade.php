<x-layout>
    <x-slot:title>
        Credit | Groepen verdelen
    </x-slot:title>

    <x-slot:styleSheets>
        <link rel="stylesheet" href="/css/credit_bedragen.css"/>
    </x-slot:styleSheets>
    <x-slot:scripts>
        <script src="/js/credit_groepen_verdelen.js"></script>
    </x-slot:scripts>

    <div class="container">
        <div class="row">
            <div class="span6">
                <h3>Budget balans</h3>
            </div>
            <div class="span6">
                <ul class="nav nav-pills pull-right" style="margin-top:15px;">
                    <li><a href="/credit">Groeperen</a></li>
                    <li class="active"><a href="/contribution-periods/allocate">Groepen verdelen</a></li>
                    <li class="disabled"><a href="#">of</a></li>
                    <li><a href="/credit/transactions">Transacties</a></li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div id="overlay"></div>
            <div class="span6">
                <div class="table thead">
                    <span style="width:280px">Naam</span>
                    <span style="width:100px">Increment</span>
                </div>
                <ul id="budgetten" class="table">
                @foreach($budgets as $budget)
                    <li id="saldo{{ $budget->id }}" data-saldo="{{ number_format( $budget->balance,2,'.','') }}">
                        <span>{{ $budget->title }}</span>
                        <span class="clearfix target" data-id="{{ $budget->id }}" data-target="{{ $budget->target }}" >
                            @if($budget->target) {{ Number::currency($budget->target, 'EUR') }} @endif
                        </span>
                    </li>
                @endforeach
                </ul>
            </div>
            <div class="span2">
                <div class="table thead">
                    <span class="center" style="float:none;">Bedrag erbij</span>
                </div>
                <ul class="table boeking">
                @foreach($budgets as $budget)
                    <li id="boeking{{ $budget->id }}">
                        <span>€</span><input name="boeking[{{ $budget->id }}]" data-id="{{ $budget->id }}" step="any" type="number" value="0"/>
                    </li>
                @endforeach
                </ul>
            </div>
            <div class="span2">
                <div class="table thead">
                    <span class="center" style="float:none;">Nieuwe stand</span>
                </div>
                <ul class="table stand">
                @foreach($budgets as $budget)
                    <li id="stand{{ $budget->id }}">
                        <span>&euro;</span>
                        <input type="number" value="{{ ($budget->balance?number_format( $budget->balance,2,'.',''):0) }}" data-id="{{ $budget->id }}" step="any"/>
                    </li>
                @endforeach
                </ul>
            </div>
            <div class="span2" style="text-align:center;">
                <div class="table thead">
                    <select id="creditgroep">
                        @foreach($contributionPeriods as $k => $contributionPeriod)
                        <option value="{{ $contributionPeriod->id }}" {{ ($k==0?'selected':'') }}>
                            @if(round($contributionPeriod->balance)==0 && $contributionPeriod->credit>0)
                                &#10003;&nbsp;
                            @endif
                            {{ $contributionPeriod->title }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div style="margin:30px auto 10px;background-image:url('/img/coinpile.png');height:224px;width:102px;">
                    <div id="pilehider" class="s1"></div>
                </div>
                <p id="vrijsaldo" data-vrijsaldo=""></p>
                <button class="btn btn-primary" id="saveboekingen">Opslaan</button>
            </div>
        </div>
    </div>
</x-layout>
