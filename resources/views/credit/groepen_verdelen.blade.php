<x-layout>
    <x-slot:title>
        Groepen Verdelen
    </x-slot:title>

    <div class="container">
        <div class="row">
            <div class="span6">
                <h3>Budget balans</h3>
            </div>
            <div class="span6">
                <ul class="nav nav-pills pull-right" style="margin-top:15px;">
                    <li><a href="/credit/groepen">Groeperen</a></li>
                    <li class="active"><a href="/credit/groepen_verdelen">Groepen verdelen</a></li>
                    <li class="disabled"><a href="#">of</a></li>
                    <li><a href="/credit/transacties">Transacties</a></li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div id="overlay"></div>
            <div class="span6">
                <div class="table thead">
                    <span style="width:211px">Naam</span>
                    <span style="width:100px">Stand v. toen</span>
                    <span style="width:100px">Increment</span>
                </div>
                <ul id="budgetten" class="table">
                @foreach($budgetten as $budget)
                    <li id="saldo{{ $budget->id }}" data-saldo="{{ number_format( $budget->saldo,2,'.','') }}">
                        <span>{{ $budget->naam }}</span>
                        <span class="saldo">{{ prijsify($budget->saldo) }}</span>
                        <span class="clearfix target" data-id="{{ $budget->id }}" data-target="{{ $budget->target }}" >
                            @if($budget->target) {{ prijsify($budget->target) }} @endif
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
                @foreach($budgetten as $budget)
                    <li id="boeking{{ $budget->id }}">
                        <span>€</span><input name="boeking[{{ $budget->id }}]" data-id="{{ $budget->id }}" step="any" type="number" min="0" value="0"/>
                    </li>
                @endforeach
                </ul>
            </div>
            <div class="span2">
                <div class="table thead">
                    <span class="center" style="float:none;">Nieuwe stand</span>
                </div>
                <ul class="table stand">
                @foreach($budgetten as $budget)
                    <li id="stand{{ $budget->id }}">
                        <span>&euro;</span>
                        <input type="number" value="{{ ($budget->saldo?number_format( $budget->saldo,2,'.',''):0) }}" data-id="{{ $budget->id }}" step="any"/>
                    </li>
                @endforeach
                </ul>
            </div>
            <div class="span2" style="text-align:center;">
                <div class="table thead">
                    <select id="creditgroep">
                        @foreach($creditgroups as $k => $creditgroup)
                        <option value="{{ $creditgroup->id }}" {{ ($k==0?'selected':'') }}><?php
                            @if(round($creditgroup->saldo)==0 && $creditgroup->credit>0)
                                &#10003;&nbsp;
                            @endif
                            {{ $creditgroup->naam }}
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
