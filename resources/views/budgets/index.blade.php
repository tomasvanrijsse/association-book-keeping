<x-layout>
    <x-slot:title>
        Budgets
    </x-slot:title>
    <x-slot:scripts>
        <script>
            $('.budgetDelete').on('click', function(){
                let title = $(this).data('budget-title');
                let confirmed = confirm(`Weet je zeker dat je "${title}" wilt archiveren?`);

                if(confirmed){
                    $.ajax({
                        url: '/budgets/' + $(this).data('budget_id'),
                        type: 'delete',
                    }).done(function() {
                        window.location.reload();
                    })
                }
            });
        </script>
    </x-slot:scripts>
    <div class="container">
        <h3>Status quo</h3>

        <div class="row">
            <div class="span7">
                <div class="well">
                    <table class="budgettable table">
                        <thead>
                            <th><strong>Budget</strong></th>
                            <th class="saldo"><strong>Saldo</strong></th>
                            <th class="target"><strong>Increment</strong></th>
                            <th></th>
                        </thead>
                        <tbody>
                        @foreach($budgets as $budget)
                            <tr>
                                <td><a href="/budgets/{{$budget->id}}">{{ $budget->title }}<a/></td>
                                <td class="saldo">{{ Number::currency($budget->balance, 'EUR') }}</td>
                                <td class="target">
                                    @if($budget->target)
                                        {{ Number::currency($budget->target, 'EUR') }}
                                    @endif
                                </td>
                                <td>
                                    <button class="budgetDelete" data-budget_id="{{$budget->id}}" data-budget-title="{{ $budget->title }}">
                                        <i class="icon-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="span4" style="margin-left:40px;">
                @if($editBudget)
                    <p style="font-weight: bold">Budget aanpassen</p>
                @else
                    <p style="font-weight: bold">Nieuw budget aanmaken</p>
                @endif
                <form action="/budgets/{{ $editBudget?->id }}" id="addBudget" method="POST">
                    @csrf
                    @if($editBudget) @method('PATCH') @endif
                    <div class="control-group">
                        <label class="control-label" for="title">Naam</label>
                        <div class="controls">
                            <input type="text" name="title" id="title" @if($editBudget)value="{{$editBudget->title}}"@endif />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="target">Maandelijkse inleg</label>
                        <div class="controls">
                            <input type="number" name="target" id="target" @if($editBudget)value="{{$editBudget->target}}"@endif />
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="controls">
                            <button type="submit" class="btn">Opslaan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>
