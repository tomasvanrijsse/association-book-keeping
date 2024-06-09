<x-layout>
    <x-slot:title>
        Budgets
    </x-slot:title>
    <div class="container">
        <div class="row">
            <div class="span7">
                <h3>Status quo</h3>
                <div class="well">
                    <table class="budgettable table">
                        <thead>
                            <th><strong>Budget</strong></th>
                            <th class="saldo"><strong>Saldo</strong></th>
                            <th class="target"><strong>Increment</strong></th>
                        </thead>
                        <tbody>
                        @foreach($budgetten as $budget)
                            <tr>
                                <td>{{ $budget->naam }}</td>
                                <td class="saldo">{!! prijsify($budget->saldo) !!}</td>
                                <td class="target">
                                    @if($budget->target)
                                        &euro; {{ $budget->target }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-layout>
