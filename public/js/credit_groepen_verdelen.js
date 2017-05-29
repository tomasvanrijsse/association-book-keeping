$('select#creditgroep').change(function(){
    if($(this).val()>0){
        $.getJSON('/credit/groep_info/'+$(this).val(),
        function(data){
            $('#vrijsaldo').data('vrijsaldo',data.saldo);
            
            $('ul.boeking input').each(function(){
                if($(this).data('id') in data.boekingen){
                    $(this).val(data.boekingen[$(this).data('id')]);
                } else {
                    $(this).val(0);
                }
            })
            
            for(var key in data.budgetten){
                $('#saldo'+key).data('saldo',data.budgetten[key])
                    .find('span:nth-child(2)').html('&euro; '+data.budgetten[key].toFixed(2));
            }
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
        saldo   = parseFloat($('#saldo'+id).data('saldo')),
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
        if(boeking<0){
            $(this).val(0);
            $stand.val(saldo.toFixed(2));
            return false;
        }
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
        if(stand-saldo<0){
            $boeking.val(0);
            $(this).val(saldo.toFixed(2));
            return false;
        }
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
    } else if(stand > 10000){
        $('#pilehider').prop('class','s20');
        $('#saveboekingen').removeClass('disabled');
    } else {
        $('#pilehider').prop('class','s'+Math.ceil(stand/500));
        $('#saveboekingen').removeClass('disabled');
    }
    
}

$('#saveboekingen').click(function(){
    if($(this).hasClass('disabled')) return false;
    
    $('.boeking input').each(function(){
        if($(this).val() >= 0){
            var $input  = $(this),
                id      = $input.data('id'),
                $saldo  = $('#saldo'+$(this).data('id')),
                $stand  = $('#stand'+id);
            $input.attr('disabled','disabled').fadeTo(400,0.2);
            $stand.attr('disabled','disabled').fadeTo(400,0.2);
            $saldo.addClass('fadeGlow');

            $.ajax('/credit/saveGroepBoeking',{
                data:{'budget_id':id,'amount':$(this).val(),'creditgroep_id':$('#creditgroep').val()},
                type:'POST',
                success:function(saldo){
                    saldo = parseFloat(saldo);
                    $saldo.removeClass('fadeGlow');
                    $input.fadeTo(400,1).removeAttr('disabled','');
                    $stand.fadeTo(400,1).removeAttr('disabled','');

                    $saldo.data('saldo',saldo).find('span:nth-child(2)').html('&euro; '+saldo.toFixed(2));
                    $stand.val(saldo.toFixed(2));
                }
            });
        }
    });
})
