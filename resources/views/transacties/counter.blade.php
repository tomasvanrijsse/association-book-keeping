<x-layout>
    <x-slot:title>
        Tegengestelde transacties
    </x-slot:title>

    <div class="container">
        <div class="row">
            <div class="span6">
                <h3>Tegengestelde transacties</h3>
            </div>
            <div class="span6">
                <ul class="nav nav-pills pull-right" style="margin-top:15px;">
                    <li {{ ($status==1?'class="active"':'') }} data-status="1" ><a href="/transacties/counter/open">Nieuw</a></li>
                    <li {{ ($status==2?'class="active"':'') }} data-status="2"><a href="/transacties/counter/verwerkt">Verwerkt</a></li>
                    <li {{ ($status==0?'class="active"':'') }} data-status="0"><a href="/transacties/counter/verborgen">Verborgen</a></li>
                </ul>
            </div>
        </div>
        @foreach($transactieSets as $set)
        <div class="row suggestion" data-id="{{ $set->id }}">
            <div class="span8">
                <h4>{{ $set->account->naam }} transacties</h4>
                <p>overeenkomsten gevonden op {{ $set->gewicht_naam }}</p>
            </div>
            <div class="span4 hidesuggestion">
                @if($status!=0)
                    <a href="#" class="hidelink">verberg suggestie</a>
                @endif
            </div>
            <div class="span6">
                <div class="well">
                    <strong>Credit (bij)</strong>
                    <ul class="table credit">
                    @foreach($set->transacties as $transactie)
                        <?php
                            if($transactie->type!='credit') continue;
                            if($transactie->sug_status==1){
                                $class = 'btn-inverse';
                                $btnclass = 'icon-white';
                                $liclass = 'strike';
                            } else {
                                $class = ($transactie->status==0?'disabled':'');
                                $btnclass = '';
                                $liclass = '';
                            }
                        ?>
                        <li data-id="{{ $transactie->id }}" class="{{ $liclass }}">
                            <span>Van: {{ $transactie->van_naam }}</span>
                            <span>{{ $transactie->datum_nl }}</span>
                            <span><a class="btn btn-mini {{ $class }}" href="#"><i class="{{ $btnclass }} icon-arrow-right"></i></a></span>
                            <span title="{{ $transactie->description }}" class="clear">{{ $transactie->description }}</span>
                            <span>{{ prijsify($transactie->bedrag) }}</span>
                        </li>
                    @endforeach
                    </ul>
                </div>
            </div>
            <div class="span6">
                <div class="well">
                    <strong>Debet (af)</strong>
                    <ul class="table debet">
                    @foreach($set->transacties as $transactie)
                        <?php
                            if($transactie->type!='debet')continue;
                            if($transactie->sug_status==1){
                                $class = 'btn-inverse';
                                $btnclass = 'icon-white';
                                $liclass = 'strike';
                            } else {
                                $class = ($transactie->status==0?'disabled':'');
                                $btnclass = '';
                                $liclass = '';
                            }
                        ?>
                        <li data-id="{{ $transactie->id }}" class="{{ $liclass }}">
                            <span><a class="btn btn-mini {{ $class }}" href="#"><i class="{{ $btnclass }} icon-arrow-left"></i></a></span>
                            <span>Naar: {{ $transactie->van_naam }}</span>
                            <span>{{ $transactie->datum_nl }}</span>
                            <span title="{{ $transactie->description }}">{{ $transactie->description }}</span>
                            <span>{{ prijsify($transactie->bedrag) }}</span>
                        </li>
                    @endforeach
                    </ul>
                </div>
            </div>
            <div class="span12" style="text-align: right;">
               <button type="button" style="opacity: 0.5" class="btn btn-primary">Opslaan</button>
            </div>
        </div>

        <hr><hr>
        @endforeach
    </div>
</x-layout>
