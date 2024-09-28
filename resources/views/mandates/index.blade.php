<x-layout>
    <x-slot:title>
        Automatische incasso's
    </x-slot:title>

    <div class="container">
        <div class="row">
            <div class="span6">
                <h3>Automatische incasso's</h3>
            </div>
        </div>
        <div class="row">
            <div class="span13">
                <form action="/mandates" method="post">
                    @csrf
                    <div class="table thead">
                        <span style="width:130px">Incasso ID</span>
                        <span style="width:200px">Van</span>
                        <span style="width:300px">Budget</span>
                    </div>
                    <ul class="table">
                        @foreach($mandates as $mandate)
                            <li style="height: 50px">
                                <span style="width:130px">{{ $mandate->external_id }}</span>
                                <span style="width:200px">{{ $mandate->related_party_name }}</span>
                                <span style="width:300px">
                                    <select name="mandate[{{$mandate->id}}]">
                                        <option value="">Selecteer een budget</option>
                                        @foreach($budgets as $budget)
                                            <option @selected($budget->id == $mandate->budget_id) value="{{$budget->id}}">
                                                {{ $budget->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </span>
                            </li>
                        @endforeach
                    </ul>

                    <button type="submit" class="btn btn-primary">Opslaan</button>
                </form>
            </div>

        </div>
    </div>
</x-layout>
