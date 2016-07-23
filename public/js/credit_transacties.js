$("#transactions li").draggable({
    revert: "invalid",
    containment : '#dragcontainment',
    cursor:'move'
});

$('#budgetten li').droppable({
    hoverClass: "accept",
    tolerance : 'pointer',
    drop:function(event,ui){
        var $budget = $(this);
        ui.draggable.fadeOut(function(){
            $(this).remove();
            // pajinate refresh/update call is needed
            // zonder is de lijst fucked na wisselen van pagina's
            //$('#transactions li:visible').last().next().fadeIn();
        });
        $.ajax({
            url:'/credit/transactieBudget',
            data:{
                tid:ui.draggable.data('id'),
                bid:$budget.data('id')
            },
            success:function(data){
                $budget.find('span.saldo').html(data);
            },
            method:'POST'
        });
    }
});

$(document).ready(function(){
    $('#transactions').parent().pajinate({
        items_per_page : 10,
        show_first_last: false,
        item_container_id:'#transactions',
        abort_on_small_lists: true
    });
}); 
