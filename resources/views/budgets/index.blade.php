<x-layout>
    <x-slot:title>
        Budgets
    </x-slot:title>

    <div class="container">
        <div class="row">
            <div class="span7">
                <h3>Status quo</h3>
                <div class="well">
                    <ul class="budgettable table">
                        <li>
                            <span><strong>Budget</strong></span>
                            <span class="saldo"><strong>Saldo</strong></span>
                            <span class="target"><strong>Increment</strong></span>
                        </li>
                    @foreach($budgetten as $budget)
                        <li>
                            <span>{{ $budget->naam }}</span>
                            <span class="saldo">{{ prijsify($budget->saldo) }}</span>
                            <span class="target">
                                @if($budget->target)
                                    &euro; {{ $budget->target }}
                                @endif
                            </span>
                        </li>
                    @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

</x-layout>
