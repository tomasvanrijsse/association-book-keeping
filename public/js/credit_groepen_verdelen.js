$('select#creditgroep').change(function(){
    if($(this).val()>0){
        $.getJSON('/contribution-periods/'+$(this).val()+'/mutations',
        function(data){
            $('#vrijsaldo').data('vrijsaldo',data.saldo);

            $('ul.boeking input').each(function(){
                if($(this).data('id') in data.boekingen){
                    let increment = data.boekingen[$(this).data('id')];
                    $(this).val(increment).data('increment', increment)
                } else {
                    $(this).val(0);
                }
            });

            resetTotaal();
        });
        $('#overlay').fadeOut();
    } else {
        $('#overlay').fadeIn();
    }
}).change();

$('ul.boeking input').change(function(){
    var id      = $(this).data('id'),
        $stand  = $('#stand'+id+' input'),
        saldo   = parseFloat($('#saldo'+id).data('saldo')) - (parseFloat($(this).data('increment') ?? 0) ),
        boeking = parseFloat($(this).val()),
        stand   = $stand.val();

    if($(this).val()==""){
        boeking = 0;
        $(this).val(0);
    }

    if(saldo+boeking==stand){
        // controleer som (oud + boeking = stand)
        // do nothing
        return true;
    } else {
        $stand.val((saldo + boeking).toFixed(2));
        resetTotaal();
    }
});

$('span.target').on('click', function () {
    var id = $(this).data('id'),
        $boeking = $('#boeking' + id + ' input');
    if ($(this).data('target')) {
        $boeking.val($(this).data('target')).change();
        resetTotaal();
    }
});

$('ul.stand input').change(function(data){
    var id      = $(this).data('id'),
        $boeking= $('#boeking'+id+' input'),
        saldo   = parseFloat($('#saldo'+id).data('saldo')),
        boeking = $boeking.val(),
        stand   = $(this).val();

    if(stand==""){
        stand = 0;
        $(this).val(0);
    }

    if(saldo+boeking==stand){
    // controleer som (oud + boeking = stand)
        // do nothing
        return true;
    } else {
        $boeking.val((stand - saldo).toFixed(2));
        resetTotaal();
    }


});
resetTotaal();
function resetTotaal(){
    // totaal = som van alle standen.
    // als totaal < 0 , opslaan disablen
    var stand = $('#vrijsaldo').data('vrijsaldo');
    $('ul.boeking input').each(function(){
        stand = stand-parseFloat($(this).val());
    });
    stand = stand.toFixed(2) * 1;
    $('#vrijsaldo').html('&euro; '+stand);

    if(stand < 0){
        $('#pilehider').prop('class','s-1');
        $('#saveboekingen').addClass('disabled');
    } else {
        $('#saveboekingen').removeClass('disabled');
        $('#pilehider').prop('class','s'+Math.max( Math.ceil(stand/250)), 20);
    }

}

$('#saveboekingen').click(function(){
    if($(this).hasClass('disabled')) return false;

    $('.boeking input').each(function(){
        var $input  = $(this),
            id      = $input.data('id'),
            $saldo  = $('#saldo'+$(this).data('id')),
            $stand  = $('#stand'+id);
        $input.attr('disabled','disabled').fadeTo(400,0.2);
        $stand.attr('disabled','disabled').fadeTo(400,0.2);
        $saldo.addClass('fadeGlow');

        $.ajax('/contribution-periods/saveBudgetMutation',{
            data:{'budget_id':id,'amount':$(this).val(),'contribution_period_id':$('#creditgroep').val()},
            type:'POST',
            success:function(){
                $saldo.removeClass('fadeGlow');
                $input.fadeTo(400,1).removeAttr('disabled','');
                $stand.fadeTo(400,1).removeAttr('disabled','');
            }
        });
    });
})
